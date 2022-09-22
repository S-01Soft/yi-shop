import WechatH5 from './account/WechatH5.js'
import WechatMiniApp from './account/WechatMiniApp.js'
import WechatApp from './account/WechatApp.js'
export default {
	services: {
		WechatMiniApp: function() {
			return new WechatMiniApp()
		},
		WechatApp: function() {
			return new WechatApp()
		},
		WechatH5: function() {
			return new WechatH5()
		},
	},
	init: function(key) {
		return this.services[key]();
	}
}