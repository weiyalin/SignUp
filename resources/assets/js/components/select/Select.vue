<template>
    <div>
        {{ this.change_date() }}
        <el-form :inline="true">
            <el-form-item style="width:74%;" class="my_item search-item searchnum">
                <el-input style="" v-model="student_id" placeholder="输入学号查找" class="searinputs" ></el-input>
            </el-form-item>
            <el-form-item style="width: 23%; float: right" class="my_item search-item gosear">
                <el-button class="search-button" type="primary" icon="search" @click="onSearchClick"
                           :loading="searching" style="width:100%;">
                </el-button>
            </el-form-item>
        </el-form>
        <div style="width: 100%; overflow: hidden" v-if="show_meg" class="information">
            <el-row :gutter="20">
                <el-col class="title" :span="10">姓名</el-col>
                <el-col :span="14">{{ student.name }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">性别</el-col>
                <el-col :span="14">{{ sex[student.sex] }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">院系</el-col>
                <el-col :span="14">{{ faculty[student.faculty] }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">专业</el-col>
                <el-col :span="14">{{ student.profession }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">班级</el-col>
                <el-col :span="14">{{ student.class }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">电话</el-col>
                <el-col :span="14">{{ student.phone }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">QQ</el-col>
                <el-col :span="14">{{ student.QQ }}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title" :span="10">时间</el-col>
                <el-col :span="14">{{new Date(student.create_time).format('yyyy-MM-dd hh:mm')}}</el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col class="title ispay" :span="10">是否付款</el-col>
                <el-col :span="14"  v-if = "student.is_pay">已付款</el-col>
                <el-col :span="14"  v-else>
                    未付款 <el-button type="primary" size="small" @click="chosepay">去付款</el-button>
                </el-col>
            </el-row>
            <el-button class="reset_submit" type="primary" @click="reset">修改</el-button>
        </div>
        <el-form  class="bigform" ref="form" :model="student" v-if="show_reset" style="margin-top: 23px">
            <el-form-item>
                <el-input placeholder="姓名" v-model="student.name"></el-input>
            </el-form-item>
            <el-form-item>
                <el-select v-model="student.sex" placeholder="请选择性别" style="width: 100%">
                    <el-option label="女" value="0"></el-option>
                    <el-option label="男" value="1"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-select v-model="student.faculty" placeholder="请选择院系" style="width: 100%">
                    <el-option label="经济与管理学院" value="0"></el-option>
                    <el-option label="生命科技学院" value="1"></el-option>
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
                    <el-option label="数学科学学院" value="12"></el-option>
                    <el-option label="外国语学院" value="13"></el-option>
                    <el-option label="体育学院" value="14"></el-option>
                    <el-option label="信息工程学院" value="15"></el-option>
                </el-select>
                <div class="open opens"><span>新科学院暂未开放</span></div>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="专业" v-model="student.profession"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="班级" v-model="student.class"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="学号" v-model="student.student_id"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="电话号码" v-model="student.phone"></el-input>
            </el-form-item>
            <el-form-item>
                <el-input placeholder="QQ号码" v-model="student.QQ"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button class="my_submit" type="primary" @click="onSubmit">修改</el-button>
            </el-form-item>
        </el-form>
        <p v-if="$route.params.paystatus != undefined">
          {{  this.remain($route.params.paystatus)}}
        </p>
    </div>
</template>

<style>
      @media (min-width: 200px) and (max-width: 499px) {
          .my_item{
              margin-right: 0 !important;
          }

      }
      .big{
          height: 1500px;
      }
    .reset_submit{
        margin-top: 20px;
        width: 100%;
    }
    .my_submit{
        width: 100%;
    }
    .search-item{
        margin-bottom: 0 !important;
    }
    .el-form-item__content{
        width: 100%;
    }
    .el-row{
        padding: 20px 0;
        border-bottom: 1px #74787E solid;
    }
    .weui-cell{
        width: 100%;
    }
      @media  only screen and (min-width: 500px){
        .searchnum{
            width: 18% !important;
            margin-left: 28%;
        }
        .gosear{
            margin-right: 26% !important;
            width: 14% !important;
        }
        .el-input__inner{
            height: 50px;
        }
        .searinputs{
            font-size: 17px!important;
        }
        .el-button--primary{
            width: 100px;
            height: 44px;
        }
        .information{
            color: #fff;
            width: 52% !important;
            margin-left: 33%;
            margin-top: 3%;

        }
        .reset_submit{
            margin-left: 25%;
        }
        .el-row{
            width: 67%;
        }
        .el-col{
            padding-left: 73px;
        }
        .ispay{
            margin-top: 3%;
        }
        .bigform{
            width: 40%;
            margin-left: 30%;
        }
        .opens{
            margin-top: -1%;
            color: red;
            height: 6px;
            text-align: center;
        }
    }
</style>
<script>
    export default {
        data() {
            return {
                sex       :['女','男'],
                faculty   :[
                    '经济与管理学院',
                    '生命科技学院',
                    '机电学院',
                    '食品学院',
                    '动物科技学院',
                    '园艺园林学院',
                    '资源与环境学院',
                    '化学化工学院',
                    '文法学院',
                    '教育科学学院',
                    '艺术学院',
                    '服装学院',
                    '数学科学学院',
                    '外国语学院',
                    '体育学院',
                    '信息工程学院'
                ],
                pay_ways : 0,
                is_pc     : false,
                show_meg  : false,
                show_reset: false,
                student_id: '',
                searching : false,
                direction : '开发',
                student   : {
                    name        : '',
                    sex         : '',
                    faculty     : '',
                    profession  : '',
                    class       : '',
                    student_id  : '',
                    phone       : '',
                    QQ          : '',
                    create_time : 0,
                    is_pay      : 0
                }
            }
        },
        methods: {
            remain(msg){
                alert(msg);
                location.href='http://localhost/getopenid#/select';
            },
            chosepay(){
                var ua = window.navigator.userAgent.toLowerCase();
                if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                    this.pay();
                }else {
                    this.pay_ways = 1;
                    window.location.href = '/alipay/wappay?phone='+this.student.phone+'&student_id='+this.student_id+'&pay_ways='+this.pay_ways;
                }
            },
            pay(){
                console.log(this.student_id+this.student.phone);
                this.$http.post('wechatpay/getpay',{
                    student_id : this.student_id,
                    phone      : this.student.phone,
                    pay_ways   : this.pay_ways,
                }).then(
                    function (response) {
                        console.log(response);
                        if(response.data.code == 1){
                            this.callpay(response.data.result);
                        }else{
                            alert(response.data.msg);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            callpay(result){
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                    }
                }else{
                    this.onBridgeReady(result);
                }
            },
            onBridgeReady(result){
                let self = this;
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest', {
                          "appId":result.appId,
                          "timeStamp":result.timeStamp,
                          "nonceStr":result.nonceStr,
                          "package":result.package,
                          "signType":"MD5",
                          "paySign":result.paySign
                    },
                    function (res) {
                        if(res.err_msg == "get_brand_wcpay_request:ok"){
                            alert("恭喜你，支付成功");
                            self.updateOrder(result.payId);
                        }else {
                            alert("支付失败");
                        }
                    }
                );
            },
            updateOrder(orderid){
                this.$http.post('/wechatpay/updateOrder', {
                    id : orderid,
                }).then(
                    location.reload()                  //刷新当前页面
                )
            },
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
                                this.student.sex        = data.msg.sex;
                                this.student.faculty    = data.msg.faculty;
                                this.student.profession = data.msg.profession;
                                this.student.class      = data.msg.class;
                                this.student.phone      = data.msg.phone;
                                this.student.QQ         = data.msg.QQ;
                                this.student.create_time= data.msg.create_time;
                                this.student.is_pay     = data.msg.is_pay;
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
                this.student.profession  = this.student.profession.trim();
                this.student.class       = this.student.class.trim();
                this.student.phone       = this.student.phone.trim();
                this.student.QQ          = this.student.QQ.trim();
            },
            reset_test(){
                var reg_name    = /^[\u4E00-\u9FA5]{2,4}$/;
                var reg_mobile  = /^1[3|5|8]\d{9}$/;
                var reg_phone   = /^0\d{2,3}-?\d{7,8}$/;

                if(!(reg_name.test(this.student.name))){
                    this.$message({
                        showClose: true,
                        message: '姓名错误，请重新填写',
                        type: 'warning'
                    });
                }else if(this.student.class.length < 5 || this.student.class.length > 10){
                    this.$message({
                        showClose: true,
                        message: '班级错误，请重新填写',
                        type: 'warning'
                    });
                }else if(!reg_mobile.test(this.student.phone) && !reg_phone.test(this.student.phone)){
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

                if(this.student.sex == '男')
                    this.student.sex = 1;
                else if(this.student.sex == '女')
                    this.student.sex = 0;

                var self = this;
                this.faculty.forEach(function (value,index) {
                    if(self.student.faculty == value)
                        self.student.faculty = index;
                });

                if(this.reset_test()){
                    this.$http.post('/reset',{
                        name       : this.student.name,
                        sex        : this.student.sex,
                        faculty    : this.student.faculty,
                        profession : this.student.profession,
                        class      : this.student.class,
                        student_id : this.student.student_id,
                        phone      : this.student.phone,
                        QQ         : this.student.QQ
                    }).then(
                        function (response) {
                            var data = response.data;
                            if(data.code == 0){
                                this.$message({
                                    showClose: true,
                                    message: data.msg,
                                    type: 'success'
                                });
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
                this.student.sex        = this.sex[this.student.sex];
                this.student.faculty    = this.faculty[this.student.faculty];
                this.student.student_id = this.student_id;
                this.show_meg   = false;
                this.show_reset = true;
            }
        },
    }
</script>
