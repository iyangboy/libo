package com.i2f.ect.pgw.model.pay;

/**
 * 批量代收回盘通知请求实体类
 * @author WPC
 *
 */
public class CounterofferInforeq extends BasePayObject{
	private String merchantId;//商户标识
	private String batchNo;//批次号
	private String status;//批量处理状态
	private String reqTime;//请求时间
	private String fileName;//文件名
	private String sign;//签名信息
	
    
	public String getFileName() {
		return fileName;
	}
	public void setFileName(String fileName) {
		this.fileName = fileName;
	}
	public String getMerchantId() {
		return merchantId;
	}
	public void setMerchantId(String merchantId) {
		this.merchantId = merchantId;
	}
	public String getBatchNo() {
		return batchNo;
	}
	public void setBatchNo(String batchNo) {
		this.batchNo = batchNo;
	}
	public String getStatus() {
		return status;
	}
	public void setStatus(String status) {
		this.status = status;
	}
	public String getReqTime() {
		return reqTime;
	}
	public void setReqTime(String reqTime) {
		this.reqTime = reqTime;
	}

	public String getSign() {
		return sign;
	}
	public void setSign(String sign) {
		this.sign = sign;
	}
	

}
