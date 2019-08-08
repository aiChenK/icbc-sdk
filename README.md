## icbc-sdk
> 工商银行二类户金融服务接口SDK
实现快速调用，传入私钥即可对请求进行签名

#### Use
```php
$appId  = '';
$corpNo = '';
$apiUrl = '';
$priKey = "-----BEGIN RSA PRIVATE KEY-----
PRIVATE CONTENT
-----END RSA PRIVATE KEY-----";
$pubKey = "-----BEGIN PUBLIC KEY-----
PUBLIC CONTENT
-----END PUBLIC KEY-----";
 
$apiClient = new \Icbc\ApiClient($apiUrl, $appId, $priKey, $pubKey);
 
$apiClient->addParams('corp_no', $corpNo)
    ->addParams('busisno', '1')
    ->addParams('cert_type', '0')
    ->addParams('cert_no', 'xxx');
 
$response = $apiClient->execute('com.icbc.xxxxx');
 
//var_dump($response->getRspData());
var_dump($response->getData());
```

#### Future
- 针对返回内容验签
- 密钥可传入文件路径
- 支持cer