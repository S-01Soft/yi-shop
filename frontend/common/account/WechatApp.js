import Base from './Base'

export default class extends Base {
	async login(data = {}, success, fail) {
		return new Promise(async (resolve, reject) => {
			plus.oauth.getServices(async services => {
				let weixinService = null
				if (services && services.length) {
					for (var i = 0, len = services.length; i < len; i++) {
						if (services[i].id === 'weixin') {
							weixinService = services[i];
							break;
						}
					}
					if (!weixinService) {
						fail && fail('没有微信登录授权服务');
						return;
					}
					// http://www.html5plus.org/doc/zh_cn/oauth.html#plus.oauth.AuthService.authorize
					
					weixinService.authorize(event => {
						// success && success(event.code)
						const scene = this.getScene();
						uni.$u.http.post('third/api/vendor/login', {code: event.code, vendor: 'WechatApp', scene}).then(result => {
							success && success(result)
						}).catch(e => {
							uni.showModal({
								title: '提示',
								content:  JSON.stringify(e),
								showCancel: false,
								confirmText: '确定'
							});
							fail && fail(e)
						})
					}, function(error) {
						fail && fail('authorize fail:' + JSON.stringify(error));
					}, {
						// http://www.html5plus.org/doc/zh_cn/oauth.html#plus.oauth.AuthOptions
					});
				} else {
					fail && fail("无可用的登录授权服务")
				}
			}, e => {
				uni.showToast({
					title: JSON.stringify(e)
				})
				fail && fail(e)
			})
		})
		
	}
}
