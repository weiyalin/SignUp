<?php
namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Model\StudentDatabase;
use Illuminate\Http\Request;
class StudentController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    //学生报名,把信息放入数据库
    public function sign(Request $request){
        $introduce  = $request->introduce;
        $name       = trim($request->name);
        $sex        = trim($request->sex);
        $faculty    = trim($request->faculty);
        $profession = trim($request->profession);
        $class      = trim($request->class);
        $student_id = trim($request->student_id);
        $phone      = trim($request->phone);
        $QQ         = trim($request->QQ);
        if( strlen($name) == 0 ||  strlen($class) == 0 || strlen($student_id) == 0 || strlen($phone) == 0
            || strlen($profession) == 0 || strlen($QQ) == 0){
            return json_encode(['code' => 1, 'msg' => '请将数据填写完整']);
        }
        $stuid = StudentDatabase::insertstudent($name,$sex,$faculty,$profession,$class,$student_id,$phone,$QQ,$introduce);
        return $stuid;
    }
    /**
     * @param Request $request
     * @return string
     */
    //根据学生ID查找学生信息
    public function search(Request $request){

        $student_id = trim($request->student_id);
        if(strlen($student_id) == 0 ){
            return json_encode(['code' => 1, 'msg' => '请重新填写学号']);
        }
        $acordstuidct = StudentDatabase::acordstuidse($student_id);
        if($acordstuidct == 0) {
            return json_encode(['code' => 1, 'msg' => '未报名']);
        }
        $student = StudentDatabase::sefirststu($student_id);
        return $student;

    }
    /**
     * @param Request $request
     * @return string
     */
    //修改学生的信息
    public function reset(Request $request){

        $name       = trim($request->name);
        $sex        = trim($request->sex);
        $faculty    = trim($request->faculty);
        $profession = trim($request->profession);
        $class      = trim($request->class);
        $student_id = trim($request->student_id);
        $phone      = trim($request->phone);
        $QQ         = trim($request->QQ);
        $introduce  = $request->introduce;
        if( strlen($name) == 0 ||  strlen($class) == 0 || strlen($student_id) == 0 || strlen($phone) == 0
            || strlen($profession) == 0 || strlen($QQ) == 0){
            return json_encode(['code' => 1, 'msg' => '请将数据填写完整']);
        }
        $countstu = StudentDatabase::acordstuidse($student_id);
        if($countstu == 0){
            return json_encode(['code' => 1, 'msg' => '未报名']);
        }
        $cotstuph = StudentDatabase::acstuidsephon($student_id,$phone);
        if($cotstuph != 0){
            return json_encode(['code' => 1, 'msg' => '电话号码已被占用']);
        }
        $upstuinma = StudentDatabase::updatestuinma($student_id,$name,$sex,$faculty,$profession,$class,$phone,$QQ,$introduce);
        return  $upstuinma;
    }
     //根据学生提交的学号，去查看学生是否已经报名
     public function seornumse(Request $request)
     {
       $res = StudentDatabase::acordstuidse($request->student_id);
       if($res == 1){
           return json_encode(['code' => 1, 'msg' => '你已经报名']);
       }else{
           return json_encode(['code' => 0, 'msg' => '未经报名']);
       }
     }
}