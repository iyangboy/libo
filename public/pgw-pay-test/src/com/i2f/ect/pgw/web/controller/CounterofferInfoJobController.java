/**
 * Project Name : pgw-pay-test
 * File Name : PaymentClientController.java
 * Package Name : com.i2f.ect.pgw.web.controller
 * Date : 2017年7月29日下午2:40:46
 * Copyright (c) 2015, i2Finance Software All Rights Reserved
 */
package com.i2f.ect.pgw.web.controller;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.InvocationTargetException;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.io.IOUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import com.i2f.ect.pgw.model.pay.CounterofferInfoResult;
import com.i2f.ect.pgw.model.pay.CounterofferInforeq;
import com.i2f.ect.pgw.utils.DateUtils;
import com.i2f.ect.pgw.utils.ECTServerXmlUtil;
import com.i2f.ect.pgw.utils.MerchantSignAndVerify;
import com.i2f.ect.pgw.utils.R;

/**
 * 批量代收付回盘通知接口测试
 * @author WPC
 *
 */
@Controller
@RequestMapping("/Counteroffer")
public class CounterofferInfoJobController {
	public static final Logger logger = LoggerFactory.getLogger(SendDiscController.class);
	//新的方法中直接以Http Post请求方式，请求证通对应接口
	@RequestMapping("/doPay")
	@ResponseBody
	public String doPay(HttpServletRequest request) throws UnsupportedEncodingException, IllegalAccessException, InstantiationException, InvocationTargetException, NoSuchMethodException{
		CounterofferInforeq counterofferInforeq = new CounterofferInforeq();
		CounterofferInfoResult counterofferInfoResult = new CounterofferInfoResult(); 
		String xmlString = null;
		//解析请求request 转换成map
		try {
			xmlString = IOUtils.toString(request.getReader());
			logger.info("请求IP={}，服务端接受报文={}", request.getRemoteAddr(),xmlString);
			counterofferInforeq = ECTServerXmlUtil.xmlToObject(xmlString, CounterofferInforeq.class);
			//进行验签
			Map<String, String> map = new HashMap<String, String>();
			map.put("batchNo", counterofferInforeq.getBatchNo());
			map.put("merchantId",counterofferInforeq.getMerchantId());
			map.put("status", counterofferInforeq.getStatus());
			map.put("reqTime", counterofferInforeq.getReqTime());
			map.put("fileName", counterofferInforeq.getFileName());
			map.put("sign", counterofferInforeq.getSign());
			String result_sign = counterofferInforeq.getSign();
			String to_verify = MerchantSignAndVerify.createLinkString(map);
			if(MerchantSignAndVerify.verify(to_verify.getBytes(R.PGWConstant.UTF8), result_sign.getBytes(R.PGWConstant.UTF8))){
				logger.info("验签成功");
			}else{
				logger.error("验签失败");
				counterofferInfoResult.setMerchantId(counterofferInforeq.getMerchantId());
				counterofferInfoResult.setBatchNo(counterofferInforeq.getBatchNo());
				counterofferInfoResult.setReqTime(DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				counterofferInfoResult.setStatus("F");
				Map<String, String> maps = new HashMap<String, String>();
				maps.put("merchantId", counterofferInforeq.getMerchantId());
				maps.put("batchNo", counterofferInforeq.getBatchNo());
				maps.put("status", counterofferInfoResult.getStatus());
				maps.put("reqTime",DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				//获得需要进行加签的字符串（通过拼接元素）
				String preSignStr = MerchantSignAndVerify.createLinkString(maps);
				//调用CFCA方法得到加签sign
				String signedString = new String(MerchantSignAndVerify.sign(preSignStr, counterofferInforeq.getMerchantId()));
				counterofferInfoResult.setSign(signedString);
				return ECTServerXmlUtil.objectToXml(counterofferInfoResult, ECTServerXmlUtil.CPRES_QBCRES);
			};
			
		}catch (Exception e) {
			logger.error("验证报文出现异常");
			try {
				counterofferInfoResult.setBatchNo(counterofferInforeq.getBatchNo());
				counterofferInfoResult.setMerchantId(counterofferInforeq.getMerchantId());
				counterofferInfoResult.setReqTime(DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				counterofferInfoResult.setStatus("F");
				Map<String, String> maps = new HashMap<String, String>();
				maps.put("merchantId", counterofferInforeq.getMerchantId());
				maps.put("batchNo", counterofferInforeq.getBatchNo());
				maps.put("status", counterofferInfoResult.getStatus());
				maps.put("reqTime",DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				//获得需要进行加签的字符串（通过拼接元素）
				String preSignStr = MerchantSignAndVerify.createLinkString(maps);
				logger.info("批量异步通知应答报文={}", preSignStr);
				//调用CFCA方法得到加签sign
				String signedString = new String(MerchantSignAndVerify.sign(preSignStr, counterofferInforeq.getMerchantId()));
				counterofferInfoResult.setSign(signedString);
				return  ECTServerXmlUtil.objectToXml(counterofferInfoResult, ECTServerXmlUtil.CPRES_QBCRES);
			} catch (Exception e1) {
				return ECTServerXmlUtil.objectToXml(counterofferInfoResult, ECTServerXmlUtil.CPRES_QBCRES);
			}
		}
		//业务处理
		try {
			logger.info("处理回盘通知成功。。。。");
			counterofferInfoResult.setBatchNo(counterofferInforeq.getBatchNo());
			counterofferInfoResult.setMerchantId(counterofferInforeq.getMerchantId());
			counterofferInfoResult.setReqTime(DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
			counterofferInfoResult.setStatus("T");
			Map<String, String> maps = new HashMap<String, String>();
			maps.put("merchantId", counterofferInforeq.getMerchantId());
			maps.put("batchNo", counterofferInforeq.getBatchNo());
			maps.put("status", counterofferInfoResult.getStatus());
			maps.put("reqTime",DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
			//获得需要进行加签的字符串（通过拼接元素）
			String preSignStr = MerchantSignAndVerify.createLinkString(maps);
			//调用CFCA方法得到加签sign
			String signedString = new String(MerchantSignAndVerify.sign(preSignStr, counterofferInforeq.getMerchantId()));
			counterofferInfoResult.setSign(signedString);
		} catch (Exception e) {
			logger.error("加签返回报文出现异常");
			try {
				counterofferInfoResult.setBatchNo(counterofferInforeq.getBatchNo());
				counterofferInfoResult.setMerchantId(counterofferInforeq.getMerchantId());
				counterofferInfoResult.setReqTime(DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				counterofferInfoResult.setStatus("F");
				Map<String, String> maps = new HashMap<String, String>();
				maps.put("merchantId", counterofferInforeq.getMerchantId());
				maps.put("batchNo", counterofferInforeq.getBatchNo());
				maps.put("status", counterofferInfoResult.getStatus());
				maps.put("reqTime",DateUtils.toString(DateUtils.DATETIME_FULL_QUICK_PAY));
				//获得需要进行加签的字符串（通过拼接元素）
				String preSignStr = MerchantSignAndVerify.createLinkString(maps);
				//调用CFCA方法得到加签sign
				String signedString = new String(MerchantSignAndVerify.sign(preSignStr, counterofferInforeq.getMerchantId()));
				counterofferInfoResult.setSign(signedString);
				return ECTServerXmlUtil.objectToXml(counterofferInfoResult, ECTServerXmlUtil.CPRES_QBCRES);
			} catch (Exception e1) {
				return ECTServerXmlUtil.objectToXml(counterofferInfoResult, ECTServerXmlUtil.CPRES_QBCRES);
			}
		}
		return ECTServerXmlUtil.objectToXml(counterofferInfoResult, ECTServerXmlUtil.CPRES_QBCRES);
	}
	
}
