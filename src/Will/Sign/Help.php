<?php

/**
 * Created by PhpStorm.
 * User: will
 * Date: 2017/5/13
 * Time: 下午11:02
 */

namespace Will\Sign;

trait Help
{
    private static $aConfig;
    
    public static function libraryPath()
    {
        return dirname(dirname(__FILE__));
    }
    
    /**
     * basePath
     * @param string $path
     * @return string
     */
    public static function basePath($path = '')
    {
        return self::libraryPath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
    
    /**
     * 查询某个字符串以某个字符串开始
     * @param $haystack
     * @param $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 载入配置文件
     * @return mixed
     */
    public static function getSingConfig()
    {
        if (is_null(self::$aConfig)) {
            self::$aConfig = require self::basePath("Config") . "/sign.php";
        }
        
        return self::$aConfig;
    }
    
    /**
     * 签名的参数
     * @return string
     */
    public static function genParams()
    {
        $config = self::getSingConfig();
        $api_key = $config['SIGN_API_KEY'];
        $api_secret = $config['SIGN_API_SECRET'];
        
        return md5($api_key . $api_secret);
    }
}