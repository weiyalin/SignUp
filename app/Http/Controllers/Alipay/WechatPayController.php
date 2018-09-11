<?php

namespace App\Http\Controllers\Alipay;

use App\Http\Controllers\Controller;
use App\Model\WeChatPayDatabase;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use EasyWeChat\Payment\Order;
use config\weixinpayconfig;
class WechatPayController  extends Controller
{
    /**微信 支付 下单付款
     * @var null
     */
    protected  $app = null;
    function getPay(Request $request)
    {
        $total_fee    = 1000;                                             //付款金额，单位为分
        $out_trade_no = time();                                        //平台内部订单号
        $phone        = $request->phone;                               //学生电话
        $pay_ways     = $request->pay_ways;                            //学生的支付方式
        $student_id   = $request->student_id;                          //学生学号
        $key          = weixinpayconfig::$key;                         //自己设置的微信key
        $appid        = weixinpayconfig::$appid;                       //微信公众号appid
        $secret       = weixinpayconfig::$secret;                      //AppSecret
        $mch_id       = weixinpayconfig::$mch_id;                      //微信支付商户号
        $notify_url   = weixinpayconfig::$notify_url;                  //异步回调地址，需外网可以访问
        $config       = [
           'debug'    => true,                                         // 调试设置为开启
           'app_id'   => $appid,                                       // AppID
           'secret'   => $secret,                                      // AppSecret
           'token'    => weixinpayconfig::$token,                      // Token
           'aes_key'  => '',                                           // EncodingAESKey，安全模式下请一定要填写！！！
           'log'      => [                                             //日志
               'level'        => weixinpayconfig::$level,
               'permission'   => 0777,
               'file'         => weixinpayconfig::$file,
           ],
           'oauth'   => [                                              //网页授权，获取用户信息
               'scopes'       => ['snsapi_userinfo'],
               'callback'     => weixinpayconfig::$callback,
           ],
           'payment' => [
               'merchant_id'  => $mch_id,                              //微信支付商户号
               'key'          => $key,                                 //自己设置的微信key
               'cert_path'    => weixinpayconfig::$cert_path,          //证书路径设置
               'key_path'     => weixinpayconfig::$key_path,           //密匙文件
               'notify_url'   => $notify_url,                          //异步回调地址，需外网可以访问
           ],
        ];
        $this->app        = new Application($config);
        $payment          = $this->app->payment;
        $insert_id        = WeChatPayDatabase::insertstuorder($student_id,$phone,$out_trade_no,$pay_ways); //把订单信息存入数据
        if($insert_id){                                                //如果订单存入成功
            $attributes        = [
                'body'         => weixinpayconfig::$body,              //支付后的支付订单信息
                'total_fee'    => $total_fee,                          //支付金额，以'分'为单位
                'notify_url'   => $notify_url,                         //回调函数
                'out_trade_no' => $out_trade_no,                       //订单号
                'trade_type'   => weixinpayconfig::$trade_type,        //唤醒接口
                'openid'       => session('openId'),              //从session中取出用户的openid
                'spbill_create_ip'=>$this->getIP()                     //获取用户端口号
            ];
            $order          = new Order($attributes);
            $result         = $payment->prepare($order);               //使用接口完成订单的创建
            $wcPayParams    = [
                "appId"     => $appid,
                "timeStamp" => time(),
                "nonceStr"  => $result['nonce_str'],                   //随机串
                "package"   => "prepay_id=".$result['prepay_id'],      //订单ID
                "signType"  => weixinpayconfig::$signType              //微信签名方式
            ];
            $paySign        = $this->MakeSign($wcPayParams);           //生成签名
            $wcPayParams['paySign'] = $paySign;                        //存签名
            $wcPayParams['payId']   = $insert_id;                      //存数据库中的订单ID
            return $this->responseToJson(1,'下单成功',$wcPayParams);
        }else{
            return $this->responseToJson(0,'下单失败');
        }
    }

    /**修改订单状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function updateOrder(Request $request)
    {
         WeChatPayDatabase::updateOrders($request->id);
    }

    /**数据返回JSON格式
     * @param int $code
     * @param string $msg
     * @param null $paras
     * @return \Illuminate\Http\JsonResponse
     */
    //所有返回前台的数据，封装成JSON数据格式
    function responseToJson($code = 0, $msg = '', $paras = null) {
        $res["code"] = $code;
        $res["msg"] = $msg;
        $res["result"] = $paras;
        return response()->json($res);
    }

    /**生成微信签名
     * @param $sign
     * @return string
     */
    //微信支付签名
    public function MakeSign($sign)
	{
        //签名步骤一：按字典序排序参数
		ksort($sign);
		$string = $this->ToUrlParams($sign);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=def56bbd76f33932dbce862cd87d59de";
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}

    /**解析生成签名时传来的JSON数据
     * @param $sign
     * @return string
     */
    //把微信支付签名，封装拼接算法
    public function ToUrlParams($sign)
	{
		$buff = "";
		foreach ($sign as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		$buff = trim($buff, "&");
		return $buff;
	}

    /**获取终端IP
     * @return array|false|string
     */
    //获取用户的终端IP
    function getIP() {
        if (getenv("HTTP_CLIENT_IP"))          //取得用户的IP代码；
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))//透过代理服务器取得客户端的真实 IP 地址
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))         //正在浏览当前页面用户的IP 地址。
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow";
        return $ip;
    }

    /**
     * @param Request $request
     * @return
     */
    //目的：获取用户的openid,首先获取用户的code,然后用code换取openid。
    public function index(Request $request){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) { //如果是微信浏览器
            if($request->get('code')){   //如果有code参数
                $code=$request->get('code');
                $get_token_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx2fffc402a50e03a5&secret=956397f1970f6d1b114a8ac835bc0a77&code=".$code."&grant_type=authorization_code";
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$get_token_url);
                curl_setopt($ch,CURLOPT_HEADER,0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
                $openid = curl_exec($ch);              //拿code换区opeid
                $Id=json_decode($openid);
                session(['openId' => $Id->openid]);    //存session
                curl_close($ch);
            }else{                        //没有code就先 跳转 然后回调到这里 执行上面的if获取Openid
                return redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2fffc402a50e03a5&redirect_uri=http://www.lishanlei.cn/getopenid&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect");
            }
        }
        return redirect('/');
    }

    /**支付后回调
     * @return mixed
     */
    public function wechatNotify() {
            $response =$this->app->payment->handleNotify(function($notify, $successful){
            $out_no = $notify->out_trade_no;
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = WeChatPayDatabase::sestuordernum($out_no);
            if (!$order) {                             // 如果订单不存在
                return 'Order not exist.';             // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            if ($successful) {                         // 用户是否支付成功
                WeChatPayDatabase::updateorstatus($out_no);
                return true;
            } else {                                   // 用户支付失败
                return false;
            }
            return true;
        });
        return $response;
    }
}