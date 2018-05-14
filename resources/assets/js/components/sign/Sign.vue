<template>
    <div>
        <el-form ref="form" :model="form">
            <el-form-item>
                <el-input placeholder="姓名" v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item>
                <el-select v-model="form.sex" placeholder="请选择性别" style="width: 100%">
                    <el-option label="女" value="0"></el-option>
                    <el-option label="男" value="1"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-select v-model="form.faculty" placeholder="请选择院系" style="width: 100%">
                    <el-option label="经济与管理学院" value="0"></el-option>
                    <el-option label="生命科技学院" value="1"></el-option>
                    <el-option label="机电学院" value="2"></el-option>
                    <el-option label="食品学院" value="3"></el-option>
                    <el-option label="动物科技学院" value="4"></el-option>
                    <el-option label="园艺园林学院" value="5"></el-option>
                    <el-option label="资源与环境学院" value="6"></el-option>
                    <el-option label="化学化工学院" value="7"></el-option>
                    <el-option label="教育科学学院" value="8"></el-option>
                    <el-option label="艺术学院" value="9"></el-option>
                    <el-option label="服装学院" value="10"></el-option>
                    <el-option label="数学科学学院" value="11"></el-option>
                    <el-option label="外国语学院" value="12"></el-option>
                    <el-option label="体育学院" value="13"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="专业" v-model="form.profession"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="班级" v-model="form.class"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="学号" v-model="form.student_id"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="电话号码" v-model="form.phone"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="QQ号码" v-model="form.QQ"></el-input>
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
                    sex :       '1',
                    faculty:    '0',
                    profession: '',
                    class:      '',
                    student_id: '',
                    phone:  '',
                    QQ:         '',
                },
                time: 0,
                disabled: false
            }
        },
        methods: {
            remove_spaces(){
                this.form.name        = this.form.name.trim();
                this.form.profession  = this.form.profession.trim();
                this.form.class       = this.form.class.trim();
                this.form.student_id  = this.form.student_id.trim();
                this.form.phone       = this.form.phone.trim();
                this.form.QQ          = this.form.QQ.trim();
            },
            test(){
                var reg_name    = /^[\u4E00-\u9FA5]{2,5}$/;
                var reg_id      = /^20\d{8,9}$/;
                var reg_mobile  = /^1[3|5|7|8]\d{9}$/;
                var reg_phone    = /^0\d{2,3}-?\d{7,8}$/;

                if(!(reg_name.test(this.form.name))){
                    this.$message({
                        showClose: true,
                        message: '姓名错误，请重新填写',
                        type: 'warning'
                    });
                }else if(this.form.class.length < 5 || this.form.class.length > 10){
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
                }else if(!reg_mobile.test(this.form.phone) && !reg_phone.test(this.form.phone)){
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
                        sex        : this.form.sex,
                        faculty    : this.form.faculty,
                        profession : this.form.profession,
                        class      : this.form.class,
                        student_id : this.form.student_id,
                        phone      : this.form.phone,
                        QQ         : this.form.QQ,
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
