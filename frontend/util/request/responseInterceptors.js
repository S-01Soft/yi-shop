/**
 * 响应拦截
 * @param {Object} http 
 */
module.exports = (vm) => {
    uni.$u.http.interceptors.response.use((response) => {
        const data = response.data
		uni.hideLoading()
		if (data.code == 1) return data.data;
		else if (data.code == undefined) return data;
        else if (data.code !== 1) {
			switch (data.code) {
				case 9999:
					return Promise.reject(data);
				default: 
					uni.$u.toast(data.message);
					return Promise.reject(data);
					break;
			}
        }
    }, (response) => {
		let message = '网络错误';
		switch (response.statusCode) {
			case 404:
				message = '请求的内容不存在';
				break;
			case 500:
				message = '服务器内部错误';
				break;
		}
		uni.$u.toast(message);
        return Promise.reject(response)
    })
}