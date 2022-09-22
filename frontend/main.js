import Vue from 'vue'
import App from './App'

// 引入全局uView
import uView from '@/uni_modules/uview-ui'

import store from './store'
uni.$store = Vue.prototype.$store = store

import jump from '@/common/jump'
Vue.prototype.$jump = jump

import wxApi from '@/common/wxApi'
Vue.prototype.$wxApi = wxApi

import tools from '@/common/tools'
Vue.prototype.$tools = tools

import f from '@/common/function';
Vue.prototype.$f = f;
uni.$f = f;

Vue.config.productionTip = false
Vue.prototype.uni = uni;
Vue.prototype.$http = uni.$u.http



App.mpType = 'app'
Vue.use(uView)

import page from '@/mixins/page';
Vue.mixin(page)

import checkAuth from '@/mixins/checkAuth';
Vue.mixin(checkAuth)
// #ifdef H5
import wechatShareH5 from '@/mixins/wechatShareH5';
Vue.mixin(wechatShareH5)

// #endif

// #ifdef MP
import mpShare from '@/mixins/mpShare';
Vue.mixin(mpShare)
// #endif


const app = new Vue({
    store,
    ...App
})

// import httpInterceptor from '@/request/httpInterceptor.js';

// Vue.use(httpInterceptor, app);

// 引入请求封装
require('./util/request/index')(app)

app.$mount()
