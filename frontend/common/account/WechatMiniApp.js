import Base from './Base'

export default class extends Base {
	async isLogin() {
		return new Promise((resolve, reject) => {
			wx.checkSession({
				success() {
					resolve(true)
				},
				fail() {
					resolve(false)
				}
			})
		})
	}
	async getScope(code) {

		return new Promise((resolve, reject) => {
			wx.getSetting({
				success(res) {
					if (!res.authSetting[code]) {
						wx.authorize({
							scope: code,
							success() {
								resolve()
							},
							fail(res) {
								resolve()
							}
						})
					} else {
						resolve()
					}
				}
			})
		})
	}
	async getOpenId() {
		return new Promise(async (resolve, reject) => {
			if (!uni.getStorageSync('openId') || !(await this.isLogin())) {
				let data = await this.login2()
				if (data.openid) resolve(data.openid);
				else reject(data)
			} else {
				resolve(uni.getStorageSync('openId'));
			}
		})
	}

	async login(data = {}) {
		data.vendor = 'WechatMiniApp'
		data.openid = await this.getOpenId();
		data.scene = this.getScene();
		return new Promise((resolve, reject) => {
			uni.$u.http.post('third/api/vendor/login', data).then(result => {
				resolve(result)
			}).catch(e => {
				reject(e)
			})
		})
	}

	async login2(info = {}) {
		var self = this;
		return new Promise((resolve, reject) => {
			wx.login({
				success(res) {
					if (res.code) {
						info.code = res.code
						info.vendor = 'WechatMiniApp'
						info.scene = self.getScene();
						uni.$u.http.post('third/api/vendor/login2', info).then(result => {
							resolve(result)
						}).catch(e => {
							reject(e)
						})
					}
				}
			})
		})
	}
}
