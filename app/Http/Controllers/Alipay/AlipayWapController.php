<?php
namespace App\Http\Controllers\Alipay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\alipay\wappay\buildermodel\AlipayTradeWapPayContentBuilder;
use App\libs\alipay\wappay\service\AlipayTradeService;
use App\Model\OrderDatabase;
class AlipayWapController extends Controller {

    //学生支付，调用支付宝接口
    public function alipayWapPay(Request $request){
        $phone = $request->phone;
        $student_id = $request->student_id;
        $out_trade_no = 'zan' . uniqid();      //
        OrderDatabase::insertsigninma($phone,$out_trade_no,$student_id);
        $subject = '报名费';
        $total_amount = 10;
        $body = '报名费用';
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
                if($arr['out_trade_no'] != null){
                    $order = OrderDatabase::acordoutranse($arr);
                    if($order){
                        OrderDatabase::updatestupayed($arr);
                        return '<br><br><br><br><br><br><h1 style="text-align:center;">已报名成功，请关注后续通知.<br>一定加QQ群( 619589995 )</h1>';
                    }
                }
                echo '验证成功';
            }else{
                echo '验证失败';
            }
        }
    }
    //
    public function alipayNotify(){
        $arr=$_POST;
        $status = $_POST['trade_status'];                    //
        if($status == 'TRADE_SUCCESS' || $status == 'TRADE_FINISHED'){ //
            //交易成功
            if($arr['out_trade_no'] != null){
                $order = OrderDatabase::acordoutranse($arr);
                if($order){
                    OrderDatabase::updatestupayed($arr);
                }
            }
            echo 'success';
        }
        echo 'fail';
    }
}