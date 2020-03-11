<!DOCTYPE html>

<%@page import="org.apache.commons.lang3.time.DateUtils"%>
<%@page import="java.util.Date"%>
<%@page import="java.net.InetAddress"%>
<%@page import="java.text.SimpleDateFormat"%>
<%@ page language="java" pageEncoding="UTF-8" contentType="text/html;charset=utf-8"%>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c"%>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>商户模拟退款交易</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="${pageContext.request.contextPath}/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript"></script>
</head>

<body>
	<div>
	
		<form method="post" id="transForm" name="transForm" action="/pgw-pay-test/payRefund/payRefund">
			<table>
	
				<tr>
					<td height="24px">测试地址:</td>
					<td>
						<input type="radio" id="url_UAT" name="url" checked="checked"
							value="https://112.65.144.19:9204/pgw-pay/refund" />外网测试
							&nbsp;&nbsp;
					</td>
				</tr>
	
				<tr>
					<td height="24px">版本号:</td>
					<td><input type="text" id="version" name="version" value="1.0.1" /></td>
				</tr>
				
				<tr>
					<td height="24px">商户编号:</td>
					<td>
						<input type="text" id="merchantId" name="merchantId" value="000010357"></input>
					</td>
				</tr>

				<tr>
					<td height="24px">退货订单号：</td>
					<td><input type="text" id="refundOrderId" name="refundOrderId" value="<%= new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) %>" /></td>
				</tr>

			 	<tr>
					<td height="24px">请求时间reqDate:</td>
					<td>
						<input type="text" style="width: 130px;" value="<%= new SimpleDateFormat("yyyyMMdd HH:mm:ss").format(new Date()) %>" 
							id="reqDate" name="reqDate" onfocus="WdatePicker({dateFmt:'yyyyMMdd HH:mm:ss'})" />
					</td>
				</tr>

				<tr>
					<td height="24px">金额：</td>
					<td><input type="text" id="amount" name="amount" value="" />
					</td>
				</tr>

				<tr>
					<td height="24px">原交易的订单号：</td>
					<td><input type="text" id="orgPayOrderId" name="orgPayOrderId" value="" /></td>
				</tr>
			
				<tr>
					<td height="30px" colspan="2" align="center" width="100px"><input type="submit" id="form_submit" value="提  交" /></td>
				</tr>
				
			</table>
		</form>
		
	</div>
</body>

</html>