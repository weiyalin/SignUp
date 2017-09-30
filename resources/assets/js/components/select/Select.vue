<template>
    <div>
        {{ this.change_date()  }}
        <el-form :inline="true">
            <el-form-item style="width:74%;" class="my_item">
                <el-input style="" v-model="student_id" placeholder="输入学号查找" ></el-input>
            </el-form-item>
            <el-form-item style="width: 23%; float: right" class="my_item">
                <el-button type="primary" icon="search" @click="onSearchClick"
                           :loading="searching" style="width:100%;">
                </el-button>
            </el-form-item>
        </el-form>
        <div style="width: 100%" v-if="show_meg">
            <el-row :gutter="20">
                <el-col class="title" :span="10">姓名</el-col>
                <el-col :span="14">{{ student.name }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">班级</el-col>
                <el-col :span="14">{{ student.grade }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">电话</el-col>
                <el-col :span="14">{{ student.phone_num }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">方向</el-col>
                <el-col :span="14">{{ direction }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">时间</el-col>
                <el-col :span="14">{{new Date(student.create_time).format('yyyy-MM-dd hh:mm')}}</el-col>
            </el-row>
            <el-button class="reset_submit" type="primary" @click="reset">修改</el-button>
        </div>
        <el-form ref="form" :model="student" v-if="show_reset">
            <el-form-item>
                <el-input placeholder="姓名" v-model="student.name"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="班级" v-model="student.grade"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="电话号码" v-model="student.phone_num"></el-input>
            </el-form-item>
            <el-form-item>
                <el-radio class="radio my_radio" v-model="student.radio" label="1">开发</el-radio>
                <el-radio class="radio my_radio my_radio2" v-model="student.radio" label="2">美工</el-radio>
            </el-form-item>
            <el-form-item>
                <el-button class="my_submit" type="primary" @click="onSubmit">修改</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<style>
    .my_radio{
        margin: 0 15%;
    }
    .my_radio2{
        float: right;
    }
    .my_item{
        margin-right: 0 !important;
    }
    .reset_submit{
        margin-top: 20px;
        width: 100%;
    }
    .my_submit{
        width: 100%;
    }
    .el-form-item__content{
        width: 100%;
    }
    .el-row{
        padding: 20px;
        border-bottom: 1px #74787E solid;
    }
</style>
<script>
    export default {
        data() {
            return {
                show_meg  :false,
                show_reset:false,
                student_id: '',
                searching : false,
                direction : '开发',
                student   : {
                    name        : '',
                    grade       : '',
                    phone_num   : '',
                    student_id  : '',
                    radio       : '1',
                    create_time : 0,
                }
            }
        },
        methods: {
            remove_spaces(){
                this.student_id  = this.student_id.trim();
            },
            test(){
                var reg_id = /^20\d{8,9}$/;
                if(!reg_id.test(this.student_id)){
                    this.$message({
                        showClose: true,
                        message: '学号错误，请重新填写',
                        type: 'warning'
                    });
                }else {
                    return true;
                }
                return false;
            },
            reset_direction(){
                if(this.student.radio == 1){
                    this.direction = '开发';
                }else if( this.student.radio == 2 ){
                    this.direction = '美工';
                }else{
                    this.direction = '未知';
                }
            },
            onSearchClick() {
                this.searching = true;
                this.remove_spaces();
                if(this.test()){
                    this.$http.post('/search',{
                        student_id : this.student_id
                    }).then(
                        function (response) {
                            var data = response.data;
                            this.searching = false;
                            if(data.code == 0){
                                this.show_reset = false;
                                this.show_meg = true;
                                this.student.name       = data.msg.name;
                                this.student.grade      = data.msg.grade;
                                this.student.phone_num  = data.msg.phone_num;
                                this.student.radio      = data.msg.radio.toString();
                                this.reset_direction();
                                this.student.create_time= data.msg.create_time;
                            } else {
                                this.$message({
                                    showClose: true,
                                    message: data.msg,
                                    type: 'error'
                                });
                            }
                        }
                    )
                }
                this.searching = false;
            },
            reset_remove_spaces(){
                this.student.name        = this.student.name.trim();
                this.student.grade       = this.student.grade.trim();
            },
            reset_test(){
                var reg_name    = /^[\u4E00-\u9FA5]{2,4}$/;
                var reg_mobile  = /^1[3|5|8]\d{9}$/;
                var reg_phone    = /^0\d{2,3}-?\d{7,8}$/;

                if(!(reg_name.test(this.student.name))){
                    this.$message({
                        showClose: true,
                        message: '姓名错误，请重新填写',
                        type: 'warning'
                    });
                }else if(this.student.grade.length < 5 || this.student.grade.length > 10){
                    this.$message({
                        showClose: true,
                        message: '班级错误，请重新填写',
                        type: 'warning'
                    });
                }else if(!reg_mobile.test(this.student.phone_num) && !reg_phone.test(this.student.phone_num)){
                    this.$message({
                        showClose: true,
                        message: '电话号码错误，请重新填写',
                        type: 'warning'
                    });
                }else {
                    return true;
                }
                return false;
            },
            onSubmit() {
                this.reset_remove_spaces();
                if(this.reset_test()){
                    this.$http.post('/reset',{
                        name       : this.student.name,
                        grade      : this.student.grade,
                        student_id : this.student.student_id,
                        phone_num  : this.student.phone_num,
                        radio      : this.student.radio,
                    }).then(
                        function (response) {
                            var data = response.data;
                            if(data.code == 0){
                                this.$message({
                                    showClose: true,
                                    message: data.msg,
                                    type: 'success'
                                });
                                this.reset_direction();
                                this.show_reset = false;
                                this.show_meg   = true;
                            } else {
                                this.$message({
                                    showClose: true,
                                    message: data.msg,
                                    type: 'error'
                                });
                            }
                        }
                    )
                }
            },
            change_date(){
                Date.prototype.format = function (fmt) {
                    var o = {
                        "M+": this.getMonth() + 1,                      //月份
                        "d+": this.getDate(),                           //日
                        "h+": this.getHours(),                          //小时
                        "m+": this.getMinutes(),                        //分
                        "s+": this.getSeconds(),                        //秒
                        "q+": Math.floor((this.getMonth() + 3) / 3),    //季度
                        "S": this.getMilliseconds()                     //毫秒
                    };
                    if (/(y+)/.test(fmt)) {
                        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                    }
                    for (var k in o) {
                        if (new RegExp("(" + k + ")").test(fmt)) {
                            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                        }
                    }
                    return fmt;
                }
            },
            reset(){
                this.student.student_id = this.student_id,
                this.show_meg   = false;
                this.show_reset = true;
            }
        }
    }
</script>
