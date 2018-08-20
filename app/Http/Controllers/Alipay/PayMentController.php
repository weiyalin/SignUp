<?php
/**
 * Created by PhpStorm.
 * User: ztfxhld520
 * Date: 2018/8/13
 * Time: 21:19
 */

namespace App\Http\Controllers\Alipay;

use App\Http\Controllers\Alipay\WechatPayController;   // 1.引入支付类文件
use App\Model\WeChatPayDatabase;
use Illuminate\Http\Request;

class PayMentController
{
   public function getPays(Request $request)
   {

      $out_trade_no   = 'wechat' . uniqid();             //生成内部订单号
//      $addquttradenum = WeChatPayDatabase::insertstuorder($phone,$student_id,$out_trade_no);  //把订单信息存入数据库                              //把订单信息绑定学生添加到数据库
//      $order_id = I('order_id');
      //  2.判断参数是否为空
      if(!empty($order_id)){
      //  3.根据订单ID查询订单是否存在
          $order = WeChatPayDatabase::sestuordernum();
          if($order){  //订单存在
          //  4.判断该笔订单是否已经存在支付，如已支付则返回支付失败并给出相应提示
              if($order['is_pay'] == '1'){
                  exit(json_encode(array('status'=>'205','msg'=>'该订单已支付，请勿重复提交')));
              }
              $bodys = '订单：' . $order['order_sn'] . '支付';
          //  5.调用支付类中封装的支付方法并对应传参
              $result = WechatPayController::getCode($order,$bodys);
          //  6.当return_code和result_code均为SUCCESS，代表下单成功，将支付参数返回
              if($result['return_code'] == 'SUCCESS'){
                  if($result['result_code'] == 'SUCCESS'){
                      exit(json_encode(array('status'=>'0','msg'=>'下单成功，请支付！','result'=>$result['mweb_url'])));
                  }elseif ($result['result_code'] == 'FAIL'){
                      exit(json_encode(array('status'=>'-201','msg'=>$result['err_code_des'])));
                  }
              }else{
                  exit(json_encode(array('status'=>'-1','msg'=>'未知错误，请稍候重试')));
              }
          }else{
               //报错： 数据不存在
              exit(json_encode(array('status'=>'-200','msg'=>'订单不存在，请核实后再提交')));
          }
      }else{
           //报错： 缺少参数
          exit(json_encode(array('status'=>'-204','msg'=>'参数缺失，请核实')));
      }
   }

}