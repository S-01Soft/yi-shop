import { mapActions } from 'vuex';
export default {
	methods: {
		...mapActions(['isLogin']),
		async _checkAuth(options) {
			if (this.CHECK_AUTH && !(await this.isLogin())) {
				uni.redirectTo({
					url: '/pages/public/login'
				})
				return Promise.reject(options);
			}
			return Promise.resolve(options)
		}
	},
	async onLoad(options) {
		this.registerLoadStartEvent('_checkAuth')
	}
}