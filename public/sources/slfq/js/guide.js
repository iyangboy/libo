(function(win, $) {
  // 石榴分期
	var product_name = "slfq",
		download = {
			iphoneDownload: "https://fir.im/l49m",
			androidDownload: "https://fir.im/ceyq",
		},
		into_from,
		lay,
		flag = 1,
		liboolean = true,
		$item = $(".items"),
		$li = $("li", $item),
		$content = $("#content"),
		mobileType = checkMobile(),
		channel = tools.getUrlParams("channel"),
		litpl = $("#list-tpl").html(),
		downloadtpl = $("#download-tpl").html(),
		laytpl = $("#layer-tpl").html(),
		laytpl2 = $("#layer-msg").html(),
		M = Mustache;

	//检验手机型号
	function checkMobile() {
		var u = navigator.userAgent;
		if (u.indexOf("Android") > -1 || u.indexOf("Linux") > -1) {
			return 'android';
		} else if (u.indexOf("iPhone") > -1) {
			return 'iphone';
		} else if (u.indexOf("Windows Phone") > -1) {
			return 'wp';
		}
	}

	//处理点击空白小键盘不回收
	function fixedInputBlur() {
		var beforeIpt = null;
		$("input").on("focus", function(e) {
			beforeIpt = this;
		});
		$("div").on("click", function(e) {
			if (e.target.nodeName.toLowerCase() != "input") {
				if (beforeIpt) {
					beforeIpt.blur();
					beforeIpt = null;
				}
				// a标签也能获取焦点，但是用代码模拟点击事件却不能触发键盘隐藏
			}
		});
	}
	fixedInputBlur();

	if (mobileType != "iphone") {
		//解决华为手机软键盘弹不出问题
		window.addEventListener("resize", function() {
			if (document.activeElement.tagName == "INPUT" || document.activeElement.tagName == "TEXTAREA") {
				window.setTimeout(function() {
					document.activeElement.scrollIntoViewIfNeeded();
				}, 0);
			}
		})
	}

	if (mobileType == "android") {
		into_from = 1;
	} else if (mobileType == "iphone") {
		into_from = 2;
	} else {
		into_from = 3;
	}

	//按钮点击
	$(".apply").on("click", function() {
		if (flag) {
			flag = 0;
			var val = $("#phone").val();

			if (tools.isPhoneNo(val)) {
				if (liboolean) {
					var json = '{"mobile":"' + val +
						'","product_type":"slfq","device_id":"201901152257010339f4291d83b95897ec1738c174a81501072d3cac11bfaa","form_token":"265675A8775FC0F2E819526BAA5B096D","platform":"5","flag":"MSG_REG_AND_LOGIN_","version":"1.0","juid":"3c5c6ffe31564f84b1a47e24f5c6f187","login_token":"310007d6f4854848a3f75b2a98814e34","product_type":"' +
						product_name + '"}';
					$.ajax({
						type: "post",
						url: tools.url + "/zy/zyUser/sendsms",
						data: {
							record: json
						},
						dataType: "json",
						beforeSend: function() {
							lay = layer.open({
								type: 2,
								content: '加载中',
								shadeClose: false
							});
						},
						success: function(data) {
							layer.close(lay);
							if (data.code == "0000" && data.result.status == 1) {
								flag = 1;
								layer.open({
									content: laytpl2,
									shadeClose: false,
									success: function(elem) {
										$(".layer-con").addClass("success");
										$("#laymsg").html('获取验证码成功');
										$(".layer-goto").on('click', function() {
											layer.closeAll();
											$item.append(litpl);
											liboolean = false;
										})
									}
								});

							} else {
								layer.open({
									content: laytpl2,
									shadeClose: false,
									success: function(elem) {
										$(".layer-con").addClass("code");
										$("#laymsg").html(data.msg);
										$(".layer-goto").on('click', function() {
											layer.closeAll();
										})
									}
								});
							}
						}
					});
				} else {
					var codeVal = $("#code").val();
					if (codeVal) {
						// 调用接口
						var json = '{"mobile":"' + val + '","product_type":"slfq","password":" ","userChannel":"' + channel +
							'","device_id":"201901152257010339f4291d83b95897ec1738c174a81501072d3cac11bfaa","form_token":"265675A8775FC0F2E819526BAA5B096D","platform":"5","into_device":"265675A8775FC0F2E819526BAA5B096D","verify_code":"' +
							codeVal + '","version":"1.0","into_from":"' + into_from +
							'","juid":"3c5c6ffe31564f84b1a47e24f5c6f187","login_token":"310007d6f4854848a3f75b2a98814e34","product_type":"' +
							product_name + '","flag":"MSG_LOGIN_","login_flag":"2"}';
						$.ajax({
							type: "post",
							url: tools.url + "/zy/zyUser/registerAndLogin",
							data: {
								record: json
							},
							dataType: "json",
							beforeSend: function() {
								lay = layer.open({
									type: 2,
									content: '加载中',
									shadeClose: false
								});
							},
							success: function(data) {
								flag = 1;
								layer.close(lay);
								if (data && data != "" && data.code == "0000") {
									layer.open({
										content: laytpl2,
										shadeClose: false,
										success: function(elem) {
											$(".layer-con").addClass("success");
											$("#laymsg").html('注册成功');
											$(".layer-goto").on('click', function() {
												layer.closeAll();
												$content.empty().append(M.render(downloadtpl, download));
                        if (mobileType == "iphone") {
                          window.location.href = download.iphoneDownload;
                        } else {
                          window.location.href = download.androidDownload;
                        }
											})
										}
									});
								} else {
									layer.open({
										content: laytpl2,
										shadeClose: false,
										success: function(elem) {
											$(".layer-con").addClass("code");
											$("#laymsg").html(data.msg);
											$(".layer-goto").on('click', function() {
												layer.closeAll();
											})
										}
									});
								}

							},
							error: function() {
								flag = 1;
								layer.close(lay);
								layer.open({
									type: 2,
									content: '请求超时，请刷新',
									time: 3
								});
							}
						});
					} else {
						flag = 1;
						layer.open({
							content: laytpl2,
							shadeClose: false,
							success: function(elem) {
								$(".layer-con").addClass("code");
								$("#laymsg").html('请输入验证码');
								$(".layer-goto").on('click', function() {
									layer.closeAll();
								})
							}
						});
					}

				}
			} else {
				flag = 1;
				layer.open({
					content: laytpl2,
					shadeClose: false,
					success: function(elem) {
						$(".layer-con").addClass("phone");
						$("#laymsg").html('手机号不正确');
						$(".layer-goto").on('click', function() {
							layer.closeAll();
						})
					}
				});
			}
		} else {
			flag = 1;
		}
	});

	$(".link").on("click", function() {
		if (mobileType == "iphone") {
			window.location.href = download.iphoneDownload;
		} else {
			window.location.href = download.androidDownload;
		}
	});

}(this, jQuery));
