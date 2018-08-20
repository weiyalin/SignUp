<?php

namespace App\Http\Controllers\Alipay;

use App\Http\Controllers\Controller;
//use App\Http\Controllers\Alipay\PayMentController;
class WechatTokenController extends Controller
{
    public static function traceHttp()
    {
        date_default_timezone_set("Asia/Shanghai");
         //定义TOKEN常量，这里的"weixin"就是在公众号里配置的TOKEN
        define("TOKEN", "weixin");
        $content = date('Y-m-d H:i:s')."\n\rremote_ip：".$_SERVER["REMOTE_ADDR"].
            "\n\r".$_SERVER["QUERY_STRING"]."\n\r\n\r";
        $max_size = 1000;
        $log_filename = "./query.xml";
        if (file_exists($log_filename) and (abs(filesize($log_filename))) > $max_size){
            unlink($log_filename);                                      //删除文件
        }
        file_put_contents($log_filename, $content, FILE_APPEND);  //把获得的信息写入文件
        if (isset($_GET["echostr"])){
            error_log($_GET["echostr"],3,"my-errors.log");
            echo $_GET["echostr"];
            exit;
        }
    }

    /**
     * 用于验证是否是微信服务器发来的消息
     * @return bool
     */
     function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature){
            return true;
        }else {
            return false;
        }
    }
}