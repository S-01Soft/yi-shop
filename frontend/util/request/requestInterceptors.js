/**
 * 请求拦截
 * @param {Object} http
 */
import { mapState } from 'vuex'
module.exports = (vm) => {
	uni.$u.http.interceptors.request.use((config) => {
		if (config.custom.loading) {
			uni.showLoading({
				title: config.custom.loading.text || '请求中……'
			})
		}
		const token = uni.getStorageSync('token');
		let id_name = vm.appInfo?.config?.shop?.share?.id_name;
		if (id_name) {
			let sid = uni.getStorageSync(id_name);
			if (sid) config.header[id_name] = sid;
		}
		config.header['Token'] = token;
		config.header['Platform'] = vm.$tools.getPlatform();
		return config;
	}, (config) => Promise.reject(config))
}
