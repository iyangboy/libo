<!DOCTYPE html>

<%@page import="org.apache.commons.lang3.time.DateUtils"%>
<%@page import="java.util.Date"%>
<%@page import="java.net.InetAddress"%>
<%@page import="java.text.SimpleDateFormat"%>
<%@ page language="java" pageEncoding="UTF-8" contentType="text/html; charset=utf-8"%>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c"%>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>商户模拟绑卡交易</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="${pageContext.request.contextPath}/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript"></script>
</head>

<body>
	<div>
		<form method="post" id="transForm" name="transForm" action="/pgw-pay-test/bindCard/bindCard">
			<table>
			
				<tr>
					<td height="24px">测试地址：</td>
					<td>
						<input type="radio" id="url_UAT" name="url" checked="checked"
							value="https://112.65.144.19:9204/pgw-pay/sign" />外网测试
					</td>
				</tr>
	
				<tr>
					<td height="24px">version：</td>
					<td><input type="text" id="version" name="version" value="1.0.1" /></td>
				</tr>
				
				<tr>
					<td height="24px">商户编号：</td>
					<td>
						<input type="text" id="merchantId" name="merchantId" value="000010357"></input>
					</td>
				</tr>

				<tr>
					<td height="24px">证通内部机构号：</td>
					<td><input type="text" id="organCode" name="organCode" value="4001800003" /></td>
				</tr>

				<tr>
					<td height="24px">机构名：</td>
					<td><input type="text" id="organName" name="organName" value="恒丰银行" /></td>
				</tr>
				
			 	<tr>
					<td height="24px">请求时间：</td>
					<td>
						<input type="text" style="width: 130px;" value="<%= new SimpleDateFormat("yyyyMMdd HH:mm:ss").format(new Date()) %>" 
							id="reqDate" name="reqDate" onfocus="WdatePicker({dateFmt:'yyyyMMdd HH:mm:ss'})" />
					</td>
				</tr>

				<tr>
					<td height="24px">开户人姓名：</td>
					<td><input type="text" id="name" name="name" value="李亚琴" /></td>
				</tr>

				<tr>
					<td height="24px">账号：</td>
					<td><input type="text" id="account" name="account" value="6221881811022564132" />
					</td>
				</tr>

				<tr>
					<td height="24px">账户类型：</td>
					<td>
						<select id="accountType" name="accountType" >
						  	<option value="90" selected="selected">储蓄卡</option>
						  	<option value="91">信用卡</option>
						  	<option value="92">预付费卡</option>
						  	<option value="01">资金账户</option>
						  	<option value="02">信用资金账户</option>
						  	<option value="03">衍生品账户</option>
						  	<option value="04">理财账户</option>
						  	<option value="20">基金账户</option>
						  	<option value="30">期货保证金</option>
						  	<option value="99">其它</option>
						</select>
					</td>
				</tr>
			
				<tr>
					<td height="24px">证件类型：</td>
					<td>
						<select id="idType" name="idType">
						  <option value="10" selected="selected">身份证</option>
						</select>
					</td>
											
				</tr>

				<tr>
					<td height="24px">开户人证件号：</td>
					<td><input type="text" id="idCode" name="idCode" value="142727199008030020" /></td>
				</tr>
				
				<tr>
					<td height="24px">开户绑定手机号：</td>
					<td><input type="text" id="mobile" name="mobile" value="13028011546" />
					</td>
				</tr>

			 	<tr>
					<td height="24px">cvv2(信用卡背后3位)：</td>
					<td><input type="text" id="cvv2" name="cvv2" value="" /></td>
				</tr>
				
				<tr>
					<td height="24px">信用卡有效期(YYMM)：</td>
					<td><input type="text" id="validDate" name="validDate" value="" /></td>
				</tr>
				<!-- <tr>
					<td height="24px">验证类型：</td>
					<td>
					<select id="checkType" name="checkType">
						  <option value="" selected="selected">请选择</option>
						  <option value="1">账户验证</option>
						  <option value="2">短信发送</option>
						  <option value="3">绑卡签约</option>
						  <option value="4">代收委托</option>
						  <option value="5">第三方绑定</option>
						  <option value="6">解约</option>
					</select>
					<span style="color: red">*1：账户验证2：短信发送3：绑卡签约4：代收委托5：第三方绑定6：解约</span></td>
				</tr>
				<tr>
					<td height="24px">短信发送编号：</td>
					<td><input type="text" id="smsSendNo" name="smsSendNo" value="" />
					<span style="color: red">*验证类型为2时填写，验证类型为3时填写原2时上送的编号</span></td>
				</tr>
				<tr>
					<td height="24px">短信验证码：</td>
					<td><input type="text" id="smsVerifyCode" name="smsVerifyCode" value="" />
					<span style="color: red">*验证类型为3时填写</span></td>
				</tr> -->
			 
				<tr>
					<td height="30px" colspan="2" align="center" width="100px"><input type="submit"  id="form_submit" value="提  交" /></td>
				</tr>
				
			</table>
		</form>
		
	</div>
</body>

</html>