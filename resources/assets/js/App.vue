<template>
    <div>
        <pageHeader></pageHeader>
        <div>
            <div class="page-menu">
                <pageMenu></pageMenu>
            </div>
            <div class="page-content">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import pageHeader from './components/layout/header.vue'
    import pageMenu from './components/layout/menu.vue'
    export default{
        components: {
            pageHeader,
            pageMenu,
        }
    }
</script>
