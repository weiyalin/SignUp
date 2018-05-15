<template>
    <div class="my_header header">
        <img :src="img_url" alt="" class="img_url">
        <img :src="image_url" alt="" class="image_url">
        <div class="text">
            <span class="hint" style="margin-top: 12px">三月报名</span>
            <span class="hint" style="font-size: 10px;">面向2017级</span>
        </div>
    </div>
</template>
<style>
    .my_header{
        width: 100%;
        height: 70px;
        text-align: center;
        /*background-color: #17195f;*/
        position: relative;
        background:url("/dist/img/star.gif");
        background-size: cover;
    }
    .img_url{
        width: 100px;
        height: 100px;
        position: absolute;
        left: 0;
        top: 10px;
    }
    .image_url{
        width: 100px;
        height: 100px;
        position: absolute;
        right: 0;
        bottom:0;
    }
    .text{
        width: 100%;
        height: 100%;
        font-size: 25px;
        color: white;
        position: absolute;
        left: 0;
        top: 0;
    }
    .hint{
        display: block;
        width: 100px;
        margin: 0 auto;
    }
</style>
<script>
    export default {
        data() {
            return {
                img_url   : '/dist/img/rabit.png',
                image_url : '/dist/img/moon.png'
            }
        },
        methods: {

        },
    }
</script>