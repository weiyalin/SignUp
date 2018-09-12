<template>
    <div class="inputs">
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
                    <el-option label="信息工程学院" value="0"></el-option>
                    <el-option label="数学科学学院" value="1"></el-option>
                    <el-option label="机电学院" value="2"></el-option>
                    <el-option label="食品学院" value="3"></el-option>
                    <el-option label="动物科技学院" value="4"></el-option>
                    <el-option label="园艺园林学院" value="5"></el-option>
                    <el-option label="资源与环境学院" value="6"></el-option>
                    <el-option label="化学化工学院" value="7"></el-option>
                    <el-option label="文法学院" value="8"></el-option>
                    <el-option label="教育科学学院" value="9"></el-option>
                    <el-option label="艺术学院" value="10"></el-option>
                    <el-option label="服装学院" value="11"></el-option>
                    <el-option label="生命科技学院" value="12"></el-option>
                    <el-option label="外国语学院" value="13"></el-option>
                    <el-option label="体育学院" value="14"></el-option>
                    <el-option label="经济与管理学院" value="15"></el-option>
                </el-select>
                <div class="open"><span>新科学院暂未开放</span></div>
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
                <el-input placeholder="个人简历 ...... 不要超过150字哦！(获奖经历或者所参加的重大活动)" v-model="form.introduce" type="textarea" :maxlength="150"></el-input>
            </el-form-item>
            <el-form-item>
                <el-checkbox v-model="checked" label="1"class="checkb">我同意招新规则。</el-checkbox>
                <span class="spshow" @click="showRule">查看招新规则>></span>
                <div style="text-align: center">
                    <p style="margin: 0;color: red">报名费十元,面试结束后退还。</p>
                </div>
            </el-form-item>
            <el-form-item >
                <el-button  class="my_submit" type="primary" @click="chosepay">报名</el-button>
            </el-form-item>
            <el-form-item class="positionimg">
                <div class="img">
                <img :src="img_group" alt="" class="img_group">
                </div>
                <div style="text-align: center">
                    <p style="margin: 0;color: red">
                        报名后请务必加群<br>
                        后续相关通知会在群中发布
                    </p>
                </div>
            </el-form-item>
        </el-form>
    </div>
