<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/5/13
 * Time: 下午11:02
 */

namespace Will\Sign;

use Namshi\JOSE\SimpleJWS;

class Jwt
{
    use Help;
    protected static $token;
    protected static $blacklistEnabled = true;
    const ALG = "RS256";
    
    /**
     * 生成token
     * @param $params
     * @return string
     */
    public static function createSignToken($params)
    {
        $jwt_expire = 60;//(在用作签名中,请尽量不用太长时间)
        $time = time();
        $token = array(
            "sub" => $params,
            "exp" => $time + $jwt_expire,//有效期为3600秒
        );
        $jws = new SimpleJWS(array(
            'alg' => self::ALG
        ));
        $jws->setPayload($token);
        $privateKey = openssl_pkey_get_private(self::getPrivateKey());
        $jws->sign($privateKey);
        
        return $jws->getTokenString();
    }
    
    /**
     * 验证用户传来的信息(一般是头信息 Authorization：Bearer $token)
     * @param $jwt
     * @return array|bool
     */
    public static function verifyToken($jwt)
    {
        $jws = SimpleJWS::load($jwt);
        if ($jws->isValid(self::getPublicKey(), self::ALG)) {
            $payload = $jws->getPayload();
            
            return $payload;
        }
        
        return false;
    }
    
    
    /**
     * 解析token
     * @param string $method
     * @param string $header
     * @return array|bool
     * @throws JWTException
     */
    public static function parseToken($method = 'bearer', $header = 'authorization')
    {
        if (!$token = self::parseAuthHeader($header, $method)) {
            return false;
        }
        
        return self::verifyToken($token);
    }
    
    /**
     * 解析头部的token
     * @param string $header
     * @param string $method
     * @return bool|string
     */
    public static function parseAuthHeader($header = 'authorization', $method = 'bearer')
    {
        
        $header = $_SERVER["HTTP_" . strtoupper($header)];
        
        if (!self::startsWith(strtolower($header), $method)) {
            return false;
        }
        
        return trim(str_ireplace($method, '', $header));
    }
    
    /**
     * 获取私钥内容,一般放到git的忽略文件中,防止泄密
     * @return string
     */
    private static function getPublicKey()
    {
        $path = realpath(self::basePath("Cert"));
        
        return "file://{$path}/pubkey.key";
    }
    
    /**
     * 获取公钥数据
     * @return string
     */
    private static function getPrivateKey()
    {
        $path = realpath(self::basePath("Cert"));
        
        return "file://{$path}/prvtkey.pem";
    }
    
    
}