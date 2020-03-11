/**
 * Project Name : pgw-pay-test
 * File Name : PaymentClientController.java
 * Package Name : com.i2f.ect.pgw.web.controller
 * Date : 2017年7月29日下午2:40:46
 * Copyright (c) 2015, i2Finance Software All Rights Reserved
 */
package com.i2f.ect.pgw.web.controller;

import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.io.IOUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import com.i2f.ect.pgw.utils.DateUtils;
import com.i2f.ect.pgw.utils.ECTServerXmlUtil;
import com.i2f.ect.pgw.utils.MerchantSignAndVerify;
import com.i2f.ect.pgw.utils.R;

/**
 * 测试银行端解约通知
 * @author hewt
 *
 */
@Controller
@RequestMapping("/bankTerminal")
public class BankTerminalNotify {
	public static final Logger logger = LoggerFactory.getLogger(BankTerminalNotify.class);
	//新的方法中直接以Http Post请求方式，请求证通对应接口
	@RequestMapping("/signOut")
	@ResponseBody
	public String doPay(HttpServletRequest request) throws Exception{
		
		String merchantId = "";
		String xmlString = null;
		Map<String, String> retMap = new HashMap<String, String>();
		//解析请求request 转换成map
		try {
			xmlString = IOUtils.toString(request.getReader());
			logger.info("请求IP={}，服务端接受报文={}", request.getRemoteAddr(),xmlString);
			Map<String, String> map = ECTServerXmlUtil.xmlToMap(xmlString);
			merchantId = map.get("merchantId");//商户号
			String protocolId = map.get("protocolId");
			String account = map.get("account");
			//进行验签
			String result_sign = map.get("sign");
			String to_verify = MerchantSignAndVerify.createLinkString(map);
			if(MerchantSignAndVerify.verify(to_verify.getBytes(R.PGWConstant.UTF8), result_sign.getBytes(R.PGWConstant.UTF8))){
				logger.info("验签成功");
				//添加返回参数
				retMap.put("merchantId", merchantId);
				retMap.put("respDate", DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				retMap.put("retFlag", "T");
				retMap.put("resultCode", "0000");
				retMap.put("resultMsg", "异步通知处理成功");
				retMap.put("protocolId", protocolId);
				retMap.put("account", account);
				
				//获得需要进行加签的字符串（通过拼接元素）
				String preSignStr = MerchantSignAndVerify.createLinkString(retMap);
				//调用CFCA方法得到加签sign
				String signedString = new String(MerchantSignAndVerify.sign(preSignStr, merchantId));
				retMap.put("sign", signedString);
				return ECTServerXmlUtil.mapToResponseXml(retMap, ECTServerXmlUtil.CPRES_BTRES);
				
			}else{
				logger.error("验签失败");
				retMap.put("merchantId", merchantId);
				retMap.put("respDate", DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				retMap.put("retFlag", "F");
				retMap.put("resultCode", "9999");
				retMap.put("resultMsg", "验签失败");
				retMap.put("protocolId", protocolId);
				retMap.put("account", account);
				
				//获得需要进行加签的字符串（通过拼接元素）
				String preSignStr = MerchantSignAndVerify.createLinkString(retMap);
				//调用CFCA方法得到加签sign
				String signedString = new String(MerchantSignAndVerify.sign(preSignStr, merchantId));
				retMap.put("sign", signedString);
				return ECTServerXmlUtil.mapToResponseXml(retMap, ECTServerXmlUtil.CPRES_BTRES);
			}
			
		}catch (Exception e) {
			logger.error("验证报文出现异常");
			retMap.put("respDate", DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
			retMap.put("retFlag", "F");
			retMap.put("resultCode", "9999");
			retMap.put("respDate", "验签失败");
			return  ECTServerXmlUtil.mapToResponseXml(retMap, ECTServerXmlUtil.CPRES_BTRES);
		}
	}
	
}
