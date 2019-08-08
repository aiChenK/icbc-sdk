<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-08-06
 * Time: 17:03
 */

namespace Icbc;

class ApiResponse
{

    private $body;
    private $sign;
    private $certId;
    private $signBlk;
    private $rspData;

    private $error = false;
    private $data  = [];
    
    private $pubKey;

    public function __construct($body, $pubKey = '')
    {
        if (is_string($body)) {
            $body = json_decode($body, true);
        }
        $this->pubKey  = $pubKey;
        $this->body    = $body;
        $this->rspData = $body[Constants::FN_RSP_DATA];
        $this->sign    = $body[Constants::FN_SIGN];
        $this->certId  = $body[Constants::FN_CERT_ID];
        $this->signBlk = json_encode($this->rspData,JSON_UNESCAPED_UNICODE);

        //解析内容
        if ($this->rspData['ICBC_API_RETCODE'] != 0) {
            $this->error = $this->rspData['ICBC_API_RETMSG'];
        } elseif ($this->rspData['hostRspCode'] != '00000') {
            $this->error = $this->rspData['hostRspMsg'];
        } else {
            $this->data = $this->rspData['response'];
        }
    }

//    public function checkSign()
//    {
//        //todo 更改
//        $sign = base64_decode($this->sign);//base64解码加密信息
//        $cer  = openssl_get_publickey($this->pubKey);
////        $cer  = openssl_x509_read($this->pubKey); //读取公钥
//        $res  = openssl_verify($this->signBlk, $sign, $cer); //验证
//        //输出验证结果，1：验证成功，0：验证失败
//        return !!$res;
//    }
    public function getRspData()
    {
        return $this->rspData;
    }

    public function isSuccess()
    {
        return $this->error === false;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getData()
    {
        return $this->data;
    }
}