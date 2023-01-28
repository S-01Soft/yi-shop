import Tools from '@/common/tools'
const http = uni.$u.http;
const gql = uni.$u.gql;
const state = {
	userinfo: {},
	isLogged: false,
	address: []
}
const mutations = {
	SAVE_USERINFO(state, data) {
		state.userinfo = data || {};
		state.isLogged = !Tools.isEmpty(data);
	},
	SAVE_ADDRESS(state, data) {
		state.address = data
	}
}
const actions = {
	login({ commit, dispatch }, form) {
		return new Promise((resolve, reject) => {
			http.post('shop/api/index/login', form).then(data => {
				uni.setStorageSync('token', data.token)
				dispatch('getUserinfo')
				resolve(data)
			}).catch(e => {
				reject(e)
			})
		})
	},
	logout({ commit }) {
		return new Promise((resolve, reject) => {
			http.post('shop/api/index/logout').then(res => {
				uni.removeStorageSync('token')
				commit('SAVE_USERINFO', {})
				resolve()
			}).catch(e => { reject(e) })
		})
	},
	isLogin({ dispatch }) {
		return new Promise( async (resolve, reject) => {
			if (!Tools.isEmpty(state.userinfo)) {
				resolve(true);
			} else {
				dispatch('getUserinfo').then(() => {
					resolve(!Tools.isEmpty(state.userinfo));
				}).catch(e => {
					resolve(false)
				});
			}
		})
	},
	getUserinfo({ commit }) {
		return new Promise(async (resolve, reject) => {
			const query = `
				{
				  userinfo {
						id
						uid
						nickname
						username
						avatar
						money
						score
				  }
				}
			`
			const result = await gql.fetch(query);
			const error = result.getError('userinfo');
			if (error) {
				reject(error)
			} else {
				const { userinfo } = result.get();
				commit('SAVE_USERINFO', userinfo)
				resolve(userinfo)
			}
		})
	},
	getUserAddress({ commit }) {
		return new Promise((resolve, reject) => {
			http.get('area/api/user/getAddress').then(data => {
				commit('SAVE_ADDRESS', data)
				resolve(data)
			}).catch(e => { reject(e) })
		})
	}
}
export default {
	state,
	mutations,
	actions
}