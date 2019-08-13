<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2019-08-08
 * Time: 16:15
 */

namespace Icbc\Core;

use Icbc\Exception\KeyException;

class Key
{
    const PRIVATE_KEY_BEGIN = '-----BEGIN RSA PRIVATE KEY-----' . PHP_EOL;
    const PRIVATE_KEY_END   = '-----END RSA PRIVATE KEY-----' . PHP_EOL;
    const PUBLIC_KEY_BEGIN  = '-----BEGIN PUBLIC KEY-----' . PHP_EOL;
    const PUBLIC_KEY_END    = '-----END PUBLIC KEY-----' . PHP_EOL;
    const X509_KEY_BEGIN    = '-----BEGIN CERTIFICATE-----' . PHP_EOL;
    const X509_KEY_END      = '-----END CERTIFICATE-----' . PHP_EOL;

    /**
     * 解析私钥
     *
     * @param $key
     * @return bool|resource
     * @throws \Exception
     */
    public static function getPrivateKey($key)
    {
        $key = self::parseKey($key, self::PRIVATE_KEY_BEGIN, self::PRIVATE_KEY_END);
        return is_resource($key) ? $key : openssl_get_privatekey($key);
    }

    /**
     * 解析公钥
     *
     * @param $key
     * @return resource
     * @throws \Exception
     */
    public static function getPublicKey($key)
    {
        $key = self::parseKey($key, self::PRIVATE_KEY_BEGIN, self::PRIVATE_KEY_END);
        return is_resource($key) ? $key : openssl_get_publickey($key);
    }

    /**
     * 解析密钥
     *
     * @param $key
     * @param $begin
     * @param $end
     * @return bool|resource|string
     * @throws \Exception
     */
    private static function parseKey($key, $begin, $end)
    {
        if (is_file($key)) {
            return self::getKeyByFile($key);
        }
        if (strpos($key, '-----') === 0) {
            return $key;
        }
        return $begin . chunk_split(str_replace(["\r\n", "\n"], ['', ''], trim($key)), 64, PHP_EOL) . $end;
    }

    /**
     * 获取密钥字符串/资源
     *
     * @param $file
     * @return bool|resource|string
     * @throws \Exception
     */
    private static function getKeyByFile($file)
    {
        $info = pathinfo($file);
        switch ($info['extension']) {
            case 'pem':
                return file_get_contents($file);
            case 'cer':
                $cer = file_get_contents($file);
                if (strpos($cer, '-----') !== 0) {
                    $cer = self::X509_KEY_BEGIN .
                        chunk_split(base64_encode($cer), 64, PHP_EOL) .
                        self::X509_KEY_END;
                }
                return openssl_x509_read($cer);
            default:
                throw new KeyException('Analytic method to the key can not be supported now');
        }
    }

    private static function isBase64($str)
    {
        return $str == base64_encode(base64_decode($str));
    }
}