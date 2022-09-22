<template>
	<view class="content">
		<view style="padding: 100rpx 10rpx;" class="u-block">
			<u-button @click="handleClearCache" type="error">点击清除缓存</u-button>
		</view>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	export default {
		data() {
			return {
				refresh: false
			}
		},
		computed: {
			...mapState({
				tabbar: state => state.menu,
				appInfo: state => state.appInfo,
				userinfo: state => state.user.userinfo,
			})
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
		},
		methods: {
			handleClearCache() {
				let token = uni.getStorageSync('token');
				uni.clearStorageSync();
				uni.setStorageSync('token', token);
				uni.showToast({
					title: '缓存已清除'
				})
			}
		}
	}
</script>

<style scoped>
</style>
