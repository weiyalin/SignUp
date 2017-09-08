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
        <div style="width: 100%" v-if="show">
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
                <el-col class="title" :span="10">时间</el-col>
                <el-col :span="14">{{new Date(student.create_time).format('yyyy-MM-dd hh:mm')}}</el-col>
            </el-row>
        </div>
    </div>
</template>
<style>
    .my_item{
        margin-right: 0 !important;
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
                show      :false,
                student_id: '',
                searching : false,
                student   : {
                    name        : '',
                    grade       : '',
                    phone_num   : '',
                    create_time : 0,
                }
            }
        },
        methods: {
            remove_spaces(){
                this.student_id  = this.student_id.trim();
            },
            test(){
                var reg_id = /^20\d{9}$/;
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
                                this.show = true;
                                this.student.name       = data.msg.name;
                                this.student.grade      = data.msg.grade;
                                this.student.phone_num  = data.msg.phone_num;
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
            }
        }
    }
</script>
