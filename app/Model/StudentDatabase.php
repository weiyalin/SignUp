<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StudentDatabase extends Model
{
    /**
     * @return mixed
     */
    //查询全部学生的信息
    public static function seallstudent()
    {
        $allstuinma = DB::table('newstudent')->get();
        return $allstuinma;
    }
    /**
     * @param $student_id
     * @return mixed
     */
    //根据学生ID匹配查询
    public static function acordstuidse($student_id)
    {
        $count = DB::table('newstudent')->where('student_id',$student_id)->count();
        return $count;
    }
    /**
     * @param $phone
     * @return mixed
     */
    //根据学生Phone匹配查询
    public static function acordstuphose($phone)
    {
        $count = DB::table('newstudent')->where('phone',$phone)->count();
        return $count;
    }
    /**
     * @param $name
     * @param $sex
     * @param $faculty
     * @param $profession
     * @param $class
     * @param $student_id
     * @param $phone
     * @param $QQ
     * @return string
     */
    //添加新学生信息
    public static function insertstudent($name,$sex,$faculty,$profession,$class,$student_id,$phone,$QQ,$introduce)
    {
        $acordstuidct = StudentDatabase::acordstuidse($student_id);
        $acordstuphoe = StudentDatabase::acordstuphose($phone);
        if($acordstuidct){
            return json_encode(['code' => 1, 'msg' => '已报名，请不要重复报名']);
        }else if($acordstuphoe){
            return json_encode(['code' => 1, 'msg' => '电话号码被占用']);
        }
        $creatime = ceil(microtime(true) * 1000);
        $studentid = DB::table('newstudent')->insertGetid([
            'name'           =>$name,
            'sex'            =>$sex,
            'faculty'        =>$faculty,
            'profession'     =>$profession,
            'class'          =>$class,
            'student_id'     =>$student_id,
            'phone'          =>$phone,
            'QQ'             =>$QQ,
            'create_time'    =>$creatime,
            'introduce'      =>$introduce
        ]);
        if ($studentid){
            return json_encode(['code' => 0, 'msg' => '报名成功']);
        }
        return json_encode(['code' => 1, 'msg' => '报名失败，请重新报名']);
    }
    /**
     * @param $student_id
     * @return string
     */
    //通过学生ID查询第一个学生的信息
    public static function sefirststu($student_id)
    {
            $isstudeorder = WeChatPayDatabase::acstuseorder($student_id);  //查学生是否已经交费
            $student = DB::table("newstudent")->where('student_id',$student_id)->first();
            return json_encode([
                'code' => 0,
                'msg' => [
                    'name'        => $student->name,
                    'sex'         => $student->sex,
                    'faculty'     => $student->faculty,
                    'profession'  => $student->profession,
                    'class'       => $student->class,
                    'phone'       => $student->phone,
                    'QQ'          => $student->QQ,
                    'introduce'   => $student->introduce,
                    'create_time' => $student->create_time,
                    'is_pay'      => $isstudeorder,
                ]
            ]);
    }
    /**
     * @param $student_id
     * @param $phone
     * @return mixed
     */
    //根据手机号和学生ID，,反查询，判断手机号是否被占用
    public static function acstuidsephon($student_id,$phone)
    {
       $count = DB::table("newstudent")
             ->where('student_id','!=',$student_id)
             ->where('phone',$phone)->count();
       return $count;
    }
    /**
     * @param $student_id
     * @param $name
     * @param $sex
     * @param $faculty
     * @param $profession
     * @param $class
     * @param $phone
     * @param $QQ
     * @return string
     */
    //修改学生的信息
    public static function updatestuinma($student_id,$name,$sex,$faculty,$profession,$class,$phone,$QQ,$introduce)
    {
        $updatetime=ceil(microtime(true) * 1000);
        $count=DB::table("newstudent")
           ->where('student_id',$student_id)
           ->update([
               'name'          => $name,
               'sex'           => $sex,
               'faculty'       => $faculty,
               'profession'    => $profession,
               'class'         => $class,
               'phone'         => $phone,
               'QQ'            => $QQ,
               'introduce'     => $introduce,
               'update_time'   => $updatetime
           ]);
        if($count){
            return  json_encode(['code' => 0, 'msg' => '修改成功']);
        }else{
            return  json_encode(['code' => 1, 'msg' => '修改失败']);
        }
    }
}