<?php

namespace config;

class weixinpayconfig {

    /**微信支付所需参数
     * @var string
     */
      public static $appid          = 'wx2fffc402a50e03a5';               //微信公众号appid
      public static $secret         = '956397f1970f6d1b114a8ac835bc0a77'; //AppSecret
      public static $mch_id         = '1439601702';                       //微信支付商户号
      public static $key            = 'def56bbd76f33932dbce862cd87d59de'; //自己设置的微信key
      public static $notify_url     = 'http://www.lishanlei.cn/wechatpay/wexinotify';//异步回调地址，需外网可以访问
      public static $token          = 'weixin';                           // Token
      //config中的log参数
      public static $level          = 'debug';                            //
      public static $file           = 'storage/logs/easywechat.log';      //
      //config中的oauth的参数
      public static $callback       = '/examples/oauth_callback.php';     //
      //config中的payment的参数
      public static $cert_path      = '/var/www/SignUp/public/path/apiclient_cert.pem';//证书路径设置,绝对路径
      public static $key_path       = '/var/www/SignUp/public/path/apiclient_key.pem'; //密匙文件,绝对路径
      //attributes中的参数
      public static $body           = '三月报名费';                        //支付后的支付订单信息
      public static $trade_type     = 'JSAPI';                            //唤醒方式
      //$wcPayParams中的参数
      public static $signType       =  'MD5';                             //微信签名方式


}

