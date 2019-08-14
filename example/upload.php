<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-08-07
 * Time: 11:19
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';
date_default_timezone_set('PRC');

include './config.php';

$apiClient = new \Icbc\ApiClient($apiUrl, $appId, $priKey, $pubKey);

$apiClient->addParams('corp_no', $corpNo)
    ->addParams('cert_type', '0')
    ->addParams('cert_no', 'xxxxxxxxx');
$response = $apiClient->upload('./touxiang.png');

if (!$response->isSuccess()) {
    throw new \Exception($response->getError());
}
var_dump($response->getRspData());