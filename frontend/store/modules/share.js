const http = uni.$u.http;

const state = {
	config: null
}


const mutations = {
	SAVE_SIGN_PARAMS(state, data) {
		state.config = data;
	}
}

const actions = {
	getShareSignParams({ commit }, form){
		return new Promise((resolve, reject) => {
			http.post('third/api/vendor/jsConfig', { scene: uni.$f.scene() }).then(data => {
				commit('SAVE_SIGN_PARAMS', data);
				resolve(data);
			})
		})
	}
}

export default {
	state,
	mutations,
	actions
}