import { mapActions } from 'vuex';
export default {
	methods: {
		...mapActions(['isLogin']),
		async _loggedOut(options) {
			if (this.LOGGED_OUT && (await this.isLogin())) {
				uni.switchTab({
					url: '/pages/user/index'
				})
			}
			return Promise.resolve(options)
		}
	},
	async onLoad(options) {
		this.registerLoadStartEvent('_loggedOut')
	}
}