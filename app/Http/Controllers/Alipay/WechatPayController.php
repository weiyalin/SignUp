<?php
/**
 * Created by PhpStorm.
 * User: ztfxhld520
 * Date: 2018/8/13
 * Time: 12:32
 */

namespace App\Http\Controllers\Alipay;

use App\Http\Controllers\Controller;
use App\Model\WeChatPayDatabase;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use EasyWeChat\Payment\Order;
class WechatPayController  extends Controller
{
    /**微信 支付 下单付款
     * @var null
     */
    protected  $app = null;
    function getPay(Request $request)
    {
        $appid       = 'wx2fffc402a50e03a5';
        $secret      = '956397f1970f6d1b114a8ac835bc0a77';  //AppSecret
        $phone          = $request->phone;
        $student_id     = $request->student_id;
//      $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";       //微信传参地址

    //    1. 获取调用统一下单接口所需要的接口
                            //微信公众号appid
       $mch_id           = '1439601702';                              //微信支付商户号
       $key              = 'def56bbd76f33932dbce862cd87d59de';        //自己设置的微信key
       $out_trade_no     = time();                                    //平台内部订单号
//       $nonce_str        = MD5($out_trade_no);                        //随机字符串
//       $body             = '三月报名费';                               //付款内容
       $total_fee        = 1;                                         //付款金额，单位为分
//       $spbill_create_ip = $this->getIP();                            //获取用户IP
//       $attach           = 'weixin-h5pay';                            //附加数据（自定义：在支付通知中原样返回）
       $notify_url       = 'http://www.lishanlei.cn/wechatpay/wexinotify';     //异步回调地址，需外网可以访问
//       $trade_type       = 'MWEB';                                    //交易类型，微信H5支付时固定为MWEB
//       $scene_info       = '{"h5_info":{"type":"Wap","wap_url":"http://juankuan.marchsoft.cn","wap_name":"三月报名"}}';//场景信息
    //    2.将参数按照key = value的格式，并按照参数名ASCII字典排序生成字符串
//       $signA            = "appid=$appid&attach=$attach&body=$body&mch_id=$mch_id&nonce_str=$nonce_str";
    //    3.拼接字符串
//       $strSignTmp       = $signA."&key=$key";
    //    4.MD5加密后转化成大写
//       $sign             = strtoupper(MD5($strSignTmp));
       $config           = [
           'debug'   => true,
           'app_id'  => $appid,         // AppID
           'secret'  => $secret,     // AppSecret
           'token'   => 'weixin',          // Token
           'aes_key' => '',                    // EncodingAESKey，安全模式下请一定要填写！！！
           'log' => [
               'level'      => 'debug',
               'permission' => 0777,
               'file'       => 'storage/logs/easywechat.log',
           ],
           'oauth' => [
               'scopes'   => ['snsapi_userinfo'],
               'callback' => '/examples/oauth_callback.php',
           ],
           'payment' => [
               'merchant_id'        => $mch_id,
               'key'                => $key,
               'cert_path'          => '/var/www/SignUp/public/path/apiclient_cert.pem', // XXX: 绝对路径！！！！
               'key_path'           => '/var/www/SignUp/public/path/apiclient_key.pem',      // XXX: 绝对路径！！！！
               'notify_url'         => $notify_url,
           ],
       ];
       $this->app        = new Application($config);
       $payment          = $this->app->payment;
//       $insert_id        = WeChatPayDatabase::insertstuorder($student_id,$phone,$out_trade_no); //把订单信息存入数据
        $insert_id = 1;
        if($insert_id){
            $attributes        = [
                'body'         => '三月报名费',
                'total_fee'    => $total_fee,
                'notify_url'   => $notify_url,
                'out_trade_no' => $out_trade_no,
                'trade_type'   => 'JSAPI',
                'openid'    => session('openId'),
                'spbill_create_ip'=>$this->getIP()
            ];
            $order          = new Order($attributes);
            $result         = $payment->prepare($order);   //使用接口完成订单的创建
            $wcPayParams    = [
                "appId"     => $appid,
                "timeStamp" => time(),
                "nonceStr"  => $result['nonce_str'],   //随机串
                // 通过统一下单接口获取
                "package"   => "prepay_id=".$result['prepay_id'],
                "signType"  => 'MD5'                  //微信签名方式
            ];
            $paySign        = $this->MakeSign($wcPayParams);
            $wcPayParams['paySign'] = $paySign;
            $wcPayParams['payId']   = $insert_id;
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
        $result = WeChatPayDatabase::updateOrders($request->id);
        return $this->responseToJson(1,'更新结果',$result);
    }

    /**数据返回JSON格式
     * @param int $code
     * @param string $msg
     * @param null $paras
     * @return \Illuminate\Http\JsonResponse
     */
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
                $openid = curl_exec($ch);              //拿code换区opeid存session
                $Id=json_decode($openid);
                session(['openId' => $Id->openid]);
                curl_close($ch);
            }else{  //没有code就先 跳转 然后回调到这里 执行上面的if获取Openid
                return redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2fffc402a50e03a5&redirect_uri=http://www.lishanlei.cn/getopenid&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect");
            }
        }
        return view('index');
    }

    /**支付后回调
     * @return mixed
     */
    public function wechatNotify() {

        $response =$this->app->payment->handleNotify(function($notify, $successful){
            $out_no = $notify->out_trade_no;
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = WeChatPayDatabase::sestuordernum($out_no);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 用户是否支付成功
            if ($successful) {
                WeChatPayDatabase::updateorstatus($out_no);
                return true;
            } else {                                          // 用户支付失败
                return false;
            }
            return true;
        });
        return $response;
    }
    // 5.拼接成所需XML格式
