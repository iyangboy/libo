package com.i2f.ect.pgw.web.controller;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.InvocationTargetException;
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
@RequestMapping("/quickPay")
public class QuickPayTradeStatusController {
	
	public static final Logger logger = LoggerFactory.getLogger(QuickPayTradeStatusController.class);

	//新的方法中直接   已 Http post请求方式，请求政通 对应接口
	@RequestMapping("/tradeStatus")  
	public ModelAndView goBindCard(HttpServletRequest request) throws UnsupportedEncodingException, IllegalAccessException, InstantiationException, InvocationTargetException, NoSuchMethodException{
		String url = "http://localhost:8080/pgw-pay/quickpay/tradestatus";
		
		ModelAndView mav = new ModelAndView("result"); 
		//加签前处理
		String merchantId = request.getParameter("merchantId");
		Map<String, String[]> parameterMap = request.getParameterMap();
		Map<String ,String> map = new HashMap<String, String>();
		for(Map.Entry<String, String[]> m:parameterMap.entrySet()){
			map.put(m.getKey(), m.getValue()[0]);
		}
		

		//map中去除 url,url不是报文参数,不能加签
		url = map.remove("url");
		
		String to_sign = MerchantSignAndVerify.createLinkString(map);
		//调用CFCA方法 得到加签 String
		String sign = new String(MerchantSignAndVerify.sign(to_sign, merchantId));
		map.put("sign", sign);
		
		
		//拼装成xml请求报文,并发送post请求,  			
		//这里只是给出了一种写法,开发者可以自由编写只要请求报文符合接口文档的规范
		String xmlString = ECTXmlUtil.mapToXml(map,ECTXmlUtil.CPREQ_TSREQ);
		logger.info("测试发出报文："+ xmlString);
		String responseString = HttpClientUtil.postToServerByXml(xmlString, url);
		logger.info("证通返回报文:"+responseString);
		
		//验签
		Map<String,String> resultMap = ECTXmlUtil.xmlToMap(responseString);
		String result_sign = resultMap.get("sign");
		String to_verify = MerchantSignAndVerify.createLinkString(resultMap);
		if(MerchantSignAndVerify.verify(to_verify.getBytes(), result_sign.getBytes())){
			logger.info("验签成功");
			mav.addObject("responseString", responseString);
			mav.addObject("resultMap", resultMap);
		}else{
			logger.error("验签失败");
			mav.addObject("responseString", "验签失败,数据可能被篡改");
		};
	 
		return mav;
	}
	
	
	
	@RequestMapping("/tradeStatusPage")
	public ModelAndView goToConsum(){
		ModelAndView model = new ModelAndView("quickPayTradeStatus");
		return model;
	}
	

	
}