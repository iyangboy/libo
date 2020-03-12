<?php

namespace App\Http\Controllers\Api\PgwPay;

use App\Exceptions\InvalidRequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PgwAgreementPaymentVerificationRequest;
use App\Http\Requests\Api\PgwSigningRequest;
use App\Models\PgwPayLog;
use App\Models\User;
use App\Models\UserBankCard;
use App\Models\UserVip;
use Carbon\Carbon;

class PgwPayController extends Controller
{
    //
    // 正式
    // public $h_url = 'https://ppay.zhengtongcf.com/pgw-quickpay';
    // 测试环境
    public $h_url = 'https://www.tongtongcf.com:9204/pgw-quickpay';
    //版本号
    public $version = '1.0.1';
    //商户号
    public $merchantId = '000010520';

    // 签约发短信
    public function signingSMS($vip_order_id, Request $request)
    {
        $url = $this->h_url . '/quickpay/identifyauth';

        $order = UserVip::with(['user.userBankCard.bankCode'])->find($vip_order_id);

        $user = $order->user ?? '';
        $bankCard = $order->user->userBankCard ?? '';
        $bankCode = $order->user->userBankCard->bankCode ?? '';
        // return $order;
        $params = [
            'version'     => $this->version,
            'merchantId'  => $this->merchantId,
            'merOrderId'  => $order->no . date('His'),      //商户订单号
            'organCode'   => $bankCode->code ?? '',         //银行机构号
            'organName'   => $bankCode->name ?? '',         //银行名称
            'reqDate'     => date('Ymd H:i:s'),             //请求时间
            'name'        => $bankCard->user_name ?? '',    //姓名
            'account'     => $bankCard->card_number ?? '',  //银行账号
            'accountType' => '90',                          //账号类型
            'idType'      => '10',                          //证件类型
            'idCode'      => $user->id_card ?? '',          //证件号
            'mobile'      => $bankCard->phone ?? '',        //手机号
            //加密后的CVV2,用sdk.rsa_encryption加密
            'cvv2'        =>  $this->rsa_encryption(''),
            //加密后的有效期,用sdk.rsa_encryption加密
            'validDate'   =>  $this->rsa_encryption(''),
        ];

        // return $params;

        $CSReq = 'QIAReq';
        $MessageId = date('YmdHis') . 102;

        $rs = $this->getResult($url, $params, $CSReq, $MessageId);

        $params_xml = $rs['params_xml'];
        $result_xml = $rs['result_xml'];
        $result = $rs['result'];

        $ret_flag        = $result['Message']['CSRes']['retFlag'] ?? '';
        $result_code     = $result['Message']['CSRes']['resultCode'] ?? '';
        $result_msg      = $result['Message']['CSRes']['resultMsg'] ?? '';
        $re_mer_order_id = $result['Message']['CSRes']['merOrderId'] ?? '';
        $re_sms_send_no  = $result['Message']['CSRes']['smsSendNo'] ?? '';
        $re_protocol_id  = $result['Message']['CSRes']['protocolId'] ?? '';
        // return $result;
        // 记录存储
        $log = new PgwPayLog();

        $log->type            = 'vip_order';
        $log->user_id         = $user->id ?? '';
        $log->vip_order_id    = $vip_order_id;
        // $log->parent_id    = $vip_order_id;
        $log->status          = '1';
        $log->mer_order_id    = $params['merOrderId'];
        // $log->params          = json_encode($params);
        $log->params          = $params;
        $log->params_xml      = $params_xml;
        // $log->result          = json_encode($result);
        $log->result          = $result;
        $log->result_xml      = $result_xml;
        $log->ret_flag        = $ret_flag;
        $log->result_code     = $result_code;
        $log->result_msg      = $result_msg;
        $log->re_mer_order_id = $re_mer_order_id;
        if (!empty($re_sms_send_no)) {
            $log->re_sms_send_no  = $re_sms_send_no;
        }
        if (!empty($re_protocol_id)) {
            $log->re_protocol_id  = $re_protocol_id;
        }
        //
        $log->save();

        if ($ret_flag == 'T') {
            // 签约成功
            $log_id = $log->id;

            return response()->json([
                'success' => [$result_msg],
                'data'    => $log
            ], 201);
        }
        // dd($rs);
        return response()->json([
            'success' => [$result_msg],
            'data'    => $log
        ], 401);
    }

