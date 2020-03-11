<!DOCTYPE html>

<%@ page language="java" pageEncoding="UTF-8" contentType="text/html;charset=utf-8"%>
<%@page import="org.apache.commons.lang3.time.DateUtils"%>
<%@page import="java.util.Date"%>
<%@page import="java.net.InetAddress"%>
<%@page import="java.text.SimpleDateFormat"%>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>商户模拟代付交易</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="${pageContext.request.contextPath}/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript"></script>
</head>

<body>
	<div>
	
		<form method="post" id="transForm" name="transForm" action="${pageContext.request.contextPath}/proxyPay/proxyPay">
			<table>
			
				<tr>
					<td height="24px">测试地址:</td>
					<td>
						<input type="radio" id="url_UAT" name="url" checked="checked"
							value="https://112.65.144.19:9204/pgw-pay/charge" />外网测试
							&nbsp;&nbsp;
					</td>
				</tr>
			
				<tr>
					<td height="24px">version:</td>
					<td><input type="text" id="version" name="version" value="1.0.1" /></td>
				</tr>
			
				<tr>
					<td height="24px">商户编号:</td>
					<td>
						<input type="text" id="merchantId" name="merchantId" value="000010357"></input>
					</td>
				</tr>
				
				<tr>
					<td height="24px">商户订单编号:</td>
					<td><input value="<%= new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) %>" 
						id="orderId" name="orderId" onfocus="WdatePicker({dateFmt:'yyyyMMddHHmmss'})" />
					</td>
				</tr>
				
				<tr>
					<td height="24px">请求时间:</td>
					<td>
						<input type="text" style="width: 130px;" value="<%= new SimpleDateFormat("yyyyMMdd HH:mm:ss").format(new Date()) %>" 
							id="reqDate" name="reqDate" onfocus="WdatePicker({dateFmt:'yyyyMMdd HH:mm:ss'})" />
					</td>
				</tr>
				
				<tr>
					<td height="24px">订单支付金额:</td>
					<td><input type="text" id="amount" name="amount" value="66.66" /></td>
				</tr>
				
				<tr>
					<td height="24px">姓名:</td>
					<td><input type="text" id="name" name="name" value="李亚琴" /></td>
				</tr>
				
				<tr>
					<td height="24px">机构号:</td>
					<td><input type="text" id="organCode" name="organCode" value="4001800003" /></td>
				</tr>
				
				<tr>
					<td height="24px">账号:</td>
					<td><input type="text" id="account" name="account" value="6221881811022564132" /></td>
				</tr>
				
				<tr>
					<td height="24px">账户类型:</td>
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
					<td height="24px">证件号码:</td>
					<td><input type="text" id="idCode" name="idCode" value="142727199008030020" /></td>
				</tr>
			
				<tr>
					<td height="24px">证件类型:</td>
					<td>
						<select id="idType" name="idType">
						  	<option value="10" selected="selected">身份证</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td height="24px">二级商户编号:</td>
					<td><input type="text" id="subMercId" name="subMercId" value="" /></td>
				</tr>

				<tr>
					<td height="24px">二级商户简称:</td>
					<td><input type="text" id="subMercName" name="subMercName" value="" /></td>
				</tr>

				<tr>
					<td height="24px">网联业务类型:</td>
					<td>
						<select id="orderBizzType" name="orderBizzType">
							<option value="" selected="selected"></option>
							<option value="100001">实物商品租购</option>
							<option value="100002">虚拟商品购买</option>
							<option value="100003">预付类账号充值</option>
							<option value="100004">航旅服务订购</option>
							<option value="100005">活动票务订购</option>
							<option value="100006">商业服务消费</option>
							<option value="100007">生活服务消费</option>
							<option value="100099">其他商家消费</option>
							<option value="110001">水电煤缴费</option>
							<option value="110002">税费缴纳</option>
							<option value="110003">学校教育缴费</option>
							<option value="110004">医疗缴费</option>
							<option value="110005">罚款缴纳</option>
							<option value="110006">路桥通行缴费</option>
							<option value="110007">邮政缴费</option>
							<option value="110008">电视账单缴费</option>
							<option value="110009">话费单缴费</option>
							<option value="110010">宽带账单缴费</option>
							<option value="110011">公益捐款</option>
							<option value="110099">其他公共服务</option>
							<option value="120001">基金申购</option>
							<option value="120002">基金认购</option>
							<option value="120003">保险购买</option>
							<option value="120004">信贷偿还</option>
							<option value="120005">商业众筹</option>
							<option value="120006">贵金属投资买入</option>
							<option value="120099">其他互联网金融</option>
							<option value="130001">支付账户充值</option>
							<option value="130002">充值至他人支付账户</option>
						</select>
					</td>
				</tr>
				
				<tr>
					<td height="30px" colspan="2" align="center" width="100px"><input type="submit" id="form_subxmit" value="提  交" /></td>
				</tr>
				
			</table>
		</form>
		
	</div>
</body>

</html>