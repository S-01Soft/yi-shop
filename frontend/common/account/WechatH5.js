import Base from './Base'
import Tools from '../tools'
import Config from '@/common/config';

export default class extends Base {
	constructor() {
	    super();
		this.appCode = 'app_id';
	}
	async login(referer) {
		const scene = this.getScene();
		let url = Config.domain + `/third/api/vendor/profile?vendor=WechatH5&scene=${scene}`;
		let base = Config.domain + uni.$f.getAppInfo('config.shop.base.h5_base_path') + '#/pages/public/login';
		if (!!referer) url += '&referer=' + encodeURIComponent(base + '?referer=' + encodeURIComponent(referer))
		else url += '&referer=' + encodeURIComponent(base)
		window.location.href = url;
	}
	async login2() {
		let openId = uni.getStorageSync('openId');
		if (!openId) {
			const scene = this.getScene();
			uni.$u.http.post('/third/api/openid/get', {scene}).then(data => {
				if (data) uni.setStorageSync('openId', data);
				else location.href = `/third/api/vendor/profile?scene=${scene}&vendor=WechatH5&scope=snsapi_base&referer=` + encodeURIComponent(location.href);
			})
		}
	}
}
