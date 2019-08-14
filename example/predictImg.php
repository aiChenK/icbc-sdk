<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-08-08
 * Time: 14:31
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';
date_default_timezone_set('PRC');

include './config.php';

$apiClient = new \Icbc\ApiClient($apiUrl, $appId, $priKey, $pubKey);

$apiClient->addParams('corp_no', $corpNo)
    ->addParams('busisno', '1')
    ->addParams('cert_type', '0')
    ->addParams('cert_no', 'xxxxxxxxx');

$response = $apiClient->execute('com.icbc.eracct.predictimg');

if (!$response->isSuccess()) {
    throw new \Exception($response->getError());
}
var_dump($response->getRspData());
//var_dump($response->getData());
