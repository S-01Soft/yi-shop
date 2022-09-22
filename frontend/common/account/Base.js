import wxApi from '../wxApi'

export default class {
	
	constructor() {
		this.$wxApi = wxApi;
	}
	async getAppId() {
		let appId = uni.getStorageSync('appId')
		if (!appId) {
			let data = await uni.$u.http.post('shop/api/index/getConfig', {
				code: [this.appCode],
				type: 'wechat'
			})
			appId = data[this.appCode]
			uni.setStorageSync('appId', appId)
		}
		return appId
	}
	
	getScene() {
		const appInfo  = uni.$store.state.appInfo;
		return appInfo.config.shop && appInfo.config.shop.third.scene;
	}
}
