package com.i2f.ect.pgw.web.controller;

import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.lang.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.ModelAndView;

import com.i2f.ect.pgw.utils.Base64Utils;
import com.i2f.ect.pgw.utils.ECTXmlUtil;
import com.i2f.ect.pgw.utils.HttpClientUtil;
import com.i2f.ect.pgw.utils.MerchantSignAndVerify;
import com.i2f.ect.pgw.utils.RSAUtils;

@Controller
@RequestMapping("/quickPay")
public class QucikPaySignController {
public static final Logger logger = LoggerFactory.getLogger(QucikPaySignController.class);

	//新的方法中直接以Http Post请求方式，请求证通对应接口
	@RequestMapping("/sign")  
	public ModelAndView identityAuth(HttpServletRequest request) throws Exception{
		String url = "http://localhost:8080/pgw-pay/quickpay/sign";
		
		ModelAndView mav = new ModelAndView("result"); 
		
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
		url = map.remove("url");
		
		//获得需要进行加签的字符串（通过拼接元素）
		String preSignStr = MerchantSignAndVerify.createLinkString(map);
		logger.info(preSignStr);
		//调用CFCA方法得到加签sign
		String signedString = new String(MerchantSignAndVerify.sign(preSignStr, merchantId));
		//加签sign放入map
		map.put("sign", signedString);
		
		//拼装成xml请求报文，并发送post请求
		//这里只是给出了一种写法，开发者可以自由编写，只要请求报文符合接口文档的规范
		String xmlString = ECTXmlUtil.mapToXml(map, ECTXmlUtil.CPREQ_SIREQ);
		logger.info("商户签约接口测试请求报文：" + xmlString);
		String responseString = HttpClientUtil.postToServerByXml(xmlString, url);
		logger.info("证通返回报文：" + responseString);
		
		//将返回的xml字符串解析成map，map中包含了<CSReq>标签内的元素
		Map<String, String> resultMap = ECTXmlUtil.xmlToMap(responseString);
		String result_sign = resultMap.get("sign");
		String to_verify = MerchantSignAndVerify.createLinkString(resultMap);
		
		//调用CFCA方法进行验签
		if(MerchantSignAndVerify.verify(to_verify.getBytes(), result_sign.getBytes())){
			logger.info("验签成功");
			mav.addObject("responseString", responseString);
			mav.addObject("resultMap", resultMap);
		}else{
			logger.error("验签失败");
			mav.addObject("responseString", "验签失败，数据可能被篡改");
		};
		return mav;
	}
	
	@RequestMapping("/signPage")
	public ModelAndView signPage(){
		logger.info("进入签约接口");
		ModelAndView model = new ModelAndView("quickPaySign");
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
