package com.i2f.ect.pgw.model.pay;

import java.io.Serializable;

public  class BasePayObject implements Serializable {
	
	private static final long serialVersionUID = 1L;
	
	protected String sign;			//R		报文摘要签名
	protected String merchantId;	//R		商户编号
 
	public String getMerchantId() {
		return merchantId;
	}

	public void setMerchantId(String merchantId) {
		this.merchantId = merchantId;
	}

	public String getSign() {
		return sign;
	}

	public void setSign(String sign) {
		this.sign = sign;
	}
	
}