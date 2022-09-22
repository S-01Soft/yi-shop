import { mapState } from 'vuex'
export default {
	computed: {
		...mapState({
			appInfo: state => state.appInfo
		})
	},
	methods: {
		bindSharer(options) {
			if (options.scene) {
				options = this.$tools.getQuery(decodeURIComponent(options.scene));
			}
			let id_name = this.appInfo.config.shop.share.id_name;
			if (!this.$tools.isEmpty(options[id_name])) uni.setStorageSync(id_name, options[id_name]);
			return Promise.resolve(options)
		}
	},
	onLoad(options) {
		this._addOnLoadStartEvent('bindSharer')
	}
}