</template>
<style>
    .el-textarea textarea{
        height: 100px;
    }
    .open{
        width: 100px;
        font-size: 12px;
        color: rgba(0,0,0,0.4);
        margin: 0 auto;
        position: relative;
    }
    .checkb{
        color: #000;
    }
    .spshow{
        color: #8a6d3b;
        font-size: 14px;
    }
    .positionimg{
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
    .img{
        display: flex;
        justify-content: center;
    }
    .open > span{
        text-align: center;
        position: absolute;
        top: -7px;
        display: block;
    }
    .my_submit{
        width: 100%;
    }

    @media  only screen and (min-width: 500px){
        .checkb{
            color: #fff !important;
        }
        .spshow{
            color: wheat !important;
        }
        .inputs{
            width: 39%;
            margin-top: 2%;
            margin-left: 30%;
        }
        .inputs input{
            height: 40px;
            font-size: 17px;
        }
        .el-form-item{
            margin-bottom: 41px;
        }
        .open > span{
            width: 225px;
            margin-top: 10px;
        }
        .open{
            width: 225px;
            color: red;
            font-size: 16px;
        }
        .el-select-dropdown__item{
            font-size: 17px;
            height: 40px;
        }
        .el-form-item__content{
            font-size: 22px;
        }
        .my_submit{
            font-size: 29px;
            background-color:#c5d4e8;
        }
    }
</style>
<script>
    export default {
        data() {
            return {
                img_group : '/dist/img/marchsofts.png',
                form: {
                    name:       '',
                    sex :       '1',
                    faculty:    '0',
                    profession: '',
                    class:      '',
                    student_id: '',
                    phone:  '',
                    QQ:         '',
                    introduce:   '',
                },
                pay_ways : 0,
                time: 0,
                disabled: false,
                checked: false
            }
        },
        methods: {
            showRule(){
                this.$alert('1、我同意时刻关注QQ招新群消息，如果错过重要通知后果自己承担。' +
                    '2、我同意报名后自愿参加三月的招新活动，如果中途退出招新，报名费不予退还。', '招新规则', {
                    confirmButtonText: '确定',
                });
            },
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
                var reg_id      = /^2018\d{6,7}$/;
                var reg_mobile  = /^1[3|5|7|8]\d{9}$/;
                var reg_phone   = /^0\d{2,3}-?\d{7,8}$/;

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
                }else if(!this.checked){
                    this.$message({
                        showClose: true,
                        message: '请同意招新规则',
                        type: 'warning'
                    });
                }
                else {
                    return true;
                }
                return false;
            },
            onSubmit() {
                let self = this;
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
                        introduce  : this.form.introduce,
                    }).then(
                        function (response) {
                            var data = response.data;
                            if(data.code == 0){
                                alert(data.msg);
                                location.href='http://www.lishanlei.cn/#/select'
                            }else if(data.code == 1) {
                                self.$message({
                                    showClose: true,
                                    message: data.msg,
                                    type: 'error'
                                });
                            }
                        }
                    )
                }
            },
            chosepay() {
                let self = this;
                self.remove_spaces();
                if (self.test()){
                this.$http.post('stuissign', {
                    phone:      self.form.phone,
                    student_id: self.form.student_id,
                }).then(
                      function (response) {
                            var data = response.data;
                            if (data.code == 1) {
                                self.$message({
                                    showClose: true,
                                    message: data.msg,
                                    type: 'error'
                                });
                            } else {
                                var ua = window.navigator.userAgent.toLowerCase();
                                if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                                    self.postpay();
                                } else {
                                    self.pay_ways = 1;
                                    self.$http.post('savestuma',{
                                        phone:      self.form.phone,
                                        student_id: self.form.student_id,
                                        name:       self.form.name,
                                        sex:        self.form.sex,
                                        faculty:    self.form.faculty,
                                        profession: self.form.profession,
                                        class:      self.form.class,
                                        QQ:         self.form.QQ,
                                        introduce:  self.form.introduce,
                                    });
                                    window.location.href = '/alipay/wappay?phone='+self.form.phone+'&student_id='+self.form.student_id+'&pay_ways='+self.pay_ways;
                                 }
                            }
                      }
                   )
                }
            },
            postpay(){
                let self = this;
                this.$http.post('wechatpay/getpay',{
                    student_id : this.form.student_id,
                    phone      : this.form.phone,
                    pay_ways   : this.pay_ways,
                }).then(
                    function (response) {
                        let data = response.data;
                        if(data.code == 1){
                            self.sicallpay(data.result);
                        }
                        else {
                            self.$message({
                                showClose: true,
                                message: data.msg,
                                type: 'error'
                            });
                        }
                    })
            },
            sicallpay(result){
                if(typeof WeixinJSBridge == 'undefined'){
                    if(document.addEventListener()){
                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                    }else if(document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady',  onBridgeReady);
                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                    }
                }else {
                    this.onBridgeReady(result);
                }
            },
            onBridgeReady(result){
                let self = this;
                let timeStamps = String(result.timeStamp);
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest', {
                        "appId":result.appId,
                        "timeStamp":timeStamps,
                        "nonceStr":result.nonceStr,
                        "package":result.package,
                        "signType":"MD5",
                        "paySign":result.paySign
                    },
                    function (res) {
                        if(res.err_msg == "get_brand_wcpay_request:ok"){
                            self.updateOrders(result.payId);
                        }else {
                            self.$message({
                                showClose: true,
                                message: '支付失败,无法报名',
                                type: 'error'
                            });
                        }
                    }
                );
            },
            updateOrders(orderid){
                let self = this;
                this.$http.post('/wechatpay/updateOrder', {
                    id : orderid,
                }).then(
                    self.onSubmit()
                )
            },
        }
    }
</script>
