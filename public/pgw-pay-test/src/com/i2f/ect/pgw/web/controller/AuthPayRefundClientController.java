package com.i2f.ect.pgw.web.controller;

import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.ModelAndView;

import com.i2f.ect.pgw.utils.ECTXmlUtil;
import com.i2f.ect.pgw.utils.HttpClientUtil;
import com.i2f.ect.pgw.utils.MerchantSignAndVerify;

@Controller
@RequestMapping("/authpay")
public class AuthPayRefundClientController {
	
	public static final Logger logger = LoggerFactory.getLogger(PayRefundClientController.class);
	public static String LOCALHOST_URL = "http://localhost:9080/pgw-quickpay/authpay/refund";
	
	//直接以Http post请求方式，请求证通对应接口
	@RequestMapping("/authpayRefund")  
	public ModelAndView goBindCard(HttpServletRequest request) throws Exception{
		ModelAndView mav = new ModelAndView("result"); 
		
		//将所有参数封装成map
		String merchantId = request.getParameter("merchantId");
		Map<String, String[]> parameterMap = request.getParameterMap();
		Map<String, String> map = new HashMap<String, String>();
		for(Map.Entry<String, String[]> m : parameterMap.entrySet()){
			map.put(m.getKey(), m.getValue()[0]);
		}

		//map中去除url，url不是报文参数，不能加签
		LOCALHOST_URL = map.remove("url");
		
		//加签
		String to_sign = MerchantSignAndVerify.createLinkString(map);
		//调用CFCA方法得到加签sign
		String sign = new String(MerchantSignAndVerify.sign(to_sign, merchantId));
		//加签sign放入map
		map.put("sign", sign);

		//拼装成xml请求报文，并发送post请求		
		//这里只是给出了一种写法，开发者可以自由编写只要请求报文符合接口文档的规范
		String xmlString = ECTXmlUtil.mapToXml(map,ECTXmlUtil.CPREQ_ARREQ);
		logger.info("测试发出报文：\n" + xmlString);
		String responseString = HttpClientUtil.postToServerByXml(xmlString, LOCALHOST_URL);
		logger.info("证通返回报文：\n" + responseString);
		
		//验签
		//xml解析成map，map中包含了<CSReq>标签内的元素
		Map<String,String> resultMap = ECTXmlUtil.xmlToMap(responseString);
		String result_sign = resultMap.get("sign");
		String to_verify = MerchantSignAndVerify.createLinkString(resultMap);
		if(MerchantSignAndVerify.verify(to_verify.getBytes(), result_sign.getBytes())){
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

	@RequestMapping("/authpayRefundPage")
	public ModelAndView goBindCardpage(){
		ModelAndView model = new ModelAndView("authpayRefund");
		return model;
	}
	
}