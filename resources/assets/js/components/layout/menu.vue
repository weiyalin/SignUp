<template>
    <div class="ourmenu">
        <el-menu :default-active="activeIndex" class="el-menu-demo" :router="true" mode="horizontal">
            <el-menu-item index="/"  class="my_menu">我要报名</el-menu-item>
            <el-menu-item index="select"  class="my_menu">报名信息</el-menu-item>
        </el-menu>
        <p v-if="$route.params.paystatus == undefined">
            {{  this.remains() }}
        </p>
        <p v-if="$route.params.paystatus == '报名成功'">
            {{  this.remain($route.params.paystatus) }}
        </p>
        <p v-if="$route.params.paystatus == '报名失败'">
            {{  this.faileremain($route.params.paystatus) }}
        </p>
    </div>
</template>
<script>
    //重写alert() 防止输出域名
    window.alert = function(name){
        var iframe = document.createElement("IFRAME");
        iframe.style.display="none";
        iframe.setAttribute("src", 'data:text/plain,');
        document.documentElement.appendChild(iframe);
        window.frames[0].window.alert(name);
        iframe.parentNode.removeChild(iframe);
    };
    window.confirm = function (message) {
        var iframe = document.createElement("IFRAME");
        iframe.style.display = "none";
        iframe.setAttribute("src", 'data:text/plain,');
        document.documentElement.appendChild(iframe);
        var alertFrame = window.frames[0];
        var result = alertFrame.window.confirm(message);
        iframe.parentNode.removeChild(iframe);
        return result;
    };
    export default {
        data() {
            return {
                activeIndex: '/',
            };
        },
        methods: {
            remain(msg){
                alert(msg);
                location.href='http://www.lishanlei.cn/#/select';
            },
            faileremain(msg){
                alert(msg);
                location.href='http://www.lishanlei.cn';
            },
            remains(){
                var aturl = window.location.href;
                if(aturl == 'http://www.lishanlei.cn/#/select'){
                    this.activeIndex = 'select'
                }
            }
        },
    }
</script>
<style scoped>
    .is-active {
        border-bottom: 5px solid;
    }
    .my_menu{
        width: 50%;
        text-align: center;
        font-size: 18px;
    }
    .el-menu-item:hover{
        border-bottom: 5px solid #17195f;
    }
    @media  only screen and (min-width: 500px){
        .ourmenu {
          width: 48%;
          margin-left: 25%;
        }
        .el-menu-demo{
          background-color: rgba(0,0,0,1);
        }
        .my_menu{
            font-size: 25px;
            color: rgba(139,156,197,1) !important;
        }
    }
</style>



