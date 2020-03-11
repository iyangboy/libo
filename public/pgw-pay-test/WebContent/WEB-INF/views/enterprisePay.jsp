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
	
		<form method="post" id="transForm" name="transForm" action="${pageContext.request.contextPath}/enterprise/pay">
			<table>
			
				<tr>
					<td height="24px">测试地址:</td>
					<td>
					 	<input type="radio" id="url_OUT" name="url"
							value="https://pay.zhengtongcf.com/pgw-pay/enterprisepay" />外网测试
					</td>
				</tr>
			
				<tr>
					<td height="24px">version:</td>
					<td><input type="text" id="version" name="version" value="1.0.1" /></td>
				</tr>
			
				<tr>
					<td height="24px">商户编号:</td>
					<td>
						<input type="text" id="merchantId" name="merchantId" value=""></input>
					</td>
				</tr>
				
				<tr>
					<td height="24px">商户订单编号:</td>
					<td><input value="<%= new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) %>" 
						id="orderId" name="merOrderId" onfocus="WdatePicker({dateFmt:'yyyyMMddHHmmss'})" />
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
					<td><input type="text" id="amount" name="amount" value="38.9" /></td>
				</tr>
				
				<tr>
					<td height="24px">公司名:</td>
					<td><input type="text" id="name" name="name" value="证通股份有限公司" /></td>
				</tr>
				
				<tr>
					<td height="24px">机构号:</td>
					<td><input type="text" id="organCode" name="organCode" value="4000100005" /></td>
				</tr>
				
				<tr>
					<td height="24px">账号:</td>
					<td><input type="text" id="account" name="account" value="6215581508005002640" /></td>
				</tr>
				
				<tr>
					<td height="24px">账户类型:</td>
					<td><input type="text" id="accountType" name="accountType" value="96" /></td>
				</tr>
				
				<tr>
					<td height="24px">证件号码:</td>
					<td><input type="text" id="idCode" name="idCode" value="" /></td>
				</tr>
			
				<tr>
					<td height="24px">证件类型:</td>
					<td>
						<select id="idType" name="idType">
						  	<option value="" selected="selected">--请选择--</option>
						  	<option value="10">身份证</option>
						  	<option value="11">护照</option>
						  	<option value="12">军官证</option>
						</select>
					</td>
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
					<td height="24px">收款方联行行号:</td>
					<td><input type="text" id="receiveAccount" name="receiveAccount" value="307584021291" /></td>
				</tr>
				
				<tr>
					<td height="24px">收款方开户行省份:</td>
					<td><input type="text" id="receiveProvince" name="receiveProvince" value="" /></td>
				</tr>
				
				<tr>
					<td height="24px">收款方开户行城市:</td>
					<td><input type="text" id="receiveCity" name="receiveCity" value="" /></td>
				</tr>
				
				<tr>
					<td height="30px" colspan="2" align="center" width="100px"><input type="submit" id="form_subxmit" value="提  交" /></td>
				</tr>
				
			</table>
		</form>
		
	</div>
</body>

</html>