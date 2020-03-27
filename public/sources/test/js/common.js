(function (win, $) {
  win.tools = {
    //接口地址
    // http://jtyqinghuaqianzhuang.com/jiedai
    // url: "http://libo.test/api/v1",
    url: "https://admin.szyingfang.com/api/v1",
    // 验证手机号
    isPhoneNo: function (phone) {
      var pattern = /^1[3456789]\d{9}$/;
      return pattern.test(phone);
    },
    /* 获取URL地址参数
     * prop:参数名
     */
    getUrlParams: function (prop) {
      var params = {},
        query = location.search.substring(1),
        arr = query.split('&'),
        rt;

      $.each(arr, function (i, item) {
        var tmp = item.split('='),
          key = tmp[0],
          val = tmp[1];

        if (typeof params[key] == 'undefined') {
          params[key] = val;
        } else if (typeof params[key] == 'string') {
          params[key] = [params[key], val];
        } else {
          params[key].push(val);
        }
      });
      rt = prop ? params[prop] : params;
      return rt;
    }
  }
}(this, jQuery));
