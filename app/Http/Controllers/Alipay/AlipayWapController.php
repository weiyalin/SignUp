<?php
/**
 * Created by Visual Studio Code.
 * User: shanlei
 * Date: 2018/4/26
 * Time: 15:39
 */


namespace App\Http\Controllers\Alipay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\libs\alipay\wappay\buildermodel\AlipayTradeWapPayContentBuilder;
use App\libs\alipay\wappay\service\AlipayTradeService;
// use Yansongda\Pay\Pay;
use DB;

class AlipayWapController extends Controller {


    public function alipayWapPay(Request $request) {
        $phone = $request->phone;
        $student_id = $request->student_id;
        $out_trade_no = 'zan' . uniqid();

        $r = DB::table('order')->insert([
            'phone' => $phone,
            'created_time' => time(),
            'updated_time' => 0,
            'out_trade_no' => $out_trade_no,
            'is_buy' => 0,
            'student_id' => $student_id,
            'is_delete' => 0
        ]);
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

        $result=$payResponse->wapPay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));


    }

    public function alipayReturn() {
        $config = config('alipay');
        $alipaySevice = new AlipayTradeService($config); 
        $arr=$_GET;
        $result = $alipaySevice->check($arr);
        // var_dump($arr);
        if(!$result) {   //这里的对公钥的判定不正确，故加！
            if($alipaySevice->appid == $arr['app_id']) {
                // dd($this->id);
                if($arr['out_trade_no'] != null) {
                    // orders::update_order_state_trade($arr['out_trade_no']);
                    // return redirect('/front/celebration/'.$arr['out_trade_no']);
                    $order = DB::table('order')->where(['out_trade_no'=>$arr['out_trade_no'],'is_buy'=>0])->first();
                    // var_dump($order);
                    if($order){
                        $r = DB::table('order')->where('out_trade_no',$arr['out_trade_no'])->update([
                            'is_buy' => 1,
                            'updated_time' => time()
                        ]);
                        return '<br><br><br><br><br><br><h1 style="text-align:center;">已报名成功，请关注后续通知.<br>一定加QQ群( 619589995 )</h1>';
                    }
                }
                echo '验证成功';
            }else {
                echo '验证失败';
            }
        }
    }

    public function alipayNotify() {
        $arr=$_POST;
        $status = $_POST['trade_status'];
        if($status == 'TRADE_SUCCESS' || $status == 'TRADE_FINISHED') {
            //交易成功
            if($arr['out_trade_no'] != null) {
                // orders::update_order_state_trade($arr['out_trade_no']);
                // return redirect('/front/celebration/'.$arr['out_trade_no']);
                $order = DB::table('order')->where(['out_trade_no'=>$arr['out_trade_no'],'is_buy'=>0])->first();
                if($order){
                    $r = DB::table('order')->where('out_trade_no',$arr['out_trade_no'])->update([
                        'is_buy' => 1,
                        'updated_time' => time()
                    ]);
                }
            }
            echo 'success';
        }
        echo 'fail';

    }
}