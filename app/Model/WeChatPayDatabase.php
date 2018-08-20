<?php
/**
 * Created by PhpStorm.
 * User: ztfxhld520
 * Date: 2018/8/14
 * Time: 10:20
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WeChatPayDatabase  extends Model
{
    /**  微信支付，对数据库的操作
     * @param $student_id
     * @param $phone
     * @param $out_trade_no
     * @return mixed
     */
   //把订单信息存入数据库
    public static function insertstuorder($student_id,$phone,$out_trade_no)
    {
        $result = DB::table('wechatpay')
            ->insertGetId([
              'student_id'   => $student_id,
              'phone'        => $phone,
              'out_trade_no' => $out_trade_no,
              'is_pay'       => '0',
              'created_time' => time()
                    ]);
        return $result;
    }
   //查询微信支付的订单号
    public static function sestuordernum($out_trade_no)
    {
        $order = DB::table('wechatpay')->where('out_trade_no',$out_trade_no)->first();
        return $order;
    }
   //修改学生的支付状态
    public static function updateorstatus($out_trade_no)
    {
        DB::table('wechatpay')->where('out_trade_no',$out_trade_no)->update([
            'status'=>1
        ]);
    }
    //根据 “订单ID” ，去更新=>学生已交费
    public static function updateOrders($oederid)
    {
        $result = DB::table('wechatpay')
            ->where('id',$oederid)
            ->update([
                'is_pay' => 1,
                'updated_time' => time()
            ]);
        return $result;
    }
}