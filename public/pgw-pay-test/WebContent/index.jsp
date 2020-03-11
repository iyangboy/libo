<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<%@ page language="java" pageEncoding="UTF-8" contentType="text/html; charset=utf-8"%>

<head>
<title>支付网关测试程序</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
	<a href="${pageContext.request.contextPath}/bindCard/bindCardPage">签约</a><br>
	<a href="${pageContext.request.contextPath}/SignOut/dendo">解约</a><br>
	<a href="${pageContext.request.contextPath}/payment/paymentPage">消费</a><br>
	<a href="${pageContext.request.contextPath}/payRefund/refundPage">退款（撤销）</a><br>
	<a href="${pageContext.request.contextPath}/proxyPay/proxyPayPage">代付</a><br>
	<a href="${pageContext.request.contextPath}/queryPayOrder/queryPayOrderPage">订单查询</a><br>
    <a href="${pageContext.request.contextPath}/batchNotify/dendo">批量代收付送盘</a><br>
    <a href="${pageContext.request.contextPath}/querybatch/dendo">批量代收付查询状态</a><br>
    <br>
    <a href="${pageContext.request.contextPath}/quickPay/quickPayPage">快捷支付</a><br>
    <a href="${pageContext.request.contextPath}/quickPayValidate/quickPayValidatePage">快捷支付短信验证</a><br>
    <a href="${pageContext.request.contextPath}/quickPay/quickPayRefundPage">快捷支付退款</a><br>
    <a href="${pageContext.request.contextPath}/quickPay/identifyAuthPage">快捷支付身份认证</a><br>
    <a href="${pageContext.request.contextPath}/quickPay/signPage">快捷支付签约</a><br>
    <a href="${pageContext.request.contextPath}/quickPay/signCancelPage">快捷支付商户端解约</a><br>
    <a href="${pageContext.request.contextPath}/quickPay/tradeStatusPage">快捷支付交易状态查询</a><br>
        <br>
    <a href="${pageContext.request.contextPath}/authpaysms/authpaySmsPage">认证支付短信申请</a><br>
    <a href="${pageContext.request.contextPath}/authpay/authpayPage">认证支付</a><br>
    <a href="${pageContext.request.contextPath}/authpay/authpayRefundPage">认证支付退款</a><br>
    <br>
    <a href="${pageContext.request.contextPath}/enterprise/page">对公代付</a><br>
</body>

</html>