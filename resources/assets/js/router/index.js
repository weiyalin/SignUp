import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            path: '/:nav?',
            component: resolve => void(require(['../components/sign/Sign.vue'], resolve))
        },
        {
            path: '/select/:paystatus?',
            component: resolve => void(require(['../components/select/Select.vue'], resolve))
        },
    ]
})