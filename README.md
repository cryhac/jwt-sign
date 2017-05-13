JWT-SIGN
========
jwt-sign是解决服务器调用的签名问题。签名(client)和验签(server)(通过携带头信息：authorization:bearer {jwt-token})

安装
----



详细部署说明
----



__1. composer install__

切换到PHPWebIM项目目录，执行指令composer install

```shell
composer require will/jwt-sign
```

__2. 修改配置__

* 配置`Config/sign.php`中的服务器API_KEY,API_SECRET信息。可通过修改.env文件来进行修改


```php
return array(
    'SIGN_API_KEY' => env("SIGN_API_KEY", 'ad01682126d627f08c9c8dbb9c273f1d'), //api-key
    'SIGN_API_SECRET' => env("SIGN_API_SECRET", 'a4915ac4bb1fc1fdba4f836a3b9ce307'), //api-secret
);
```

3、Cert是证书文件,请保持文件名不变
```shell
cd vendor/will/jwt-sign/src/Will/Cert  #切换到对应的目录直接生成,或者生成以后拷贝
openssl genrsa -out prvtkey.pem 1024 #生成私钥
openssl rsa -in prvtkey.pem -out pubkey.key -pubout #导出公钥
```
4、使用方法,请使用命名空间。为了描述方便,实例写路径形式
```php
$sign = \Will\Sign\Sign::sign();//生成签名
var_dump($sign);
$_SERVER['HTTP_AUTHORIZATION'] = "bearer " . $sign;//通过头信息提交 authorization:bearer {jwt-token}
$ret = \Will\Sign\Sign::verify();//验证签名
var_dump($ret);//验证结果

```