import Tools from '@/common/tools'
const http = uni.$u.http;
const state = {
	
}
const mutations = {
	
}
const actions = {
	getProductInfo({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.post('shop/api/product/index', form).then(res => {
				resolve(res)
			}).catch(e => {
				reject(e)
			})
		})
	}
}

export default {
	state,
	mutations,
	actions
}