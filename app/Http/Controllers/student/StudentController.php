<?php
/**
 * Created by PhpStorm.
 * User: weiyalin
 * Date: 2017/9/8
 * Time: 11:03
 */

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

use  App\Http\Controllers\sms\SmsController;

class StudentController extends Controller
{
    public function sign(Request $request){

        //return json_encode(['code' => 1, 'msg' => '报名已截止！']);
        $input = $request->all();
        $phone = $input['phone_num'];

        // $sms = new SmsController();

        // $sms->sendSms($phone);

        $name       = trim($request->name);
        $grade      = trim($request->grade);
        $student_id = trim($request->student_id);
        $phone_num  = trim($request->phone_num);
        $radio      = trim($request->radio);

        if( strlen($name) == 0 ||  strlen($grade) == 0 || strlen($student_id) == 0 || strlen($phone_num) == 0){
            return json_encode(['code' => 1, 'msg' => '请将数据填写完整']);
        }

        $count = DB::table("student")
            ->where('student_id',$student_id)
            ->count();
        $count2 = DB::table("student")
            ->where('phone_num',$phone_num)
            ->count();
        if($count){
            return json_encode(['code' => 1, 'msg' => '已报名，请不要重复报名']);
        }else if($count2){
            return json_encode(['code' => 1, 'msg' => '电话号码已被占用']);
        }

        $id = DB::table('student')->insertGetId([
            'name'          => $name,
            'grade'         => $grade,
            'student_id'    => $student_id,
            'phone_num'     => $phone_num,
            'radio'         => $radio,
            'create_time'   => ceil(microtime(true) * 1000),
        ]);
        if ( $id ){
            return json_encode(['code' => 0, 'msg' => '报名成功']);
        }
        return json_encode(['code' => 1, 'msg' => '报名失败，请重新报名']);
    }
    public function search(Request $request){

        $student_id = trim($request->student_id);

        if(strlen($student_id) == 0 ){
            return json_encode(['code' => 1, 'msg' => '请重新填写学号']);
        }
        $count = DB::table("student")->where('student_id',$student_id)->count();
        if($count){
            $student = DB::table("student")->where('student_id',$student_id)->first();
            return json_encode([
                'code' => 0,
                'msg' => [
                    'name'          => $student->name,
                    'grade'         => $student->grade,
                    'phone_num'     => $student->phone_num,
                    'radio'         => $student->radio,
                    'create_time'   => $student->create_time,
                ]
            ]);
        }
        return json_encode(['code' => 1, 'msg' => '未报名']);
    }
    public function reset(Request $request){
        $name       = trim($request->name);
        $grade      = trim($request->grade);
        $student_id = trim($request->student_id);
        $phone_num  = trim($request->phone_num);
        $radio      = trim($request->radio);

        if( strlen($name) == 0 ||  strlen($grade) == 0 || strlen($student_id) == 0 || strlen($phone_num) == 0){
            return json_encode(['code' => 1, 'msg' => '请将数据填写完整']);
        }
        $count = DB::table("student")->where('student_id',$student_id)->count();
        $count2 = DB::table("student")->where('student_id','!=',$student_id)
                                    ->where('phone_num',$phone_num)->count();
        if($count == 0){
            return json_encode(['code' => 1, 'msg' => '未报名']);
        }else if($count2 != 0){
            return json_encode(['code' => 1, 'msg' => '电话号码已被占用']);
        }
        DB::table("student")
            ->where('student_id',$student_id)
            ->update([
                'name'          => $name,
                'grade'         => $grade,
                'phone_num'     => $phone_num,
                'radio'         => $radio
            ]);
        return json_encode(['code' => 0, 'msg' => '修改成功']);
    }
}