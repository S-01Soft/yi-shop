import Vue from 'vue'
import Vuex from 'vuex'
const http = uni.$u.http;
import user from './modules/user'
import cart from './modules/cart';
import product from './modules/product';
import share from './modules/share';
import invoice from './modules/invoice';
Vue.use(Vuex)

const modules = {
	user, cart, product, share, invoice
};
const store = new Vuex.Store({
	modules,
	state: {
		appInfo: {
			config: {}
		},
		areas: null
	},
	mutations: {
		SAVE_APP_INFO(state, data) {
			state.appInfo = Object.assign(state.appInfo, data);
			uni.$emit('AppInfoChange', state.appInfo)
		},
		SAVE_PAGE_SETTING(state, data) {
			state.appInfo.page_setting = data;
		},
		SAVE_AREAS(state, data) {
			state.areas = data
		},
	},
	actions: {
		getAppInfo({ commit }) {
			return new Promise((resolve, reject) => {
				http.post('shop/api/index/appInfo').then(data => {
					uni.setStorageSync('appInfo', data);
					commit('SAVE_APP_INFO', data);
					resolve(data);
				})
			})
		},
		getAreas({ state, commit }) {
			return new Promise((resolve, reject) => {
				if (state.areas) resolve(state.areas)
				else {
					http.get('area/api/index/getArea').then(data => {
						commit('SAVE_AREAS', data)
						resolve(data)
					}).catch(e => { reject(e) })
				}
			})
		},
	}
})

export default store