    // 签约
    public function signing($log_id, PgwSigningRequest $request)
    {
        // dump('签约');
        $url = $this->h_url . '/quickpay/sign';
        $pLog = PgwPayLog::find($log_id);
        // return $pLog;


        $merOrderId = $pLog->mer_order_id;

        $smsSendNo = $pLog->re_sms_send_no ? ($pLog->re_sms_send_no) : ($pLog->result['Message']['CSRes']['smsSendNo'] ?? '');
        $smsVerifyCode = $request->sms_verify_code ?? '123456';

        $params = [
            'version'         => $this->version,
            'merchantId'      => $this->merchantId,
            'merOrderId'      => $merOrderId,
            'reqDate'         => date('Ymd H:i:s'),
            'cvv2'            => $this->rsa_encryption(''),
            'validDate'       => $this->rsa_encryption(''),
            'smsSendNo'       => $smsSendNo ?? '',
            'smsVerifyCode'   => $smsVerifyCode,
        ];

        $CSReq = 'SIReq';
        $MessageId = date('YmdHis') . 202;

        $rs = $this->getResult($url, $params, $CSReq, $MessageId);
        // return $rs;
        $params_xml = $rs['params_xml'];
        $result_xml = $rs['result_xml'];
        $result = $rs['result'];

        $ret_flag        = $result['Message']['CSRes']['retFlag'] ?? '';
        $result_code     = $result['Message']['CSRes']['resultCode'] ?? '';
        $result_msg      = $result['Message']['CSRes']['resultMsg'] ?? '';
        $re_mer_order_id = $result['Message']['CSRes']['merOrderId'] ?? '';
        $re_sms_send_no  = $result['Message']['CSRes']['smsSendNo'] ?? '';
        $re_protocol_id  = $result['Message']['CSRes']['protocolId'] ?? '';
        // return $result;
        // 记录存储
        $log = new PgwPayLog();

        $log->type            = 'vip_order';
        $log->user_id         = $pLog->id ?? '';
        $log->vip_order_id    = $pLog->vip_order_id;
        $log->parent_id       = $pLog->id;
        $log->status          = '2';
        $log->mer_order_id    = $params['merOrderId'];
        // $log->params          = json_encode($params);
        $log->params          = $params;
        $log->params_xml      = $params_xml;
        // $log->result          = json_encode($result);
        $log->result          = $result;
        $log->result_xml      = $result_xml;
        $log->ret_flag        = $ret_flag;
        $log->result_code     = $result_code;
        $log->result_msg      = $result_msg;
        $log->re_mer_order_id = $re_mer_order_id;
        if (!empty($re_sms_send_no)) {
            $log->re_sms_send_no  = $re_sms_send_no;
        }
        if (!empty($re_protocol_id)) {
            $log->re_protocol_id  = $re_protocol_id;
        }
        //
        $log->save();

        if ($ret_flag == 'T') {
            // 签约成功
            $log_id = $log->id;

            // 存储签约ID
            if ($re_protocol_id) {
                $account = $pLog->params['account'] ?? '';
                $bank = UserBankCard::where('user_id', $pLog->user_id)->where('card_number', $account)->first();
                $bank->protocol_id = $re_protocol_id;
                $bank->save();
            }

            return response()->json([
                'success' => [$result_msg],
                'data'    => $log
            ], 201);
        }
        // dd($rs);
        return response()->json([
            'success' => [$result_msg],
            'data'    => $log
        ], 401);
    }

