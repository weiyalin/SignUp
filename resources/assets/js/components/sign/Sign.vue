<template>
    <div>
        <el-form ref="form" :model="form">
            <el-form-item>
                <el-input placeholder="姓名" v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="班级" v-model="form.grade"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="学号" v-model="form.student_id"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="电话号码" v-model="form.phone_num"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button class="my_submit" type="primary" @click="onSubmit">报名</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<style>
    .my_submit{
        width: 100%;
    }
</style>
<script>
    export default {
        data() {
            return {
                form: {
                    name:       '',
                    grade:      '',
                    student_id: '',
                    phone_num:  '',
                }
            }
        },
        methods: {
            remove_spaces(){
                this.form.name        = this.form.name.trim();
                this.form.grade       = this.form.grade.trim();
                this.form.student_id  = this.form.student_id.trim();
                this.form.phone_num   = this.form.phone_num.trim();
            },
            test(){
                var reg_name    = /^[\u4E00-\u9FA5]{2,4}$/;
                var reg_id      = /^20\d{9}$/;
                var reg_mobile  = /^1[3|5|8]\d{9}$/;
                var reg_phone    = /^0\d{2,3}-?\d{7,8}$/;

                if(!(reg_name.test(this.form.name))){
                    this.$message({
                        showClose: true,
                        message: '姓名错误，请重新填写',
                        type: 'warning'
                    });
                }else if(this.form.grade.length < 5 || this.form.grade.length > 10){
                    this.$message({
                        showClose: true,
                        message: '班级错误，请重新填写',
                        type: 'warning'
                    });
                }else if(!reg_id.test(this.form.student_id)){
                    this.$message({
                        showClose: true,
                        message: '学号错误，请重新填写',
                        type: 'warning'
                    });
                }else if(!reg_mobile.test(this.form.phone_num) && !reg_phone.test(this.form.phone_num)){
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
                this.remove_spaces();
                if(this.test()){
                    this.$http.post('/sign',{
                        name       : this.form.name,
                        grade      : this.form.grade,
                        student_id : this.form.student_id,
                        phone_num  : this.form.phone_num,
                    }).then(
                        function (response) {
                            var data = response.data;
                            if(data.code == 0){
                                this.$message({
                                    showClose: true,
                                    message: data.msg,
                                    type: 'success'
                                });
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
            }
        }
    }
</script>
