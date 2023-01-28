import Vue from 'vue'
import Vuex from 'vuex'
const http = uni.$u.http;
const gql = uni.$u.gql;
import user from './modules/user'
import cart from './modules/cart';
import product from './modules/product';
import share from './modules/share';
Vue.use(Vuex)

const modules = {
	user, cart, product, share
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
			return new Promise(async (resolve, reject) => {
				const query = `
					query {
						appInfo {
							plugins, config
						}
					}
				`
				const result = await gql.fetch(query);
				const { appInfo } = result.get();
				commit('SAVE_APP_INFO', appInfo);
				resolve(appInfo);
				// http.post('shop/api/index/appInfo').then(data => {
				// 	uni.setStorageSync('appInfo', data);
				// 	commit('SAVE_APP_INFO', data);
				// 	resolve(data);
				// })
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
