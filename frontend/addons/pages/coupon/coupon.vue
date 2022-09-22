<template>
	<view class="" v-if="coupon.id">
		<view class="coupon-wrap u-flex-center u-flex-align-center">
			<view class="desc">
				{{coupon.desc}}
			</view>
			<view class="discount u-block">
				<text class="l">￥</text> <text class="r">{{coupon.money}}</text> 
			</view>
			<view class="name mt-20">
				{{coupon.name}}
			</view>
			<view class="stock mt-20">
				<text v-if="coupon.stock == -1">数量充足</text>
				<text v-else>剩{{coupon.stock}}张</text>				
			</view>
			<view class="end-time mt-20 text-desc u-block">
				<text class="u-p-r-6">{{coupon.use_start_time_text}}</text> ~ <text class="u-p-l-6">{{coupon.use_end_time_text}}</text>
			</view>
			<view class="mt-20 u-block w-100 u-flex-align-center">
				<u-button v-if="!coupon.has" type="error" size="medium" shape="circle" @click="submit">立即领取</u-button>
				<u-button v-else size="medium"  :disabled="true" shape="circle">已领取</u-button>
			</view>
		</view>
		
		<view class="coupon-rule mt-40 u-flex-center u-flex-align-center" v-if="list.length">
			<view class="" v-if="coupon.type == 0">
				全场商品可用
			</view>
			<view v-if="coupon.type == 1" class="w-100">
				<view class="mb-10">限以下分类使用：</view>
				<view class="u-flex-row">
					<view v-for="(item,index) in list" :key="index" @click="goCategory(item.id)" class="mt-10 mb-10 ml-10 mr-10">
						<text>{{item.name}}</text>					
					</view>
				</view>
			</view>
			<view class="" v-if="coupon.type == 2">
				限以下商品使用： 
				<view class="mt-10 text-center" v-for="(item,index) in list" :key="index" @click="goProduct(item.id)">{{item.title}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex';
	export default {
		data() {
			return {
				coupon: {},
				list: []
			}
		},
		computed: {
			...mapState({
				user: state => state.user.userinfo
			})
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.getInfo(options.code);
		},
		methods: {
			async getInfo(code) {
				this.coupon = await this.$http.post('shopcoupon/api/coupon/show', {code: code})
				this.list = await this.$http.post('shopcoupon/api/coupon/detail', {code: code})
			},
			goCategory(id) {
				uni.navigateTo({
					url: '/pages/product/list?cat_id=' + id
				})
			},
			goProduct(id) {
				uni.navigateTo({
					url: '/pages/product/product?id=' + id
				})
			},
			submit() {
				if (!this.user.id) uni.navigateTo({
					url: '/pages/public/login'
				})
				this.$http.post('shopcoupon/api/coupon/addCoupon', {id: this.coupon.code}).then(data => {
					uni.showToast({
						title: '领取优惠券成功'
					});
					setTimeout(() => {
						uni.navigateTo({
							url: '/pages/user/coupon'
						})						
					}, 1000)
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.coupon-wrap {
		margin: 160rpx 10% 0 10%;
		width: 80%;
		padding: 40rpx 20rpx;
		border-radius: 14rpx;
		background-color: #ffe5e3;
		position: relative;
		
		.desc {
			position: absolute;
			top: 0;
			left: 0;
			background-image: linear-gradient(180deg, #f33d25, #ff8f57);
			white-space: nowrap;
			padding: 4rpx 10rpx;
			color: #fff;
			border-radius: 14rpx 0 14rpx 0;
		}
		
		.discount {
			color: #f2270c;
			
			.l {
				font-size: 30rpx;
				
			}
			
			.r {
				font-size: 40rpx;
				font-weight: 700;
			}
		}
		
		.name, .stock {
			font-size: 26rpx;
			color: #999;
		}
		
	}
	
	.coupon-rule {
		font-size: 26rpx;
		color: #999;
		margin: 100rpx 10% 0 10%;
	}
	
	.text-desc {
		font-size: 26rpx;
		color: #999;
	}
	
	.text-bold {
		font-weight: 700;
	}
</style>
