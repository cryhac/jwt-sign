<?php
/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/5/13
 * Time: 下午11:02
 */

namespace Will\Sign;

/***
 * 签名验证类
 * Class SignService
 * @package Sign
 */
class Sign
{
    
    /**
     * 签名
     * @return string
     */
    public static function sign()
    {
        return Jwt::createSignToken(Help::genParams());
    }
    
    /**
     * 验签
     * @return array|bool
     */
    public static function verify()
    {
        $payload = Jwt::parseToken();
        
        return Help::genParams() == $payload['sub'];
    }
}