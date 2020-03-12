<?php

namespace App\Http\Controllers\Api\PgwPay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 工具类
class EncryptUtilController
{
    private $private_key;

    private $public_key;

    function __construct($private_key_path, $public_key_path, $private_key_password = '123', $Debug = FALSE)
    {
        if (!$Debug) ob_start();
        // 初始化商户私钥
        if ($private_key_path !== null) {
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
    public function getSign($toSign)
    {
        $privateKey = wordwrap($this->private_key, 64, "\n", true);

        // $key = openssl_get_privatekey($privateKey);
        $key = openssl_pkey_get_private($privateKey);

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
    function verifySign($data, $sign, $public_key_path, $sha = "sha1WithRSAEncryption")
    {
        //测试用参数，可用来快速验证公钥是否存在问题，下面的2个固定内容是经过验证的，可以通过验签
        //$data = "merOrderId=20190710150911$1234&merchantId=000010461&respDate=20190720 13:27:54&resultCode=90020910&resultMsg=90020910|模拟返回交易失败&retFlag=F";
        //$sign = "dUaVIIzAOcCCPxjRjWT+61Vf0ChrGbMPlNQHh47CYGplOyIxr3VsefD4SZ/kgxxrBZV5082uri7g/IVy4oSKrq70cD5iOAw59wbazSYlBhI6a1b2+bRCE3FPq0F6imtTDddwbsnRQ03+lEHpX6z7ozvd1fLYfP1vtSXxyrON0YpJgUkDP55zydMTOOHzu1Gc7OOBr/K1LtuWSEtxuMH96EHamMXbLOpBh217ooTQ9Pt/gZFIZDBkfsI1HN4U1YbwcgMjeoisnv2NYQXNFClYGr/ZCavtyjhT4Jk4BP7HdoKJXF1rNdTqaqi8zU4hP/IXcbIVcU0ACpmCwljCaZZUYg==";

        //读取公钥文件与格式化
        $cer = file_get_contents($public_key_path);
        //转换为pem格式的公钥
        $pem = chunk_split(base64_encode($cer), 64, "\n");
        $pem = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
        $pubkeyid = openssl_get_publickey($pem);
        //验签
        $result = openssl_verify($data, base64_decode($sign), $pubkeyid, $sha);
        return $result;
    }
}
