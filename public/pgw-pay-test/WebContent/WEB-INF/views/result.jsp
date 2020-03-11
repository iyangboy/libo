<!DOCTYPE html>
<%@page import="org.apache.commons.lang3.time.DateUtils"%>
<%@page import="java.util.Date"%>
<%@page import="java.net.InetAddress"%>
<%@page import="java.text.SimpleDateFormat"%>
<html xmlns="http://www.w3.org/1999/xhtml">
<%@ page language="java" pageEncoding="UTF-8"
	contentType="text/html;charset=utf-8"%>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
<head>
<title>展示返回结果</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="${pageContext.request.contextPath}/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" language="javascript">

</script>
</head>

<body>
${responseString}	
<br>
<br>
展示返回结果:<br>
	<table  width="100%" border="1" cellspacing="0" cellpadding="0" class="tb table_01" bordercolor="green">
		<c:forEach var="item" items="${resultMap}">
			<tr>
				<td> ${item.key}:</td>
				<td> ${item.value}</td>
			</tr>
		</c:forEach>
	</table>
	
	<br><hr>
		
		<c:if test="${resultMap == null}">
			<c:out value="${responseString}" escapeXml="true"/>
		</c:if>
	
</body>
</html>