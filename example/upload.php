<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-08-07
 * Time: 11:19
 */
require_once dirname(__DIR__) . '/src/Bootstrap.php';
\Icbc\Bootstrap::init();
date_default_timezone_set('PRC');

include './config.php';

$apiClient = new \Icbc\ApiClient($apiUrl, $appId, $priKey, $pubKey);

$apiClient->addParams('corp_no', $corpNo)
    ->addParams('cert_type', '0')
    ->addParams('cert_no', '330621199512060835');
$response = $apiClient->upload('./touxiang.png');

var_dump($response->getRspData());