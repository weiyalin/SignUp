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
class StudentController extends Controller
{
    public function sign(Request $request){

        $name       = trim($request->name);
        $grade      = trim($request->grade);
        $student_id = trim($request->student_id);
        $phone_num  = trim($request->phone_num);

        if( strlen($name) == 0 ||  strlen($grade) == 0 || strlen($student_id) == 0 || strlen($phone_num) == 0){
            return json_encode(['code' => 1, 'msg' => '请将数据填写完整']);
        }

        $count = DB::table("student")
            ->where('student_id',$student_id)
            ->count();
        if($count){
            return json_encode(['code' => 1, 'msg' => '已报名，请不要重复报名']);
        }

        $id = DB::table('student')->insertGetId([
            'name'          => $name,
            'grade'         => $grade,
            'student_id'    => $student_id,
            'phone_num'     => $phone_num,
            'create_time'   => ceil(microtime(true) * 1000),
        ]);
        if ( $id ){
            return json_encode(['code' => 0, 'msg' => '报名成功']);
        }
        return json_encode(['code' => 1, 'msg' => '报名失败，请重新报名']);
    }
}