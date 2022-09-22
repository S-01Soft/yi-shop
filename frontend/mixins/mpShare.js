import { mapState } from 'vuex';
export default {
	computed: {
		...mapState({
			appInfo: state => state.appInfo,
			user: state => state.user.userinfo
		})
	},
	onShareAppMessage() {
		let page = getCurrentPages()[getCurrentPages().length - 1];
		let options = page.options;
		if (this.user && !this.$tools.isEmpty(this.user.uid)) {
			options[this.appInfo.config.shop.share.id_name] = this.user.uid;
		}
		let path = '/' + page.route;
		if (!this.$tools.isEmpty(options)) path += '?' + this.$tools.queryStringify(options);
		let title = this.appInfo.config.shop.share.title;
		let imageUrl = this.appInfo.config.shop.share.img;
		return {
			title,
			path,
			imageUrl
		}
	}
}