<?php
/**
*免责声明

*1、本DEMO仅供技术接入参考，不建议直接用至生产。

*2、因使用本DEMO而引致的任何意外或者任何损失，概不负责，亦概不承担任何民事或刑事法律责任。

*3、当你第一次开始使用DEMO那一刻起就将被视为对本声明全部内容的认可。同时您必须认可上述免责条款，方可使用。如有任何异议，建议立刻删除并且停止使用.

*以上内容，讯联保留最终解释权。
**/

namespace xunlianPay;

$url = 'https://www.tongtongcf.com:9204/pgw-quickpay/quickpay/identifyauth';//demo接口-签约发短信
$param = [
    'version'=>'1.0.1',                   //版本号
    'merchantId'=>'000010461',            //商户号 
    'merOrderId'=>'20190710150911$1234',  //商户订单号
    'organCode'=>'4001200007',            //银行机构号
    'organName'=>'招商银行',                 //银行名称
    'reqDate'=>'20190717 16:46:35',       //请求时间
    'name'=>'黄xx',                        //姓名
    'account'=>'6225885545565568',        //银行账号
    'accountType'=>'90',                  //账号类型
    'idType'=>'10',                       //证件类型
    'idCode'=>'110101199003071735',       //证件号
    'mobile'=>'13786838558',              //手机号
    //加密后的CVV2,用sdk.rsa_encryption加密
    'cvv2'=>'Pxjt/PZYV8W/E016oJzWMnPjlgekdOCeQ0ml3dR4B/gEAf2eCDEACmYPrzs4VbC4J/Nd2nWFp62ERXcMwgQOQUM6AK7Idwd6na7lA2TO9ZVU7rUYvw6m1sWdKo2zo/V1T7oX88zkORtHzeR0I8D8oWVGU34+EqIsVQs99N6Msp8=',
    //加密后的有效期,用sdk.rsa_encryption加密
    'validDate'=>'UO1kLEhOzpGZaVR9uzzCP7EjCh+22EhGck7i/izZ27mU7PW1CXZ25+/N6V9P32mEwcCv4BzoxRtnHhLrUmgGMgmrIXbADyg7h/fQ6hlkusa00m9V8X1OONwE8MEkWcydiLFhbaPUtVy+oCcaPuo2lPZwKGY6v0hQmJNKi6uVA+M='
];
$cls = new sdk();
$cls->get_result($url, $param);

class sdk{
    /**
     *获取请求结果
     * 
     */
    function get_result($url, $param){
        $sign_str = $this->get_sign($param);
        $append_xml = '';
        foreach ($param as $k => $v) $append_xml .= "<{$k}>{$v}</{$k}>";
        $post_data = <<<EOF
<EctData>
    <Message id="201510280008881">
        <CSReq id="IAReq ">
		    {$append_xml}
		    <sign>{$sign_str}</sign>
		</CSReq>
    </Message>
</EctData>
EOF;
		            
		$result = $this->curl_post_https($url, $post_data);
		print_r("request xml as follows: \n");
		print_r($post_data);
		print("\n");
		//为了打印中文正常才增加增加转换
		$result_mb=mb_convert_encoding($result, 'GBK', 'UTF-8,GBK,GB2312,BIG5');
		print_r("response xml as follows: \n");
		print_r($result_mb);
		print("\n");

		//验签
		$xml = simplexml_load_string($result);
		$parm = array();
		$sign = null;
		foreach($xml->children()->children()->children() as $child)
		{
		    if(empty($child) == false && $child->getName() !== "sign") {
		        $parm[$child->getName()] = $child->__toString();
		    }
		    if($child->getName() === "sign") {
		        $sign = $child->__toString();
		    }
		}
		
		$verify_result = $this->verify_sign($parm, $sign);
		if($verify_result === 1) {
		    print("===========verify sign success ===========\n");
		} else {
		    print("===========verify fail ===========\n");
		}

    }
    
    /**
     * 获取签名
     * $signarr 签名的原文
     *  
     *数字签名和验签要求 ---详见接口文档数字签名章节
     *所有交易的报文格式中提及的字段，除sign字段外，其余字段只要商户上送，均需要进行签名，但参数值如果为null或者空字符串的除外。用于加签的明文数据，按照上送字段key的字母顺序排列后，以key=value格式用&号连接，最后一个字段后不加&号。
     *商户入网时，讯联智付运营人员会在内部管理平台注册商户，提交两码至自服务平台。商户运营人员可以登录讯联智付商户自服务平台获取两码，然后自行到CFCA官网根据两码下载证书。下载证书后，需将公钥证书导出并通过讯联智付商户自服务平台上传至讯联智付服务器。
     *加签/验签采用SHA1WithRSA算法。
     * 
     */
    function get_sign($signarr){
        //按照key排序
        ksort($signarr);
        $query_str = http_build_query($signarr);
        $query_str = urldecode($query_str);
        
        print("get sign string as follows \n");
        print($query_str);
        print("\n");
        $pfxpath = dirname(__FILE__).'/merchant.pfx';//商户私钥,请根据实际私钥替换
        if (!file_exists($pfxpath)) die('pfx not exists');
        $pfx_pwd = '123';//测试默认私钥密码
        
        $encryptUtil = new EncryptUtil($pfxpath, null, $pfx_pwd, true); //实例化加密类。
        return $encryptUtil->getsign($query_str);
    }
    