    // 协议支付
    public function agreementPayment($vip_order_id, Request $request)
    {
        // dump('协议支付');
        $url = $this->h_url . '/quickpay/pay';
        $order = UserVip::with(['user.userBankCardProtocol.bankCode'])->find($vip_order_id);
        // return $order;
        // $smsVerifyCode = $request->sms_verify_code ?? '123456';
        $user = $order->user;
        // 支付金额
        $amount = $order->total_amount;
        // 签约协议号 // 签约返回值
        $bankCode = $order->user->userBankCardProtocol->bankCode ?? '';
        $userBankCard = $order->user->userBankCardProtocol ?? '';
        $protocolId = $userBankCard->protocol_id ?? '';
        $name = $userBankCard->user_name ?? '';
        $account = $userBankCard->card_number ?? '';

        // 订单详情
        // $orderDesc = '2#2#商品1简称^100.00^1#商品2简称^50.00^20';
        $orderDesc = '1#1#商品1简称^' . $amount . '^1';

        $params = [
            'version'         => $this->version,
            'merchantId'      => $this->merchantId,
            'payOrderId'      => $order->no . date('His'),
            'reqDate'         => date('Ymd H:i:s'),
            'amount'          => $amount,
            'protocolId'      => $protocolId,
            'name'            => $name ?? '',     // 用户名
            'account'         => $account ?? '',  // 银行账号
            'payType'         => 120014,          // 支付场景 - 信贷发放
            'orderDesc'       => $orderDesc,      // 订单详情
            // 'subMercId'       => '',              // 二级商户号(二级商户交易时必填)
            // 'subMercName'     => '',              // 二级商户简称(二级商户交易时必填)
            'cvv2'            => $this->rsa_encryption(''),
            'validDate'       => $this->rsa_encryption(''),
        ];

        $CSReq = 'QPReq';
        $MessageId = date('YmdHis') . 302;

        $rs = $this->getResult($url, $params, $CSReq, $MessageId);

        // return $rs;
        $params_xml = $rs['params_xml'];
        $result_xml = $rs['result_xml'];
        $result = $rs['result'];

        $ret_flag        = $result['Message']['CSRes']['retFlag'] ?? '';
        $result_code     = $result['Message']['CSRes']['resultCode'] ?? '';
        $result_msg      = $result['Message']['CSRes']['resultMsg'] ?? '';
        // $re_mer_order_id = $result['Message']['CSRes']['merOrderId'] ?? '';
        $re_pay_order_id = $result['Message']['CSRes']['payOrderId'] ?? '';
        $re_sms_send_no  = $result['Message']['CSRes']['smsSendNo'] ?? '';
        $re_protocol_id  = $result['Message']['CSRes']['protocolId'] ?? '';
        // return $result;
        // 记录存储
        $log = new PgwPayLog();

        $log->type            = 'vip_order';
        $log->user_id         = $user->id ?? '';
        $log->vip_order_id    = $vip_order_id;
        // $log->parent_id    = $vip_order_id;
        $log->status          = '3';
        // $log->mer_order_id    = $params['merOrderId'];
        $log->pay_order_id    = $params['payOrderId'];
        // $log->params          = json_encode($params);
        $log->params          = $params;
        $log->params_xml      = $params_xml;
        // $log->result          = json_encode($result);
        $log->result          = $result;
        $log->result_xml      = $result_xml;
        $log->ret_flag        = $ret_flag;
        $log->result_code     = $result_code;
        $log->result_msg      = $result_msg;
        // $log->re_mer_order_id = $re_mer_order_id;
        $log->re_pay_order_id = $re_pay_order_id;
        if (!empty($re_sms_send_no)) {
            $log->re_sms_send_no  = $re_sms_send_no;
        }
        if (!empty($re_protocol_id)) {
            $log->re_protocol_id  = $re_protocol_id;
        }
        //
        $log->save();

        // 需要短信验证
        if ($result_code == '0099') {
            return response()->json([
                'success' => [$result_msg],
                'data'    => $log
            ], 201);
        }

        if ($ret_flag == 'T') {
            // 签约成功
            $log_id = $log->id;

            return response()->json([
                'success' => [$result_msg],
                'data'    => $log
            ], 201);
        }
        // dd($rs);
        return response()->json([
            'success' => [$result_msg],
            'data'    => $log
        ], 401);

        $data = [
            'merchantId' => $rs->merchantId,
            'payOrderId' => $rs->payOrderId,
            'amount'     => $rs->amount,      // 金额
            'protocolId' => $rs->protocolId,  // 协议号
            'respDate'   => $rs->respDate,
            'retFlag'    => $rs->retFlag,     // 交易结果 （T-成功，F-失败，P-未明）
            'resultCode' => $rs->resultCode,  // 结果代码 （0032表示支付成功，0099需要短信验证，9998 需要重新签约短信验证）
            'resultMsg'  => $rs->resultMsg,   // 结果描述
            'name'       => $rs->name,        // 客户姓名 （支付成功时返回）
            'account'    => $rs->account,     // 客户姓名 （支付成功时返回）
            'serialNo'   => $rs->serialNo,    // 付交易流水号 （支付成功时返回）
            'smsSendNo'  => $rs->smsSendNo,   // 短信发送编号  （交易结果为0099或9998时返回）
            'sign'       => $rs->sign,
        ];
    }

