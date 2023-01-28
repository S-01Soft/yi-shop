const http = uni.$u.http;
const gql = uni.$u.gql;
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
		return new Promise(async (resolve, reject) => {
			const query = `
				mutation ($sku_id: Int!, $quantity: Int!) {
				  addCart(sku_id: $sku_id, quantity: $quantity)
				}
			`
			const result = await gql.fetch(query, form);
			const err = result.getError('addCart');
			if (err) {
				result.show(err);
				reject(err);
			}
			else resolve(result.get('addCart'));
		})
	},
	cartUpdateProduct(ctx, form) {
		return new Promise(async(resolve, reject) => {
			const query = `
				mutation ($sku_id: Int!, $quantity: Int!) {
					editCart(sku_id: $sku_id, quantity: $quantity) {
						id
						product_id
						quantity
						is_selected
					}
				}
			`
			const result = await gql.fetch(query, form);
			const err = result.getError('editCart');
			if (err) {
				result.show(err);
				reject(err)
			} else {
				resolve(result.get('editCart'))
			}
		})
	},
	cartUpdateStatus(ctx, form) {
		return new Promise(async(resolve, reject) => {
			const query = `
				mutation ($ids: [Int!]!) {
					updateCartStatus(ids: $ids) {
						id
						product_id
						is_selected
						quantity
					}
				}
			`
			const result = await gql.fetch(query, form);
			const err = result.getError('updateCartStatus');
			if (err) {
				result.show(err);
				reject(err)
			} else {
				resolve(result.get('updateCartStatus'))
			}
		})
	},
	getCartProducts({ commit }, form) {
		return new Promise(async (resolve, reject) => {
			const query = `
				{
				  carts {
				    id
				    is_selected
				    quantity
				    product {
				      id
					  on_sale
				      title
				      image
				    }
				    sku {
				      id
				      value
				      price
				    }
				  }
				}
			`
			const result = await gql.fetch(query);
			const err = result.getError('carts');
			if (err) reject(err);
			else {
				const { carts } = result.get();
				commit('SAVE_CART_PRODUCTS', carts)
				resolve(carts)
			}
		})
	},
	clearCartProducts({ commit }, form) {
		return new Promise(async (resolve, reject) => {
			const query = `
				mutation {
					clearCart
				}
			`
			const result = await gql.fetch(query);
			const err = result.getError('clearCart');
			if (err) return result.show(err);
			commit('SAVE_CART_PRODUCTS', [])
			resolve()
		})
	}
}

export default {
	state,
	mutations,
	actions
}