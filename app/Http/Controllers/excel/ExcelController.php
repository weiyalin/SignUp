<?php
/**
 * Created by PhpStorm.
 * User: weiyalin
 * Date: 2017/9/9
 * Time: 16:30
 */

namespace App\Http\Controllers\excel;

use App\Http\Controllers\Controller;
use App\Model\StudentDatabase;
use App\Model\OrderDatabase;
use APP\Model\WeChatPayDatabase;
use Excel;
class ExcelController extends Controller
{
    const SEX = ['女','男'];
    const FACULTY = [
        '经济与管理学院',
        '生命科技学院',
        '机电学院',
        '食品学院',
        '动物科技学院',
        '园艺园林学院',
        '资源与环境学院',
        '化学化工学院',
        '文法学院',
        '教育科学学院',
        '艺术学院',
        '服装学院',
        '数学科学学院',
        '外国语学院',
        '体育学院',
        '信息工程学院'
    ];
    const BUY = ['未付款','已付款'];

    public function export(){
        $cellData = [
            ['序号','姓名','性别','学院','专业','班级','学号','电话','QQ','报名时间','是否付款'],
        ];
        $Data = StudentDatabase::seallstudent();
        foreach ($Data as $student ){
              $count = WeChatPayDatabase::wecotstuorder($student->student_id);
              if($student->pay_ways == 0){
                  $student->pay_ways == "微信支付";
              }else{
                  $student->pay_ways == "支付宝支付";
              }
              if($count >= 1)
                    $count = 1;
                    $cellData[] = [
                    $student->id,
                    $student->name,
                    self::SEX[$student->sex],
                    self::FACULTY[$student->faculty],
                    $student->profession,
                    $student->class,
                    $student->student_id,
                    $student->phone,
                    $student->QQ,
                    $student->pay_ways,
                    date('Y-m-d', $student->create_time/1000),
                    self::BUY[$count]
                ];
        }
        Excel::create('三月报名'.date('Y-m-d-h'),function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}