    /**
     * 验证签名
     * $data 需要验签的字符串
     * $sign 讯联返回报文中的签名串
     * 返回验签结果 1为成功   0位失败    -1为异常
     */
    function verify_sign($data, $sign){
        //按照key排序
        ksort($data);
        $query_str = http_build_query($data);
        $query_str = urldecode($query_str);
        
        print("verify sign string as follows \n");
        print($query_str);
        print("\n");
        
        $public_key_path = dirname(__FILE__).'/xl_pub_key.cer';//xunlian公钥
        if (!file_exists($public_key_path)) die('public key not exists');
        
        $encryptUtil = new EncryptUtil(null, $public_key_path, null, true); //实例化加密类。
        return $encryptUtil->verifysign($query_str, $sign, $public_key_path);
    }
    
    /**
     * rsa 敏感信息rsa公钥加密
     * str 明文，cvv2 validDate
     * 返回 string 密文
     * 
     * 详见接口文档敏感数据加密传输要求章节
     * 加密采用RSA算法。明文加密后需要将密文encode成Base64格式。加签字段的value值应采用加密后的密文而非加密前的明文进行签名。
     */
    function rsa_encryption($str)
    {
        //公钥
        $public_key_str = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCme526ar35LeqRQaWzFvAd2NTKPmHdNfizeubSO3l+bkSrLT/hB3ZS46FGCK1r4SM6/Ka19ej2VP8oWrOrBUzk10pUBuvcBu7c2raVTs8oPM9O/notC460TPO9Bg8247lborjZGm9Fv0nlHhN0RYoUYUVuzvQkbBtpCqTO8sNeewIDAQAB';
        $key = wordwrap($public_key_str,64,"\n",true)."\n";
        $pem_key = "-----BEGIN PUBLIC KEY-----\n" . $key . "-----END PUBLIC KEY-----\n";
        $pu_key_pem = openssl_pkey_get_public($pem_key);
        $encrypt = '';
        openssl_public_encrypt($str,$encrypt,$pu_key_pem,OPENSSL_PKCS1_PADDING);
        $encrypt = base64_encode($encrypt);
        return $encrypt;
    }
    
    /**
     * curl https get
     */
    function curl_get_https($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // 跳过检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 跳过检查
        $tmpInfo = curl_exec($curl);
        curl_close($curl);
        return $tmpInfo;   //返回json对象
    }
    
    /**
     * curl https post
     */
    function curl_post_https($url, $post='')
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
//         curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $res; // 返回数据，json格式
    }
    
}

/**
 * 工具类
 */
class EncryptUtil
{
    private $private_key;
    private $public_key;
    function __construct($private_key_path, $public_key_path, $private_key_password='123', $Debug = FALSE)
    {
        if (!$Debug) ob_start();
        // 初始化商户私钥
        if($private_key_path !== null) {
            $pkcs12 = file_get_contents($private_key_path);
            $private_key = [];
            openssl_pkcs12_read($pkcs12, $private_key, $private_key_password);
            $this->private_key = $private_key["pkey"];
        }
        if (!$Debug) ob_end_clean();
    }
    
    /**
     * 获取签名
     * $toSign 用来签名的字符串
     * 返回签名字符串
     */
    function getsign($toSign){
        $privateKey = wordwrap($this->private_key, 64, "\n", true) ;
        $key = openssl_get_privatekey($privateKey);
        $signature = null;
        openssl_sign($toSign, $signature, $key);
        openssl_free_key($key);
        $sign = base64_encode($signature);
        return $sign;
    }
    
    /**
     * 验证签名
     *  $data 需要验签的字符串
     *  $sign 讯联返回报文中的签名串
     *  $public_key_path 公钥路径
     *  $sha 保留后续修改成256或其它算法的可能性
     *  返回验签结果 1为成功   0位失败    -1为异常
     */
    function verifysign($data, $sign, $public_key_path, $sha="sha1WithRSAEncryption"){
        //测试用参数，可用来快速验证公钥是否存在问题，下面的2个固定内容是经过验证的，可以通过验签
//      $data = "merOrderId=20190710150911$1234&merchantId=000010461&respDate=20190720 13:27:54&resultCode=90020910&resultMsg=90020910|模拟返回交易失败&retFlag=F";
//      $sign = "dUaVIIzAOcCCPxjRjWT+61Vf0ChrGbMPlNQHh47CYGplOyIxr3VsefD4SZ/kgxxrBZV5082uri7g/IVy4oSKrq70cD5iOAw59wbazSYlBhI6a1b2+bRCE3FPq0F6imtTDddwbsnRQ03+lEHpX6z7ozvd1fLYfP1vtSXxyrON0YpJgUkDP55zydMTOOHzu1Gc7OOBr/K1LtuWSEtxuMH96EHamMXbLOpBh217ooTQ9Pt/gZFIZDBkfsI1HN4U1YbwcgMjeoisnv2NYQXNFClYGr/ZCavtyjhT4Jk4BP7HdoKJXF1rNdTqaqi8zU4hP/IXcbIVcU0ACpmCwljCaZZUYg==";

        //读取公钥文件与格式化
        $cer= file_get_contents($public_key_path);
        $pem = chunk_split(base64_encode($cer),64,"\n");//转换为pem格式的公钥
        $pem = "-----BEGIN CERTIFICATE-----\n".$pem."-----END CERTIFICATE-----\n";
        $pubkeyid = openssl_get_publickey($pem);
        //验签
        $result = openssl_verify($data, base64_decode($sign), $pubkeyid, $sha);
        return $result;
    }
}