<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\web\Response;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\Random;
use yii\web\BadRequestHttpException;

/**
 * 模拟阿里云的请求，和接收到数据的响应
 *
 * 使用说明：
 *
 * 新建个demo项目，把当前controller放进去
 * composer加载密码库：
 *  composer require phpseclib/phpseclib:~3.0
 *
 * 请求
 * 请求index接口，拿到阿里云的发起请求的参数, 比如：
 * curl -X POST -v http://192.168.1.54:8180/index -d 'aaaaa'
 *
 */
class IndexController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 生成阿里云的请求参数
     * 去请求中心的接口
     *
     * @return string
     */
    public function actionIndex()
    {
        // 测试参数
        $params = [
            'endpoint' => 'all_miners1',
            'date' => '2022-02-21',
        ];

        // AES生成秘钥并加密
        $cipher = new AES('cbc');
        $aesKey = Random::string(16);
        $iv = Random::string(16);
        $cipher->setIV($iv);
        $cipher->setKey($aesKey);
        $ciphertext = $cipher->encrypt(json_encode($params));

        // RSA加密AES的秘钥key
        $encryptKeyPath = Yii::getAlias('@common/keys/cncert-enc-public.pem');
        $encryptKey = PublicKeyLoader::load(file_get_contents($encryptKeyPath));
        $aesKeyEncrypted = $encryptKey->withPadding(RSA::ENCRYPTION_OAEP)->withHash('sha256')->withMGFHash('sha256')->encrypt($aesKey);

        // 生成签名
        $signKeyPath = Yii::getAlias('@common/keys/aliyun-sign-private.pem');
        $signKey = PublicKeyLoader::load(file_get_contents($signKeyPath));
        $sign = $signKey->withPadding(RSA::SIGNATURE_PKCS1)->sign($aesKeyEncrypted.$iv.$ciphertext);

        // 响应头添加签名
        Yii::$app->getResponse()->headers->add('Request-Sign', base64_encode($sign));

        return base64_encode($aesKeyEncrypted.$iv.$ciphertext);
    }

    /**
     * 使用中心返回的数据
     * 模拟响应给阿里云
     *
     * @return array
     */
    public function actionIndex2()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        // 验证签名
        $sign = Yii::$app->getRequest()->headers->get('Request-Sign');
        $ciphertext = Yii::$app->getRequest()->getRawBody();

        $signVerifyKeyPath = Yii::getAlias('@common/keys/cncert-sign-public.pem');
        $signVerifyKey = PublicKeyLoader::load(file_get_contents($signVerifyKeyPath));
        $isSignValid = $signVerifyKey->withPadding(RSA::SIGNATURE_PKCS1)->verify(base64_decode($ciphertext), base64_decode($sign));
        if (!$isSignValid) {
            throw new BadRequestHttpException('验签失败');
        }

        // 拆分字符串
        $aesEncryptedKey = substr(base64_decode($ciphertext), 0, 256);
        $cipherBody = substr(base64_decode($ciphertext), 256);
        $iv = substr($cipherBody, 0, 16);
        $cipherParam = substr($cipherBody, 16);

        // AES的key使用RSA解密
        $decryptKeyPath = Yii::getAlias('@common/keys/aliyun-enc-private.pem');
        $decryptKey = PublicKeyLoader::load(file_get_contents($decryptKeyPath));
        $aesKey = $decryptKey->withPadding(RSA::ENCRYPTION_OAEP)->withHash('sha256')->withMGFHash('sha256')->decrypt($aesEncryptedKey);

        // AES解密
        $cipher = new AES('cbc');
        $cipher->setKey($aesKey);
        $cipher->setIv($iv);

        return json_decode($cipher->decrypt($cipherParam), true);
    }

}