    // 协议支付 - 短信验证  QPReq
    public function agreementPaymentVerification($log_id, PgwAgreementPaymentVerificationRequest $request)
    {
        // dump('协议支付 - 短信验证');

        $url = $this->h_url . '/quickpay/smsvalidate';
        // 短信验证码
        $smsVerifyCode = $request->sms_verify_code ?? '123456';

        $pLog = PgwPayLog::find($log_id);
        // return $pLog;

        $payOrderId = $pLog->pay_order_id;

        $smsSendNo = $pLog->re_sms_send_no ? ($pLog->re_sms_send_no) : ($pLog->result['Message']['CSRes']['smsSendNo'] ?? '');

        // 支付金额
        $amount = $pLog->params['amount'];
        // 签约协议号 // 签约返回值
        $protocolId = $pLog->re_protocol_id;

        $params = [
            'version'         => $this->version,
            'merchantId'      => $this->merchantId,
            'payOrderId'      => $payOrderId,
            'reqDate'         => date('Ymd H:i:s'),
            'amount'          => $amount,
            'protocolId'      => $protocolId,
            'smsSendNo'       => $smsSendNo,                // 短信发送编号
            'smsVerifyCode'   => $smsVerifyCode ?? '',      // 短信验证码
            'cvv2'            => $this->rsa_encryption(''),
            'validDate'       => $this->rsa_encryption(''),
        ];

        $CSReq = 'QPReq';
        $MessageId = date('YmdHis') . 402;

        $rs = $this->getResult($url, $params, $CSReq, $MessageId);

        // return $rs;
        $params_xml = $rs['params_xml'];
        $result_xml = $rs['result_xml'];
        $result = $rs['result'];

        $ret_flag        = $result['Message']['CSRes']['retFlag'] ?? '';
        $result_code     = $result['Message']['CSRes']['resultCode'] ?? '';
        $result_msg      = $result['Message']['CSRes']['resultMsg'] ?? '';
        // $re_mer_order_id = $result['Message']['CSRes']['merOrderId'] ?? '';
        $re_pay_order_id = $result['Message']['CSRes']['payOrderId'] ?? '';
        $re_sms_send_no  = $result['Message']['CSRes']['smsSendNo'] ?? '';
        $re_protocol_id  = $result['Message']['CSRes']['protocolId'] ?? '';
        // return $result;
        // 记录存储
        $log = new PgwPayLog();

        $log->type            = 'vip_order';
        $log->user_id         = $pLog->user_id ?? '';
        $log->vip_order_id    = $pLog->vip_order_id;
        $log->parent_id       = $log_id;
        $log->status          = '4';
        // $log->mer_order_id    = $params['merOrderId'];
        $log->pay_order_id    = $params['payOrderId'];
        // $log->params          = json_encode($params);
        $log->params          = $params;
        $log->params_xml      = $params_xml;
        // $log->result          = json_encode($result);
        $log->result          = $result;
        $log->result_xml      = $result_xml;
        $log->ret_flag        = $ret_flag;
        $log->result_code     = $result_code;
        $log->result_msg      = $result_msg;
        // $log->re_mer_order_id = $re_mer_order_id;
        $log->re_pay_order_id = $re_pay_order_id;
        if (!empty($re_sms_send_no)) {
            $log->re_sms_send_no  = $re_sms_send_no;
        }
        if (!empty($re_protocol_id)) {
            $log->re_protocol_id  = $re_protocol_id;
        }
        //
        $log->save();

        // 需要重新验证
        if ($result_code == '9998') {
            return response()->json([
                'success' => [$result_msg],
                'data'    => $log
            ], 201);
        }

        if ($result_code == '0032') {
            // 支付成功
            $log_id = $log->id;

            // 修改订单状态
            $order = UserVip::where('user_id', $pLog->user_id)->find($pLog->vip_order_id);
            $order->paid_at = Carbon::now();
            $order->status = UserVip::STATUS_SUCCESS;
            $order->payment_method = 'pgw_pay';
            $order->payment_no = $protocolId;
            $order->pay_log_id = $log_id;
            $order->save();

            $user = User::find($pLog->user_id);
            $user->grade_id = 1;
            $user->save();

            return response()->json([
                'success' => [$result_msg],
                'data'    => $log
            ], 201);
        }
        // dd($rs);
        return response()->json([
            'success' => [$result_msg],
            'data'    => $log
        ], 401);

        $data = [
            'merchantId' => $rs->merchantId,
            'payOrderId' => $rs->payOrderId,
            'amount'     => $rs->amount,      // 金额
            'protocolId' => $rs->protocolId,  // 协议号
            'respDate'   => $rs->respDate,
            'retFlag'    => $rs->retFlag,     // 交易结果 （T-成功，F-失败，P-未明）
            'resultCode' => $rs->resultCode,  // 结果代码 （0032表示支付成功，0099需要短信验证，9998 需要重新签约短信验证）
            'resultMsg'  => $rs->resultMsg,   // 结果描述
            'name'       => $rs->name,        // 客户姓名 （支付成功时返回）
            'account'    => $rs->account,     // 客户姓名 （支付成功时返回）
            'serialNo'   => $rs->serialNo,    // 付交易流水号 （支付成功时返回）
            'smsSendNo'  => $rs->smsSendNo,   // 短信发送编号 （交易结果为9998时返回。此时交易并未结束，商户需要继续调用本接口）
            'sign'       => $rs->sign,
        ];
    }

