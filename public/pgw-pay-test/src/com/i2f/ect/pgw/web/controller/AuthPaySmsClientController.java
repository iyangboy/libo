/**
 * Project Name : pgw-pay-test
 * File Name : PaymentClientController.java
 * Package Name : com.i2f.ect.pgw.web.controller
 * Date : 2015年8月26日下午2:40:46
 * Copyright (c) 2015, i2Finance Software All Rights Reserved
 */
package com.i2f.ect.pgw.web.controller;

import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.ModelAndView;

import com.i2f.ect.pgw.utils.Base64Utils;
import com.i2f.ect.pgw.utils.ECTXmlUtil;
import com.i2f.ect.pgw.utils.HttpClientUtil;
import com.i2f.ect.pgw.utils.MerchantSignAndVerify;
import com.i2f.ect.pgw.utils.R;
import com.i2f.ect.pgw.utils.RSAUtils;

@Controller
@RequestMapping("/authpaysms")
public class AuthPaySmsClientController {
	
	public static final Logger logger = LoggerFactory.getLogger(AuthPaySmsClientController.class);
	
	public static String LOCALHOST_URL = "";

	//新的方法中直接以Http Post请求方式，请求证通对应接口
	@RequestMapping("/doPay")  
	public ModelAndView doPay(HttpServletRequest request) throws Exception{
		
		//将所有参数封装成map
		String merchantId = request.getParameter("merchantId");
		Map<String, String[]> parameterMap = request.getParameterMap();
		Map<String, String> map = new HashMap<String, String>();
		for(Map.Entry<String, String[]>  mapEntry: parameterMap.entrySet()){
			if("cvv2".equals(mapEntry.getKey().toLowerCase()) || "validdate".equals(mapEntry.getKey().toLowerCase())) {
				String miString = encrypt(mapEntry.getValue()[0]);
		        map.put(mapEntry.getKey(), miString); 
		        continue;
			}
			map.put(mapEntry.getKey(), mapEntry.getValue()[0]);
		}
		
		//map中去除url（请求页面中选择的目标地址，非报文项），url不是报文参数，不能加签
		//remove方法返回的是被除掉的对应元素，即从页面取到的服务器URL
		LOCALHOST_URL = map.remove("url");
		//如果网联类型为空，则不传网联
		if (StringUtils.isBlank(map.get("payType"))) {
			map.remove("payType");
		}
		//获得需要进行加签的字符串（通过拼接元素）
		String preSignStr = MerchantSignAndVerify.createLinkString(map);
		logger.info(preSignStr);
		//调用CFCA方法得到加签sign
		String signedString = new String(MerchantSignAndVerify.sign(preSignStr, merchantId));
		//加签sign放入map
		map.put("sign", signedString);
		
		//拼装成xml请求报文，并发送post请求
		//这里只是给出了一种写法，开发者可以自由编写，只要请求报文符合接口文档的规范
		String xmlString = ECTXmlUtil.mapToXml(map, ECTXmlUtil.CPREQ_ATREQ);
		logger.info("测试发出报文：" + xmlString);
		String responseString = HttpClientUtil.postToServerByXml(xmlString, LOCALHOST_URL);
		logger.info("证通返回报文：" + responseString);
		
		//将返回的xml字符串解析成map，map中包含了<CSReq>标签内的元素
		Map<String, String> resultMap = ECTXmlUtil.xmlToMap(responseString);
		String result_sign = resultMap.get("sign");
		String to_verify = MerchantSignAndVerify.createLinkString(resultMap);
		
		ModelAndView mav = new ModelAndView("result"); 
		//调用CFCA方法进行验签
		if(MerchantSignAndVerify.verify( to_verify.getBytes(R.PGWConstant.UTF8), result_sign.getBytes(R.PGWConstant.UTF8))){
			logger.info("验签成功");
			mav.addObject("responseString", responseString);
			mav.addObject("resultMap", resultMap);
		}else{
			logger.error("验签失败");
			mav.addObject("responseString", "验签失败，数据可能被篡改");
		};
		
		return mav;
	}
	
	@RequestMapping("/authpaySmsPage")
	public ModelAndView goPayPage(){
		logger.info("进入快捷支付测试输入页面");
		ModelAndView model = new ModelAndView("authpaysms");
		return model;
	}
	
	private String encrypt(String str) throws Exception {
		if(StringUtils.isNotBlank(str)) {
			byte[] ming =str.getBytes(Base64Utils.UTF_8);
			byte[] mi = RSAUtils.encryptByPublicKey(ming, "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCme526ar35LeqRQaWzFvAd2NTKPmHdNfizeubSO3l+bkSrLT/hB3ZS46FGCK1r4SM6/Ka19ej2VP8oWrOrBUzk10pUBuvcBu7c2raVTs8oPM9O/notC460TPO9Bg8247lborjZGm9Fv0nlHhN0RYoUYUVuzvQkbBtpCqTO8sNeewIDAQAB");
	        return Base64Utils.encode(mi);
		}
		
		return "";
	}
	
}
