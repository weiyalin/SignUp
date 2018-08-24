<?php
namespace App\Http\Controllers\Alipay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\alipay\wappay\buildermodel\AlipayTradeWapPayContentBuilder;
use App\libs\alipay\wappay\service\AlipayTradeService;
use App\Model\WeChatPayDatabase;
class AlipayWapController extends Controller {

    //学生支付，调用支付宝接口
    public function alipayWapPay(Request $request){
        $phone = $request->phone;
        $student_id = $request->student_id;
        $pay_ways   = $request->pay_ways;
        $out_trade_no = 'zan' . uniqid();
        WeChatPayDatabase::insertstuorder($student_id,$phone,$out_trade_no,$pay_ways);
        $subject = '报名费';
        $total_amount = 0.01;
        $body = '三月报名费用';
        $timeout_express="1m";
        $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);
        $payResponse = new AlipayTradeService();
        $payResponse->wapPay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));
    }

    //判断是否支付成功
    public function alipayReturn(){
        $arr=$_GET;
        $config = config('alipay');
        $alipaySevice = new AlipayTradeService($config);  //
        $result = $alipaySevice->check($arr);
        if(!$result){                                     //这里的对公钥的判定不正确，故加！
            if($alipaySevice->appid == $arr['app_id']){   //
                $out_trade_no = $arr['out_trade_no'];
                if($out_trade_no != null){
                    $order = WeChatPayDatabase::acordoutranse($out_trade_no);
                    if($order){
                        WeChatPayDatabase::updateorstatus($out_trade_no);
                        return  redirect('http://lishanlei.cn/#/select/支付成功，后续关注通知。一定要加群哦！');
                    }
                }
                return  redirect('http://lishanlei.cn/#/select/支付成功');
            }else{
                return  redirect('http://lishanlei.cn/#/select/支付失败');
            }
        }
    }
    //
    public function alipayNotify(){
        $arr=$_POST;
        $status = $_POST['trade_status'];                    //
        if($status == 'TRADE_SUCCESS' || $status == 'TRADE_FINISHED'){ //
            //交易成功
            $out_trade_no = $arr['out_trade_no'];
            if($out_trade_no != null){
                $order = WeChatPayDatabase::acordoutranse($out_trade_no);
                if($order){
                    WeChatPayDatabase::updateorstatus($out_trade_no);
                }
            }
            return  redirect('http://lishanlei.cn/#/select');
        }
        return  redirect('http://lishanlei.cn/#/select');
    }
}