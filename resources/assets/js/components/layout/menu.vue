<template>
    <div class="ourmenu">
        <el-menu class="el-menu-demo" :router="true" mode="horizontal">
            <el-menu-item index="/" class="my_menu">我要报名</el-menu-item>
            <el-menu-item index="select" class="my_menu">报名信息</el-menu-item>
        </el-menu>
    </div>
</template>
<style>
    .my_menu{
        width: 50%;
        text-align: center;
        font-size: 18px;
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
