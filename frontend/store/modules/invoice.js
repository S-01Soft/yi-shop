import Tools from '@/common/tools'
const http = uni.$u.http;
const state = {
	list: []
}
const mutations = {
	SAVE_INFO(state, data) {
		state.list = data
	}
}
const actions = {
	getInvoiceInfo({ commit }) {
		return new Promise((resolve, reject) => {
			http.get('shop/api/invoice/info').then(data => {
				commit('SAVE_INFO', data)
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