/**
 * Project Name : pgw-consum
 * File Name : SignController.java
 * Package Name : com.i2f.ect.pgw.web.controller
 * Date : 2015年8月26日下午2:40:46
 * Copyright (c) 2015, i2Finance Software All Rights Reserved
 *
 */
package com.i2f.ect.pgw.web.controller;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.ModelAndView;

import com.i2f.ect.pgw.utils.ECTXmlUtil;
import com.i2f.ect.pgw.utils.HttpClientUtil;
import com.i2f.ect.pgw.utils.MerchantSignAndVerify;
import com.i2f.ect.pgw.utils.R;

@Controller
@RequestMapping("/enterprise")
public class EnterprisePayClientController {
	
	public static final Logger logger = LoggerFactory.getLogger(EnterprisePayClientController.class);
	
	public static String LOCALHOST_URL = "http://127.0.0.1:8080/pgw-pay/enterprisepay";

	//新的方法中直接以Http post请求方式，请求证通对应接口
	@RequestMapping("/pay")
	public ModelAndView goBindCard(HttpServletRequest request) throws UnsupportedEncodingException {
		ModelAndView mav = new ModelAndView("result"); 
		
		//将所有参数封装成map
		String merchantId = request.getParameter("merchantId");
		Map<String, String[]> parameterMap = request.getParameterMap();
		Map<String ,String> map = new HashMap<String, String>();
		for(Map.Entry<String, String[]> m : parameterMap.entrySet()){
			map.put(m.getKey(), m.getValue()[0]);
		}

		//map中去除 url，url不是报文参数，不能加签
		//remove返回的是被去除的对应元素，即是页面上输入的元素
		LOCALHOST_URL = map.remove("url");
		//如果网联类型为空，则不传网联
		if (StringUtils.isBlank(map.get("orderBizzType"))) {
			map.remove("orderBizzType");
		}
		
		//加签
		String to_sign = MerchantSignAndVerify.createLinkString(map);
		//调用CFCA方法得到加签 sign
		String sign = new String(MerchantSignAndVerify.sign(to_sign, merchantId));
		//加签sign放入map
		map.put("sign", sign);
		
		//拼装成xml请求报文，并发送post请求,  			
		//这里只是给出了一种写法，开发者可以自由编写只要请求报文符合接口文档的规范
		String xmlString = ECTXmlUtil.mapToXml(map, ECTXmlUtil.CPREQ_EPREQ);
		logger.info("测试发出报文：" + xmlString);
		String responseString = HttpClientUtil.postToServerByXml(xmlString, LOCALHOST_URL);
		logger.info("证通返回报文：" + responseString);
		
		//验签
		//xml解析成map，map中包含了 <CSReq>标签内的元素
		Map<String, String> resultMap = ECTXmlUtil.xmlToMap(responseString);
		String result_sign = resultMap.get("sign");
		String to_verify = MerchantSignAndVerify.createLinkString(resultMap);
		
		if(MerchantSignAndVerify.verify(to_verify.getBytes(R.PGWConstant.UTF8), result_sign.getBytes(R.PGWConstant.UTF8))){
			logger.info("验签成功");
			mav.addObject("responseString", responseString);
			mav.addObject("resultMap", resultMap);
		}else{
			logger.error("验签失败");
			mav.addObject("responseString", "验签失败，数据可能被篡改");
		}
		
		//后续操作
		return mav;
	}

	@RequestMapping("/page")
	public ModelAndView goBindCardpage(){
		ModelAndView model = new ModelAndView("enterprisePay");
		return model;
	}
	
}
