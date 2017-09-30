<template>
    <div>
        <el-menu  class="el-menu-demo" :router="true" mode="horizontal">
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
</style>