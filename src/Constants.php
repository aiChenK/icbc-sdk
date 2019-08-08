<?php

namespace Icbc;

class Constants
{
    const FN_SIGN_TYPE = "apigw_signtype";

    const FN_APP_ID = "apigw_appid";

    const FN_FORMAT = "apigw_format";

    const FN_METHOD = "apigw_method";

    const FN_TIMESTAMP = "apigw_timestamp";

    const FN_VERSION = "apigw_version";

    const FN_SIGN = "apigw_sign";

    const FN_CERT_ID = "apigw_certid";

    const FN_RSP_DATA = "apigw_rspdata";

    const FN_SIGN_BLOCK = "apigw_signblock";

    const FN_API_NAME = "apigw_apiname";

    const FN_UPLOAD_FILE_API = "com.icbc.eracct.filereach";

    const FN_UPLOAD_FILE_SIGN = "apigw_upload_file_sign";

    const FN_UPLOAD_FILE_NAME = "apigw_upload_file_name";

    const FN_DOWNLOAD_FILE_NAME = "apigw_download_file_name";

    const SIGN_TYPE_RSA = "RSA";

    /** 默认时间格式 **/
    const DATE_TIME_FORMAT = "yyyy-MM-dd HH:mm:ss";
    /** UTF-8字符集 **/
    const CHARSET_UTF8 = "UTF-8";
    /** GBK字符集 **/
    const CHARSET_GBK = "GBK";
    /** JSON 应格式 */
    const FORMAT_JSON = "json";
    /** XML 应格式 */
    const FORMAT_XML = "xml";

    const CONNECT_TIMEOUT = 3000;

    const READ_TIMEOUT = 30000;

    /** RSA最大加密明文大小  */
    const MAX_ENCRYPT_BLOCK = 117;

    /** RSA最大解密密文大小   */
    const MAX_DECRYPT_BLOCK = 128;

    const API_VERSION = "1.0";

    const ALGORITHM_SHA1RSA = "SHA1withRSA";
    const API_FUNC_PREFIX = "/func";
    const API_UPLOAD_PREFIX = "/upload";
    const API_DOWNLOAD_PREFIX = "/download";
    const UPLOADFILE_MAX_SIZE = 2 * 1024 * 1024;
    const DOWNLOADFILE_HEAD_BUFFER = 1024;
}