<template>
    <div class="my_header header">
        <img :src="img_url" alt="" class="img_url">
        <img :src="image_url" alt="" class="image_url">
        <div class="text">
            <span class="hint marsign" style="margin-top: 11px; height: 33px; line-height: 33px;">三月报名</span>
            <span class="hint to" style="font-size: 14px; height: 16px; line-height: 16px;">面向2018级</span>
        </div>
    </div>
</template>
<style>
    .my_header{
        width: 100%;
        height: 70px;
        text-align: center;
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
    @media  only screen and (min-width: 500px){
    .my_header{
        height: 111px;
    }
    #background{
    background-image: url('/dist/img/background.jpg');
    background-repeat: no-repeat;
    background-size: 100% 100%;
    height: 1580px;
        }
    .text{
        font-size: 43px;
    }
    .marsign{
        height: 41px!important;
        width: 220px;
    }
    .to{
        margin-top: 1%;
        font-size: 23px!important;
        width: 125px!important;
        height: 26px!important;
    }
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
    }

</script>