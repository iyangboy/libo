<!DOCTYPE html>

<%@page import="org.apache.commons.lang3.time.DateUtils"%>
<%@page import="java.util.Date"%>
<%@page import="java.net.InetAddress"%>
<%@page import="java.text.SimpleDateFormat"%>
<%@ page language="java" pageEncoding="UTF-8" contentType="text/html;charset=utf-8"%>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c"%>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>商户批量代收付送盘</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="${pageContext.request.contextPath}/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript"></script>
</head>

<body>
	<div>
	
		<form method="post" id="transForm" name="transForm" action="/pgw-pay-test/batchNotify/doPay">
			<table>
	
				<tr>
					<td height="24px">测试地址:</td>
					<td>
						<input type="radio" id="url_UAT" name="url" checked="checked"
							value="https://112.65.144.19:9204/pgw-pay/batchNotify" />外网测试
							&nbsp;&nbsp;
					</td>
				</tr>
	
				<tr>
					<td height="24px">版本号:</td>
					<td><input type="text" id="vision" name="vision" value="1.0.1" /></td>
				</tr>
				
				<tr>
					<td height="24px">商户标识：</td>
					<td><input type="text" id="merchantId" name="merchantId" value="000010055" /></td>
				</tr>

			 	<tr>
					<td height="24px">请求时间:</td>
					<td>
						<input type="text" style="width: 130px;" value="<%= new SimpleDateFormat("yyyyMMdd HH:mm:ss").format(new Date()) %>" 
							id="reqTime" name="reqTime" onfocus="WdatePicker({dateFmt:'yyyyMMdd HH:mm:ss'})" />
					</td>
				</tr>
				
				<tr>
					<td height="24px">预约处理时间:</td>
					<td>
						<input type="text" style="width: 130px;" value="<%= new SimpleDateFormat("yyyyMMdd HH:mm:ss").format(new Date()) %>" 
							id="preDoingTime" name="preDoingTime" onfocus="WdatePicker({dateFmt:'yyyyMMdd HH:mm:ss'})" />
					</td>
				</tr>
				
				<tr>
					<td height="24px">文件名称：</td>
					<td><input type="text" id="fileName" name="fileName" value="123" />
					</td>
				</tr>
				
				<tr>
					<td height="24px">批次号：</td>
					<td><input type="text" id="batchNo" name="batchNo" value="16101099000001411300" />
					</td>
				</tr>
				
				<tr>
					<td height="24px">业务类型：</td>
					<td><input type="text" id="busyType" name="busyType" value="0" />
					</td>
				</tr>

				<tr>
					<td height="24px">回盘通知地址：</td>
					<td><input type="text" id="notifyUrl" name="notifyUrl" value="http://localhost:8080/pgw-pay-test/Counteroffer/doPay" />
					</td>
				</tr>
			 	
				<tr>
					<td height="30px" colspan="2" align="center" width="100px"><input type="submit" id="form_submit" value="提  交" /></td>
				</tr>
				
			</table>
		</form>
		
	</div>
</body>

</html>