<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-08-06
 * Time: 17:03
 */

namespace Icbc\Core;

use Icbc\Exception\SignException;

class ApiResponse
{

    private $body;
    private $sign;
    private $certId;
    private $signBlk;
    private $rspData;

    private $error = false;
    private $data  = [];

    /**
     * ApiResponse constructor.
     * @param $body
     * @param bool $pubKey
     * @throws SignException
     * @throws \Exception
     */
    public function __construct($body, $pubKey = false)
    {
        if (is_string($body)) {
            $body = json_decode($body, true);
        }
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

        //有公钥则验签
        if ($pubKey) {
            $this->checkSign($pubKey);
        }
    }

    /**
     * @param $pubKey
     * @throws SignException
     * @throws \Exception
     */
    private function checkSign($pubKey)
    {
        $sign = base64_decode($this->sign); //base64解码加密信息
        $res  = Key::getPublicKey($pubKey); //读取公钥
        if (openssl_verify($this->signBlk, $sign, $res) !== 1) {
            throw new SignException('sign verify failed, pls recheck the public key');
        }
    }

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