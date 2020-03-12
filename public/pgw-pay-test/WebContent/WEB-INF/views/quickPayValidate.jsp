<%@ page language="java" pageEncoding="UTF-8" contentType="text/html;charset=utf-8"%>
<!DOCTYPE html>
<%@page import="org.apache.commons.lang3.time.DateUtils"%>
<%@page import="java.util.Date"%>
<%@page import="java.net.InetAddress"%>
<%@page import="java.text.SimpleDateFormat"%>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>商户模拟快捷支付交易</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="${pageContext.request.contextPath}/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript">
	window.onload = function() {
		//$("#parentMerchantId").val($("#merchantId").val());	
	}

</script>
</head>

<body>
	<div>
	
		<form method="post" id="transForm" name="transForm" action="${pageContext.request.contextPath}/quickPayValidate/doPay">
			<table>
			
				<tr>
					<td height="24px">测试地址:</td>
					<td>
						<input type="radio" id="url_UAT" name="url" checked="checked"
							value="https://112.65.144.19:9204/pgw-pay/quickpay/smsvalidate" />外网测试
					</td>
				</tr>
				
				<tr>
					<td height="24px">版本号:</td>
					<td><input type="text" id="version" name="version" value="1.0.1" /></td>
				</tr>
				
				<tr>
					<td height="24px">商户编号:</td>
					<td>
						<input type="text" id="merchantId" name="merchantId" value="000010957"></input>
					</td>
				</tr>
				
				<tr>
					<td height="24px">商户请求时间:</td>
					<td>
						<input type="text" id="reqDate" name="reqDate" value="<%= new SimpleDateFormat("yyyyMMdd HH:mm:ss").format(new Date()) %>"></input>
					</td>
				</tr>
				
				<tr>
					<td height="24px">商户订单编号:</td>
					<td><input value="<%= new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) %>" id="payOrderId" name="payOrderId" onfocus="WdatePicker({dateFmt:'yyyyMMddHHmmss'})" /></td>
				</tr>
				
				<tr>
					<td height="24px">订单支付金额:</td>
					<td><input type="text" id="amount" name="amount" value="" /></td>
				</tr>
				
				<tr>
					<td height="24px">绑定协议号:</td>
					<td><input type="text" id="protocolId" name="protocolId" value="" /></td>
				</tr>
				
				<tr>
					<td height="24px">短信发送编号:</td>
					<td><input type="text" id="smsSendNo" name="smsSendNo" value="" /></td>
				</tr>
				<tr>
					<td height="24px">短信验证码:</td>
					<td><input type="text" id="smsVerifyCode" name="smsVerifyCode" value="" /></td>
				</tr>
				
				

				<tr>
					<td height="30px" colspan="2" align="center" width="100px"><input type="submit" id="form_subxmit" value="提  交" /></td>
				</tr>
				
			</table>
		</form>
		
	</div>
</body>

</html>