//       $post_data        = "<xml>
//                  <appid>$appid</appid>
//                  <attach>$attach</attach>
//                  <body>$body</body>
//                  <mch_id>$mch_id</mch_id>
//                  <nonce_str>$nonce_str</nonce_str>
//                  <notify_url>$notify_url</notify_url>
//                  <out_trade_no>$out_trade_no</out_trade_no>
//                  <spbill_create_ip>$spbill_create_ip</spbill_create_ip>
//                  <total_fee>$total_fee</total_fee>
//                  <trade_type>$trade_type</trade_type>
//                  <scene_info>$scene_info</scene_info>
//                  <sign>$sign</sign>
//                             </xml>";
//        //6.以POST方式向微信传参，并获取微信返回的支付参数
//       $datexml   = $this->httpRequest($url,'POST',$post_data);
//        //7.将微信返回的数据转化成数组
//       $objectxml = (array)simplexml_load_string($datexml,  'SimpleXMLElement', LIBXML_NOCDATA);
//       return $objectxml;
    //        $url         = 'http://cfe63fdc.ngrok.io/wexinotify';
//if(!isset($_GET['code'])) {
//           $ch = curl_init();
//           $str ='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$url.'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
//           curl_setopt($ch, CURLOPT_URL, $str);
//           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//           curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//           curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
//           curl_exec($ch);
//        }
//        LOG::info($request->path());
//        $code = $_GET['code'];
//        return $code;
//        $str = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
//        curl_setopt($ch, CURLOPT_URL, $str);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
//        curl_close($ch);
//        $respose = curl_exec($ch);
//         return json_encode($respose);
    /**
     * CURL请求
     * @param $url 请求url地址
     * @param $method 请求方法 get post
     * @param null $postfields post数据数组
     * @param array $headers 请求header信息
     * @param bool|false $debug 调试开启 默认false
     * @return mixed
     */
//    function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
//        $method = strtoupper($method);                  //把字符全部转换成大写
//        $ci = curl_init();                              //初始化一个cURL会话
//        /* Curl settings */
//        curl_setopt($ci,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0); //设置curl使用的HTTP协议
//        curl_setopt($ci,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
//        curl_setopt($ci,CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
//        curl_setopt($ci,CURLOPT_TIMEOUT,7); /* 设置cURL允许执行的最长秒数 */
//        curl_setopt($ci,CURLOPT_RETURNTRANSFER,true); //如果成功，只将结果返回，不会自动输出任何内容
//        switch ($method) {
//            case "POST":
//                curl_setopt($ci,CURLOPT_POST,true); //让PHP去做一个正规的HTTP POST
//                if (!empty($postfields)) {
//                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
//                    curl_setopt($ci,CURLOPT_POSTFIELDS,$tmpdatastr); //传递一个作为HTTP “POST”操作的所有数据的字符串
//                }
//                break;
//            default:
//                curl_setopt($ci,CURLOPT_CUSTOMREQUEST,$method); /* //设置请求方式 */
//                break;
//        }
//        $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;  //匹配路由，判断是否是HTTP请求
//        curl_setopt($ci, CURLOPT_URL, $url);                      //取回URL
//        if($ssl){
//            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
//            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
//        }
//        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1); //启用时会将服务器服务器返回的“Location:”放在header中递归的返回给服务器
//        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
//        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);//设置一个header中传输内容的数组。
//        curl_setopt($ci, CURLINFO_HEADER_OUT, true);//发送请求的字符串
//        $response = curl_exec($ci);//执行一个cURL会话
//        $requestinfo = curl_getinfo($ci);//获取cURL连接资源句柄的信息
//        if ($debug) {
//            echo "=====post data======\r\n";
//            var_dump($postfields);
//            echo "=====info===== \r\n";
//            print_r($requestinfo);
//            echo "=====response=====\r\n";
//            print_r($response);
//        }
//        curl_close($ci);   //关闭句柄
//        return $response;
//    }


}