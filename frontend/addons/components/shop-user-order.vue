<template>
	<view class="order-section u-flex-row" :style="{margin: option.margin}">
		<view class="order-item" @click="navTo('/pages/order/index?state=0')">
			<text class="iconfont iconquanbudingdan1"></text>
			<text>全部订单</text>
		</view>
		<view class="order-item" @click="navTo('/pages/order/index?state=1')">
			<text class="iconfont icondaifukuan"></text>
			<text>待付款</text>
		</view>
		<view class="order-item" @click="navTo('/pages/order/index?state=2')">
			<text class="iconfont icondaishouhuo"></text>
			<text>待发货</text>
		</view>
		<view class="order-item" @click="navTo('/pages/order/index?state=3')">
			<text class="iconfont icondaishouhuo"></text>
			<text>待收货</text>
		</view>
		<view class="order-item" @click="navTo('/pages/order/index?state=5')">
			<text class="iconfont iconicon4"></text>
			<text>售后</text>
		</view>
	</view>

</template>

<script>
	import { mapState } from 'vuex';
	export default {
		name: 'shop-user-order',
		props: {
			option: {},
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo
			})
		},
		methods: {
			navTo(url) {
				if (!this.userinfo.id) {
					url = '/pages/public/login';
				}
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang="scss" scoped>

	%flex-center {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	%section {
		display: flex;
		justify-content: space-around;
		align-content: center;
		background: #fff;
		border-radius: 10upx;
	}

	.order-section {
		@extend %section;
		padding: 28upx 0;
		margin-top: 20upx;

		.order-item {
			@extend %flex-center;
			width: 120upx;
			height: 120upx;
			border-radius: 10upx;
			font-size: $font-sm;
			color: $font-color-dark;
		}

		.iconfont {
			font-size: 48upx;
			margin-bottom: 18upx;
			color: #fa436a;
		}

		.icon-shouhoutuikuan {
			font-size: 44upx;
		}
	}
</style>
