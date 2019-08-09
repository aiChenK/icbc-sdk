# icbc-sdk
> 工商银行二类户金融服务接口SDK
实现快速调用，传入私钥即可对请求进行签名

## 运行环境
- PHP 5.5+
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
$priKey = "./pri.pem"; //或私钥内容
$pubKey = "./pub.pem";
 
//初始化
$apiClient = new \Icbc\ApiClient($apiUrl, $appId, $priKey, $pubKey);
 
$apiClient->addParams('corp_no', $corpNo)
    ->addParams('busisno', '1')
    ->addParams('cert_type', '0')
    ->addParams('cert_no', 'xxx');
    
//接口调用
$response = $apiClient->execute('com.icbc.xxxxx');
//文件上传
$response = $apiClient->upload('./touxiang.png');
 
//获取结果
//var_dump($response->getRspData());
var_dump($response->getData());
```

## 更新日志
2019-08-09 - v1.1.1
- 更新说明内容

2019-08-08 - v1.1.0
- 更改目录结构
- 增加Key类，支持密钥文件解析（pem,cer）

## 待增加内容
- 针对返回内容验签