(function(win, $) {
	var $infoList = $(".infoList"),
		scrollTpl = $("#scroll-tpl").html(),
		M = Mustache;

	// 滚动数据
	var scollContent = [
		'湖南长沙李**, 成功借款40000元',
		'江苏南京赖**, 成功借款50000元',
		'上海奉贤蔡**, 成功借款30000元',
		'浙江杭州王**, 成功借款40000元',
		'山西太原李**, 成功借款50000元',
		'辽宁大连叶**, 成功借款40000元',
		'吉林通化蒋**, 成功借款20000元',
		'安徽合肥周**, 成功借款30000元',
		'福建福州刘**, 成功借款40000元',
		'山东济南高**, 成功借款50000元'
	];
	//滚动
	function renderSlider() {
		$infoList.empty().append(M.render(scrollTpl, {
			item: scollContent
		}));
		//计算高度
		recalc = function() {
			var ht,
				clientWidth = document.body.clientWidth;
			if (!clientWidth) return;
			if (clientWidth >= 750) {
				ht = '60px';
			} else {

				ht = 60 * (clientWidth / 750)+"px";
			}
			return ht;
		};
		var ht = recalc();
		$(".slider").css({
			"height":ht,
			"line-height":ht
		});
		$("#slider").slide({
			mainCell: "#slder-con",
			effect: "topLoop",
			autoPlay: true,
			vis: 1
		});
	}

	renderSlider();

}(this, jQuery));
