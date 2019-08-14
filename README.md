# icbc-sdk
> 工商银行二类户金融服务接口SDK
实现快速调用，传入私钥即可对请求进行签名

## 运行环境
- PHP 5.6+
- openssl extension
- aichenk/http-client ^1.0

## 安装方法
1. 根目录运行

        composer require aichenk/icbc-sdk
        
2. 在`composer.json`中声明

        "require": {
            "aichenk/icbc-sdk": "^1.0"
        }
            
## 使用
```php
//参数声明
$appId  = '';
$corpNo = '';
$apiUrl = '';
$priKey = "./pri.pem"; //文件地址或字符串内容
$pubKey = "./pub.cer"; //支持字符串/cer/pem，可不传入，不传入则不对内容验签
 
//初始化
$apiClient = new \Icbc\ApiClient($apiUrl, $appId, $priKey, $pubKey);
$apiClient->setCorpNo($corpNo);
 
$apiClient->addParams('busisno', '1')
    //->addParams('corp_no', $corpNo)
    ->addParams('cert_type', '0')
    ->addParams('cert_no', 'xxx');
    
//接口调用
$response = $apiClient->execute('com.icbc.xxxxx');
//文件上传
$response = $apiClient->upload('./touxiang.png');
 

//获取结果
if (!$response->isSuccess()) {
    throw new \Exception($response->getError());
}
//var_dump($response->getRspData());
var_dump($response->getData());
```

## 更新日志
2019-08-15 - v1.2.2
- 增加setCorpNo|getCorpNo方法
- 初始化corpNo后不在需要每个请求单独增加corp_no参数

2019-08-14 - v1.2.1
- 修复请求错误时验签的bug
- 请求结束后清空params，可复用ApiClient调用下一请求

2019-08-13 - v1.2.0
- pubKey可选，如传入则针对返回内容验签
- pubKey支持字符串，pem/cer格式文件路径，自动解析
- 增加Exception类
- php版本要求增加到5.6

2019-08-09 - v1.1.1
- 更新说明内容

2019-08-08 - v1.1.0
- 更改目录结构
- 增加Key类，支持密钥文件解析（pem,cer）