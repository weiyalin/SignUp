<template>
    <div class="my_header header">
        <img :src="img_url" alt="" class="img_url">
        三月报名
        <img :src="image_url" alt="" class="image_url">
    </div>
</template>
<style>
    .my_header{
        width: 100%;
        height: 70px;
        line-height: 70px;
        text-align: center;
        font-size: 25px;
        color: white;
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