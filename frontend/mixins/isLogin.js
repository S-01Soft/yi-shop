import { mapActions } from 'vuex'
export default {
	data() {
		return {
			isLogged: false
		}
	},
	async mounted() {
		this.isLogged = await this.isLogin();
	},
	methods: {
		...mapActions(['isLogin'])
	}
}