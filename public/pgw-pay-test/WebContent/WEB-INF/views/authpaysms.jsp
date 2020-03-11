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


</script>
</head>

<body>
	<div>
	
		<form method="post" id="transForm" name="transForm" action="${pageContext.request.contextPath}/authpaysms/doPay">
			<table>
				<tr>
					<td height="24px">测试地址:</td>
					<td>
						<input type="radio" id="url_OUT" name="url"
							value="https://pay.zhengtongcf.com/pgw-quickpay/authpay/auth" />外网测试
					</td>
				</tr>

				<tr>
					<td height="24px">版本号:</td>
					<td><input type="text" id="version" name="version" value="1.0.1" /></td>
				</tr>
				
				<tr>
					<td height="24px">商户编号:</td>
					<td>
						<input type="text" id="merchantId" name="merchantId" value="000010414"></input>
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
					<td><input value="<%= new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()) %>" id="merOrderId" name="merOrderId" onfocus="WdatePicker({dateFmt:'yyyyMMddHHmmss'})" /></td>
				</tr>
				
				<tr>
					<td height="24px">订单支付金额:</td>
					<td><input type="text" id="amount" name="amount" value="10.68" /></td>
				</tr>
				

				<tr>
					<td height="24px">姓名:</td>
					<td><input type="text" id="name" name="name" value="雷一" /></td>
				</tr>
				
				<tr>
					<td height="24px">账号:</td>
					<td><input type="text" id="account" name="account" value="6226720123456789"/></td>
				</tr>
				
				<tr>
					<td height="24px">账户类型:</td>
					<td>
						<input type="text" id="accountType" name="accountType" value="90"/>
						<!-- 
						<select id="accountType" name="accountType">
							<option value="90" selected="selected">储蓄卡(借记卡)</option>
							<option value="91">信用卡(贷记卡)</option>
						</select>
						 -->
					</td>
				</tr>
				
				<tr>
					<td height="24px">证件类型:</td>
					<td>
						<input type="text" id="idType" name="idType" value="10"/>
						<!-- 
						<select id="idType" name="idType">
							<option value="10" selected="selected">身份证</option>
						</select>
						 -->
					</td>
				</tr>
				
				<tr>
					<td height="24px">证件号:</td>
					<td><input type="text" id="idCode" name="idCode" value="370100199507230172"/></td>
				</tr>
				
				<tr>
					<td height="24px">机构号:</td>
					 <td>
					 	<input type="text" id="organCode" name="organCode" value="4000800002"/>
					 	<!-- 
					 	<select id="organCode" name="organCode">
					 		<option value="4000100005">4000100005 中国工商银行</option>
							<option value="4000200006">4000200006 中国农业银行</option>
							<option value="4000300007">4000300007 中国银行</option>
							<option value="4000400008">4000400008 中国建设银行</option>
							<option value="4000500009">4000500009 邮储银行</option>
							<option value="4000600000">4000600000 交通银行</option>
							<option value="4000700001">4000700001 中信银行</option>
							<option value="4000800002" selected="selected">4000800002 光大银行</option>
							<option value="4000900003">4000900003 华夏银行</option>
							<option value="4001000005">4001000005 民生银行</option>
							<option value="4001100006">4001100006 广发银行</option>
							<option value="4001200007">4001200007 招商银行</option>
							<option value="4001300008">4001300008 兴业银行</option>
							<option value="4001400009">4001400009 浦发银行</option>
							<option value="4001500000">4001500000 平安银行</option>
							<option value="4001600001">4001600001 上海银行</option>
							<option value="4001800003">4001800003 恒丰银行</option>
							<option value="4001900004">4001900004 浙商银行</option>
					 	</select>
					 	 -->
					 </td>
				</tr>
				
				<tr>
					<td height="24px">手机号:</td>
					<td><input type="text" id="mobile" name="mobile" value="13755286400" /></td>
				</tr>
				
				<tr>
					<td height="24px">cvv2:</td>
					<td><input type="text" id="cvv2" name="cvv2" value="" /></td>
				</tr>
				
				<tr>
					<td height="24px">有效期:</td>
					<td><input type="text" id="validDate" name="validDate" value="" /></td>
				</tr>
				
				<tr>
					<td height="24px">支付场景:</td>
					<td>
						<input type="text" id="payType" name="payType" value="100001" />
						<!-- 
						<select id="payType" name="payType">
							 	<option value="100001" selected="selected">实物商品租购</option>
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
								<option value="120007">基金赎回</option>
								<option value="120008">基金到期返还</option>
								<option value="120009">认/申购失败返还</option>
								<option value="120010">基金分红</option>
								<option value="120011">保险理赔</option>
								<option value="120012">保险红利发放</option>
								<option value="120013">贵金属投资卖出</option>
								<option value="120014">信贷发放</option>
								<option value="120099">其他互联网金融</option>
								<option value="130001">支付账户充值</option>
								<option value="130002">充值至他人支付账户</option>
								<option value="130003">支付账户回提</option>
								<option value="130004">回提至他人银行账户</option>
								<option value="140001">交易资金结算</option>
								<option value="140002">其他商户结算</option>
						</select>
						 -->
					</td>
				</tr>
				<tr>
					<td height="24px">订单详情:</td>
					<td>
						<input type="text" style="width: 400px" id="orderDesc" name="orderDesc" value="2#2#商品1简称^100.00^1#商品2简称^50.00^20"/>
					</td>
				</tr>
				
				<tr>
					<td height="24px">二级商户编号:</td>
					<td><input type="text" id="subMercId" name="subMercId" value="" /></td>
				</tr>

				<tr>
					<td height="30px" colspan="2" align="center" width="100px"><input type="submit" id="form_subxmit" value="提  交" /></td>
				</tr>
				
			</table>
		</form>
		
	</div>
</body>

</html>