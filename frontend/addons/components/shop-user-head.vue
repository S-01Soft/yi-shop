<template>
	<view class="u-block">
		<view v-if="isLogged" class="userinfo-section"
			:style="{background: option.image ? 'url(' + $tools.fixurl(option.image) + ') 100% no-repeat' : option.background}">
			<view class="info-section">
				<u-avatar :src="userinfo && $tools.fixurl(userinfo.avatar)"></u-avatar>
				<view class="info">
					<view class="nickname" :style="{color: option.color}">
						{{userinfo && userinfo.nickname}}
					</view>
				</view>
			</view>
		</view>
		<view v-if="!isLogged" class="u-flex-row u-flex-js-center">
			<view class="section not-login-section">
				<view class="b-btn" @click="toLogin()">
					去登录
				</view>
			</view>
		</view>

	</view>
</template>

<script>
	import {
		mapState
	} from 'vuex';
	import Refresh from '@/mixins/refresh';
	export default {
		name: 'shop-user-head',
		mixins: [Refresh],
		props: {
			option: {},
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo,
				isLogged: state => state.user.isLogged
			})
		},
		mounted() {
			
		},
		methods: {
			toLogin() {
				uni.navigateTo({
					url: '/pages/public/login'
				})
			}
		}
	}
</script>


<style lang="scss" scoped>
	.b-btn {
		text-align: center;
		font-size: 28upx;
		color: #fff;
		border-radius: 30upx;
		background: #fa436a;
		z-index: 1;
		padding: 10upx 30upx;
	}


	.info-section {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		
		.info {
			margin-left: 20rpx;
			
			.nickname {
				font-size: 28rpx;
				font-weight: 600;
			}
		}
	}
	.not-login-section {
		padding: 30rpx;
	}
	.userinfo-section {
		position: relative;
		display: flex;
		flex-direction: row;
		
		align-items: center;
		flex-wrap: wrap;
		padding: 22upx 40upx;
		background-size: cover !important;
	}

	.not-logic-section {
		padding: 40upx 0;
	}
</style>
