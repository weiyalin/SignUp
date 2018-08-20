<?php
/**
 * Created by PhpStorm.
 * User: ztfxhld520
 * Date: 2018/8/10
 * Time: 15:31
 */

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentDatabase extends Model
{
    //查询全部学生的信息
    public static function seallstudent()
    {
        $allstuinma = DB::table('student')->get();
        return $allstuinma;
    }
    //根据学生ID匹配查询
    public static function acordstuidse($student_id)
    {
        $count = DB::table('student')->where('student_id',$student_id)->count();
        return $count;
    }
    //根据学生Phone匹配查询
    public static function acordstuphose($phone)
    {
        $count = DB::table('student')->where('phone',$phone)->count();
        return $count;
    }
    //添加新学生信息
    public static function insertstudent($name,$sex,$faculty,$profession,$class,$student_id,$phone,$QQ)
    {
        $acordstuidct = StudentDatabase::acordstuidse($student_id);
        $acordstuphoe = StudentDatabase::acordstuphose($phone);
        $isstudeorder = OrderDatabase::acrstuidseorder($student_id);
        if($acordstuidct){
            if(!$isstudeorder){
                return json_encode(['code' => 2, 'msg' => '未付款，请继续付款']);
            }
            return json_encode(['code' => 1, 'msg' => '已报名，请不要重复报名']);
        }else if($acordstuphoe){
            return json_encode(['code' => 1, 'msg' => '电话号码被占用']);
        }
        $creatime = ceil(microtime(true) * 1000);
        $studentid = DB::table('student')->insertGetid([
            'name'           =>$name,
            'sex'            =>$sex,
            'faculty'        =>$faculty,
            'profession'     =>$profession,
            'class'          =>$class,
            'student_id'     =>$student_id,
            'phone'          =>$phone,
            'QQ'             =>$QQ,
            'create_time'    =>$creatime
        ]);
        if ($studentid){
            return json_encode(['code' => 0, 'msg' => '报名成功']);
        }
        return json_encode(['code' => 1, 'msg' => '报名失败，请重新报名']);
    }
    //通过学生ID查询第一个学生的信息
    public static function sefirststu($student_id)
    {
            $isstudeorder = OrderDatabase::acrstuidseorder($student_id);
            $student = DB::table("student")->where('student_id',$student_id)->first();
            return json_encode([
                'code' => 1,
                'msg' => [
                    'name'        => $student->name,
                    'sex'         => $student->sex,
                    'faculty'     => $student->faculty,
                    'profession'  => $student->profession,
                    'class'       => $student->class,
                    'phone'       => $student->phone,
                    'QQ'          => $student->QQ,
                    'create_time' => $student->create_time,
                    'is_buy'      => $isstudeorder
                ]
            ]);
    }
    //根据手机号和学生ID，,反查询，判断手机号是否被占用
    public static function acstuidsephon($student_id,$phone)
    {
       $count = DB::table("student")
             ->where('student_id','!=',$student_id)
             ->where('phone',$phone)->count();
       return $count;
    }
    //修改学生的信息
    public static function updatestuinma($student_id,$name,$sex,$faculty,$profession,$class,$phone,$QQ)
    {
        $updatetime=ceil(microtime(true) * 1000);
        $count=DB::table("student")
           ->where('student_id',$student_id)
           ->update([
               'name'          => $name,
               'sex'           => $sex,
               'faculty'       => $faculty,
               'profession'    => $profession,
               'class'         => $class,
               'phone'         => $phone,
               'QQ'            => $QQ,
               'update_time'   => $updatetime
           ]);
        if($count){
            return  json_encode(['code' => 1, 'msg' => '修改成功']);
        }else{
            return  json_encode(['code' => 0, 'msg' => '修改失败']);
        }
    }


}