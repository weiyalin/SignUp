<?php
/**
 * Created by PhpStorm.
 * User: ztfxhld520
 * Date: 2018/8/10
 * Time: 15:26
 */

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDatabase  extends Model
{
    /**支付宝支付，对数据库的操作
     * @param $student_id
     * @return mixed
     */
    //统计所有已经订单的学生人数
    public static function countstuorder($student_id)
    {
        $countorderstu = DB::table('order')->where('student_id',$student_id)->where('is_buy',1)->count();
        return $countorderstu;
    }
    //根据学生ID查学生是否已经订单
    public static function acrstuidseorder($student_id)
    {
        $count = DB::table('order')->where('student_id',$student_id)->where('is_buy',1)->count();
        return $count;
    }
    //添加新的交费学生信息
    public static function insertsigninma($phone,$out_trade_no,$student_id)
    {
        $time = time();
        DB::table('order')->insert([
            'phone' => $phone,
            'created_time' => $time,
            'updated_time' => 0,
            'out_trade_no' => $out_trade_no,
            'is_buy' => 0,
            'student_id' => $student_id,
            'is_delete' => 0
        ]);
    }
    //根据“ out_trade_no ”，去查是否有第一个未付款的
    public static function acordoutranse($arr)
    {
        $count = DB::table('order')->where(['out_trade_no'=>$arr['out_trade_no'],'is_buy'=>0])->first();
        return $count;
    }
    //根据 “ out_trade_no ”，去更新=>学生已交费
    public static function updatestupayed($arr)
    {
        DB::table('order')->where('out_trade_no',$arr['out_trade_no'])->update([
            'is_buy' => 1,
            'updated_time' => time()
        ]);
    }
}