    // 单笔退款  QRReq
    public function oneRefund(Request $request)
    {
        $url = $this->h_url . '/quickpay/refund';
        $user = [];

        // 退款金额
        $amount = 100.00;
        // 原商户订单号 // 原商户支付订单号
        $orgPayOrderId = '';

        $params = [
            'version'         => $this->version,
            'merchantId'      => $this->merchantId,
            'refundOrderId'   => date('YmdH:i:s') . 111111,
            'reqDate'         => date('Ymd H:i:s'),
            'amount'          => $amount,
            'orgPayOrderId'   => $orgPayOrderId,
            // 'reserved'        => '',      // 保留域
            'sign'            => '',
        ];

        $rs = $this->getResult($url, $params);

        // 应答 QRRes
        $data = [
            'merchantId'    => $rs->merchantId,
            'refundOrderId' => $rs->refundOrderId,
            'amount'     => $rs->amount,             // 金额
            'respDate'   => $rs->respDate,
            'retFlag'    => $rs->retFlag,            // 交易结果 （T-成功，F-失败，P-未明）
            'resultCode' => $rs->resultCode,         // 结果代码 （0032表示支付成功，0099需要短信验证，9998 需要重新签约短信验证）
            'resultMsg'  => $rs->resultMsg ?? '',    // 结果描述
            'account'    => $rs->account,            // 交易账号 （支付成功时返回）
            'serialNo'   => $rs->serialNo,           // 付交易流水号 （支付成功时返回）
            // 'reserved'  => $rs->reserved,         // 保留域
            'sign'       => $rs->sign,
        ];
    }

    // 单笔交易查询  TSReq
    public function oneQuery(Request $request)
    {
        $url = $this->h_url . '/quickpay/tradestatus';
        $user = [];

        // 退款金额
        $amount = 100.00;
        // 原商户订单号 // 原商户支付订单号
        $orgPayOrderId = '';

        $params = [
            'version'         => $this->version,
            'merchantId'      => $this->merchantId,
            'reqDate'         => date('Ymd H:i:s'),
            'amount'          => $amount,
            'type'            => 0,                      // 交易性质 (0-全部，1-支付，2-退款)
            'merOrderId'      => $merOrderId ?? '',      // 商户订单号 (商户支付/退款订单号)
            'serialNo'        => '',                     // 支付交易流水号
            'sign'            => '',
        ];

        $rs = $this->getResult($url, $params);

        // 应答 TSRes
        $data = [
            'merchantId'      => $rs->merchantId,
            'respDate'        => $rs->respDate,
            'type'            => $rs->type,              // 交易类型 (1-支付，2-退款)
            'merOrderId'      => $rs->merOrderId,        // 商户的订单号
            'serialNo'        => $rs->serialNo,          // 交易流水号
            'queryResult'     => $rs->queryResult,       // 查询结果 (T：成功 F：失败)
            'account'         => $rs->account,           // 交易账号
            'amount'          => $rs->amount,            // 交易金额
            'refundedAmount'  => $rs->refundedAmount,    // 已退款金额
            'retFlag'         => $rs->retFlag,           // 交易结果 （T-成功，F-失败，P-未明）
            'resultCode'      => $rs->resultCode,        // 结果代码 （0032表示支付成功，0099需要短信验证，9998 需要重新签约短信验证）
            'resultMsg'       => $rs->resultMsg ?? '',   // 结果描述
            'sign'            => $rs->sign,
        ];
    }

