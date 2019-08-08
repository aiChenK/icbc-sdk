<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-07-03
 * Time: 17:15
 */

namespace Icbc;

use HttpClient\Client;
use Icbc\Core\ApiResponse;
use Icbc\Core\Constants;
use Icbc\Core\Key;

class ApiClient
{
    private $priKey;
    private $pubKey;
    private $httpClient;

    private $bodyParams  = [];
    private $appId;
    private $apiVersion  = '1.0';
    private $formatType  = 'json';
    private $signType    = 'RSA';
    private $timestamp   = '';

    public function __construct($apiUrl, $appId, $priKey, $pubKey)
    {
        $this->appId     = $appId;
        $this->priKey    = $priKey;
        $this->pubKey    = $pubKey;
        $this->timestamp = date('Y-m-d H:i:s');

        $this->httpClient = new Client($apiUrl);
        $this->httpClient->followLocation(true);
        $this->httpClient->setConnectTimeout(Constants::CONNECT_TIMEOUT);
        $this->httpClient->setOptions([
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_0
        ]);
    }

    //添加参数
    public function addParams($key, $value)
    {
        $this->bodyParams[$key] = $value;
        return $this;
    }

    /**
     * 执行接口调用
     *
     * @param $apiName
     * @return ApiResponse
     * @throws \Exception
     */
    public function execute($apiName)
    {
        $this->setSign($apiName);
        $data = $this->httpClient->post($apiName, $this->bodyParams)->getBody();

        return new ApiResponse($data, $this->pubKey);
    }

    /**
     * 执行上传文件
     *
     * @param $file
     * @param string $realName
     * @return ApiResponse
     * @throws \Exception
     */
    public function upload($file, $realName = '')
    {
        if (!file_exists($file)) {
            throw new \Exception('文件不存在');
        }
        if (!$realName) {
            $realName = basename($file);
        }
        $fileData = file_get_contents($file);

        $this->addParams(Constants::FN_UPLOAD_FILE_NAME, $realName)
            ->addParams(Constants::FN_UPLOAD_FILE_SIGN, $this->getFileSign($fileData))
            ->setSign(Constants::FN_UPLOAD_FILE_API);

        $this->httpClient->setHeaders([
            'Content-Disposition' => "form-data;name='{$realName}';filename={$realName}",
            'Content-Type'        => 'application/octet-stream',
            'Content-Length'      => filesize($file)
        ]);

        $path = Constants::API_UPLOAD_PREFIX . '/' .Constants::FN_UPLOAD_FILE_API . '?' . http_build_query($this->getBodyParams());
        $data = $this->httpClient->post($path, $fileData, false)->getBody();

        return new ApiResponse($data, $this->pubKey);
    }

    private function getBodyParams()
    {
        return $this->bodyParams;
    }

    private function toUrlParams($array)
    {
        $buff = '';
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                continue;
            }
            $buff .= "{$k}={$v}&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    //添加数据并签名
    private function setSign($apiName)
    {
        $this->addParams(Constants::FN_APP_ID, $this->appId);
        $this->addParams(Constants::FN_FORMAT, $this->formatType);
        $this->addParams(Constants::FN_VERSION, $this->apiVersion);
        $this->addParams(Constants::FN_API_NAME, $apiName);
        $this->addParams(Constants::FN_SIGN_TYPE, $this->signType);
        $this->addParams(Constants::FN_TIMESTAMP, $this->timestamp);
        $this->addParams(Constants::FN_SIGN, $this->getSign());
    }

    //获取签名
    private function getSign()
    {
        $bodyParams = $this->getBodyParams();

        ksort($bodyParams); //按ASCII升序排列
        reset($bodyParams);
        //参与签名的数据
        $data = $this->toUrlParams($bodyParams);

        //进行rsa签名
        $res = Key::getPrivateKey($this->priKey);
        openssl_sign($data,$sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);

        return $sign;
    }

    //获取文件签名
    private function getFileSign($data)
    {
        //进行rsa签名
        $res = Key::getPrivateKey($this->priKey);
        openssl_sign($data,$sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);

        return $sign;
    }
}