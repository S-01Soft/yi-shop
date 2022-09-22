const http = uni.$u.http;
const state = {
	products: [],
	isTempProduct: 0,
	tempProducts: [],
	selectedAmount: 0
}

const mutations = {
	SAVE_CART_PRODUCTS(state, data) {
		// data.forEach((item, index) => {
		// 	data[index].is_selected = true
		// })
		if (state.isTempProduct) {
			state.tempProducts = data
		} else {
			state.products = []
			setTimeout(() => {
				state.products = data
			}, 2)
		}
	},
	SAVE_CART_PRODUCT_QUANTITY(state, data) {
		state.products[data.index].quantity = data.quantity
	},
	CART_PRODUCT_CHECK_TOGGLE(state, index) {
		state.products[index].is_selected = !state.products[index].is_selected
	},
	CART_PRODUCT_CHECK_ALL(state, is_selected) {
		state.products.forEach((item, index) => {
			state.products[index].is_selected = !is_selected
		})
	},
	SAVE_IS_TEMP_PRODUCT(state, type) {
		if (type) state.isTempProduct = 1
		else state.isTempProduct = 0
	}
}

const actions = {
	cartAddProduct(ctx, form) {
		return new Promise((resolve, reject) => {
			http.post('shop/api/cart/add', form).then(data => {
				resolve(data)
			}).catch(e => { reject(e) })
		})
	},
	cartUpdateProduct(ctx, form) {
		return new Promise((resolve, reject) => {
			http.post('shop/api/cart/edit', form).then(data => {
				resolve(data)
			}).catch(e => { reject(e) })
		})
	},
	cartUpdateStatus(ctx, form) {
		return new Promise((resolve, reject) => {
			http.post('shop/api/cart/updateStatus', form).then(data => {
				resolve(data)
			}).catch(e => { reject(e) })
		})
	},
	getCartProducts({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.get('shop/api/cart', {params: form}).then(data => {
				commit('SAVE_CART_PRODUCTS', data)
				resolve(data)
			}).catch(e => { reject(e) })
		})
	},
	clearCartProducts({ commit }, form) {
		return new Promise((resolve, reject) => {
			http.post('shop/api/cart/clear').then(data => {
				commit('SAVE_CART_PRODUCTS', [])
				resolve(data)
			}).catch(e => {reject(e)})
		})
	}
}

export default {
	state,
	mutations,
	actions
}