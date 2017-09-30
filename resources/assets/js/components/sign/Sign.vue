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
                <el-input placeholder="验证码" v-model="form.code" style="width: 65%"></el-input>
                <el-button style="width: 30%" class="sendSmsBtn" :class="disabled?'dissendSmsBtn':''" type="button" v-on:click="start" :disabled="disabled || time > 0">{{ text }}</el-button>
            </el-form-item>
            <el-form-item>
                <el-radio class="radio my_radio" v-model="form.radio" label="1">开发</el-radio>
                <el-radio class="radio my_radio my_radio2" v-model="form.radio" label="2">美工</el-radio>
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
        background-color: orange;
    }
    .my_submit:active{
        background-color: #fa5e00;
    }
    .my_submit:hover{
        background-color: #fa5e00;
    }
    .my_radio{
        margin: 20px 15% 0 15%;
    }
    .my_radio2{
        float: right;
    }
    .sendSmsBtn{
        position: absolute;
        height: 37px;
        line-height: 34px;
        border-radius: 3px;
        background: #ffb400;
        border: none;
        padding: 0 6px;
        color: #fff;
        display: inline-block;
        width: 90px;
        top: 0;
        right:0;
    }
    .sendSmsBtn .dissendSmsBtn{
        background-color: #FFE39F;
    }
    .el-radio__input.is-checked .el-radio__inner{
        background-color: orange;
    }
    .el-radio__inner:hover{
        background-color: orangered;
    }
</style>
<script>
    export default {
        props:{
            second: {
                type: Number,
                default: 60
            }
        },
        data() {
            return {
                form: {
                    name:       '',
                    grade:      '',
                    student_id: '',
                    phone_num:  '',
                    radio:      '1',
                },
                time: 0,
                disabled: false
            }
        },
        computed: {
            text: function () {
                return this.time > 0 ? this.time + 's 后重获取' : '获取验证码';
            }
        },
        methods: {
            start: function () {
                this.time = this.second;
                console.log(this.disabled);
                this.timer();
            },
            setDisabled: function (val) {
                this.disabled = val;
            },
            timer: function () {
                if (this.time > 0){
                    this.time--;
                    setTimeout(this.timer, 1000);
                }else {
                    this.disabled = false;
                }
            },
            remove_spaces(){
                this.form.name        = this.form.name.trim();
                this.form.grade       = this.form.grade.trim();
                this.form.student_id  = this.form.student_id.trim();
                this.form.phone_num   = this.form.phone_num.trim();
                this.form.code        = this.form.code.trim();
            },
            test(){
                var reg_name    = /^[\u4E00-\u9FA5]{2,4}$/;
                var reg_id      = /^20\d{8,9}$/;
                var reg_mobile  = /^1[3|5|7|8]\d{9}$/;
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
                }else if (!code.test(this.form.code)){
                    this.$message({
                        showClose: true,
                        message: '验证码错误',
                        type: 'warning'
                    });
                }else {
                    return false;
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
                        radio      : this.form.radio,
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
