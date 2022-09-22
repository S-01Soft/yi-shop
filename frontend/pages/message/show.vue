<template>
	<view class="container">
		<view class="content">
			<u-parse :content="message.content" :domain="appInfo.config.shop && appInfo.config.shop.base.domain" ></u-parse>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				id: 0,
				message: {},
				CHECK_AUTH: true
			}
		},
		computed: {
			...mapState({
				appInfo: state => state.appInfo
			}),
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.id = options.id;
			this.getMessage()
		},
		methods: {
			async getMessage() {
				this.message = await this.$http.post('shop/api/user/getMessage', {id: this.id})
				uni.setNavigationBarTitle({
					title: this.message.title
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	page {
		background-color: #fff;
	}
	.container {
		padding: 10rpx;
		.title {
			font-size: 34rpx;
			font-weight: 700;
			margin: 30rpx;
		}
		.time {
			font-size: 26rpx;
			color: #888;
			margin: 10rpx;
		}
		.content {
			padding-top: 20rpx;
			text-indent: 30rpx;
		}
	}
</style>