    // 获取请求结果
    public function getResult($url, $params, $CSReq = '', $MessageId = '')
    {
        $sign_str = $this->getSign($params);

        $append_xml = '';
        $append_xml .= '<EctData>' . "\n";
        $append_xml .= '<Message id="' . $MessageId . '">' . "\n";
        $append_xml .= '<CSReq id="' . $CSReq . '">' . "\n";
        foreach ($params as $key => $value) {
            if ($value) {
                $append_xml .= '<' . $key . '>' . $value . '</' . $key . '>' . "\n";
            }
        }
        $append_xml .= '<sign>' . $sign_str . '</sign>' . "\n";
        $append_xml .= '</CSReq>' . "\n";
        $append_xml .= '</Message>' . "\n";
        $append_xml .= '</EctData>' . "\n";
        // dump($append_xml);

        $result = $this->curl_post_https($url, $append_xml);
        // dump($result);
        // $result_mb = mb_convert_encoding($result, 'GBK', 'UTF-8,GBK,GB2312,BIG5');
        // dd($result_mb);

        $obj = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
        // dump($obj);
        // dump($obj->Message->CSReq->merchantId);
        $data = json_decode(json_encode($obj), true);

        // dump($data);


        return [
            'params_xml' => $append_xml,
            'result_xml' => $result,
            'result'     => $data,
        ];


        // dump($data['Message']['CSRes']['smsSendNo']);
        dd(1);

        //验签
        $xml = simplexml_load_string($result);
        $parm = array();
        $sign = null;

        foreach ($xml->children()->children()->children() as $child) {
            if (empty($child) == false && $child->getName() !== "sign") {
                $parm[$child->getName()] = $child->__toString();
            }
            if ($child->getName() === "sign") {
                $sign = $child->__toString();
            }
        }

        $verify_result = $this->verify_sign($parm, $sign);

        if ($verify_result === 1) {
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
    public function getSign($signarr)
    {
        //按照key排序
        ksort($signarr);
        $query_str = http_build_query($signarr);
        $query_str = urldecode($query_str);
        //dd($query_str)
        //商户私钥,请根据实际私钥替换
        //$pfxpath = dirname(__FILE__).'/merchant.pfx';
        // $pfxpath = public_path('pgw-pay/merchant.pfx');
        $pfxpath = public_path('pgw-pay/000010520.pfx');
        if (!file_exists($pfxpath)) {
            throw new InvalidRequestException('商户私钥未载入');
        }
        //测试默认私钥密码
        $pfx_pwd = '123';

        //实例化加密类。
        $encryptUtil = new EncryptUtilController($pfxpath, null, $pfx_pwd, true);
        return $encryptUtil->getSign($query_str);
    }

    /**
     * 验证签名
     * $data 需要验签的字符串
     * $sign 讯联返回报文中的签名串
     * 返回验签结果 1为成功   0位失败    -1为异常
     */
    function verify_sign($data, $sign)
    {
        //按照key排序
        ksort($data);
        $query_str = http_build_query($data);
        $query_str = urldecode($query_str);

        //xunlian公钥
        // $public_key_path = dirname(__FILE__) . '/xl_pub_key.cer';
        $public_key_path = public_path('pgw-pay/xl_pub_key.cer');

        // if (!file_exists($public_key_path)) die('public key not exists');
        if (!file_exists($public_key_path)) {
            throw new InvalidRequestException('商户公钥未载入');
        }

        //实例化加密类。
        $encryptUtil = new EncryptUtilController(null, $public_key_path, null, true);
        return $encryptUtil->verifySign($query_str, $sign, $public_key_path);
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
        // $public_key_str = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCme526ar35LeqRQaWzFvAd2NTKPmHdNfizeubSO3+bkSrLT/hB3ZS46FGCK1r4SM6/Ka19ej2VP8oWrOrBUzk10pUBuvcBu7c2raVTs8oPM9O/notC460TPO9Bg8247lborjZGm9Fv0nlHhN0RYoUYUVuzvQkbBtpCqTO8sNeewIDAQAB';
        $key = wordwrap($public_key_str, 64, "\n", true) . "\n";
        $pem_key = "-----BEGIN PUBLIC KEY-----\n" . $key . "-----END PUBLIC KEY-----\n";
        $pu_key_pem = openssl_pkey_get_public($pem_key);
        $encrypt = '';
        openssl_public_encrypt($str, $encrypt, $pu_key_pem, OPENSSL_PKCS1_PADDING);
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
        return $tmpInfo;                                    //返回json对象
    }

    /**
     * curl https post
     */
    function curl_post_https($url, $post = '')
    {
        $curl = curl_init();                               // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);             // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);     // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);     // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);     // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);        // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1);               // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);     // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);           // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0);             // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);     // 获取的信息以文件流的形式返回
        $res = curl_exec($curl);                           // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);              //捕抓异常
        }
        curl_close($curl);                                 // 关闭CURL会话
        return $res;                                       // 返回数据，json格式
    }
}
