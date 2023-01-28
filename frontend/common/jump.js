import config from '@/common/config';

let jump = function(option) {
	let type = option.type == undefined ? 0 : option.type;
	type = parseInt(type);
	switch (type) {
		case 0: {
			uni.navigateTo({
				url: option.target
			})
			break;
		}
		case 1: {
			uni.navigateTo({
				url: '/pages/product/product?id=' + option.target
			})
			break;
		}
		case 2: {
			uni.navigateTo({
				url: '/pages/product/list?cat_id=' + option.target
			})
			break;
		}
		case 3: {
			let url = '/' + option.target;
			if (option.params) url += '?' + option.params;
			uni.navigateTo({
				url
			})
			break;
		}
		case 4: {
			// #ifndef H5
			uni.navigateTo({
				url: '/pages/webview/webview?url=' + encodeURIComponent(option.target)
			})			
			// #endif
			// #ifdef H5
			window.location.href = option.target;
			// #endif
			break;
		}
		case 5: {
			let url = config.baseUrl + '/shop/index/article/index?id=' + option.target;
		
			uni.navigateTo({
				url: '/pages/webview/index?url=' + encodeURIComponent(url)
			})
			
			break;
		}
		case 6: {
			uni.switchTab({
				url: '/' + option.target
			});
			break;
		}
		case 7: {
			let url = option.target;
			if (option.params) {
				if (url.indexOf('?') == -1) url += `?${option.params}`
				else url += option.params
			}
			let token = uni.getStorageSync('token');
			let target = config.baseUrl + '/shop/index/index/jump?url=' + encodeURIComponent(encodeURIComponent(url))
			if (option.token) target += '&token=' + token;
			// #ifdef H5
			window.location.href = target;
			// #endif
			// #ifndef H5
			uni.navigateTo({
				url: '/pages/webview/index?url=' + encodeURIComponent(target),
			});	
			// #endif
			break;
		}
		case 8: {
			uni.navigateTo({
				url: '/pages/page/index?id=' + option.target
			})
		}
		default : {
			break;
		}
	}
}

export default jump