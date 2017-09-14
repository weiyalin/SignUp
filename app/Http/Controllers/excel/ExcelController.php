<?php
/**
 * Created by PhpStorm.
 * User: weiyalin
 * Date: 2017/9/9
 * Time: 16:30
 */

namespace App\Http\Controllers\excel;

use App\Http\Controllers\Controller;
use Excel;
use DB;

class ExcelController extends Controller
{
    public function export(){
        $cellData = [
            ['序号','姓名','班级','学号','电话','方向','报名时间'],
        ];
        $Data = DB::table("student")->where('radio',1)->get();
        foreach ($Data as $student ){
            $dir = '开发';
            $cellData[] = [
                $student->id,
                $student->name,
                $student->grade,
                $student->student_id,
                $student->phone_num,
                $dir,
                date('Y-m-d', $student->create_time/1000),
            ];
        }
        Excel::create('开发新生报名信息',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    public function export2(){
        $cellData = [
            ['序号','姓名','班级','学号','电话','方向','报名时间'],
        ];
        $Data = DB::table("student")->where('radio',2)->get();
        foreach ($Data as $student ){
            $dir = '美工';
            $cellData[] = [
                $student->id,
                $student->name,
                $student->grade,
                $student->student_id,
                $student->phone_num,
                $dir,
                date('Y-m-d', $student->create_time/1000),
            ];
        }
        Excel::create('美工新生报名信息',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}