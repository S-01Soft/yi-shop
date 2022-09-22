<template>
	<view class="order-detail-content">
		<view class="state-tip" v-if="orderInfo.order_sn">
			{{orderInfo.state_tip}}
		</view>
		<view class="list">
			<view class="item" v-for="(item, index) in orderInfo.products" :key="index">
				<image class="image" :src="$tools.fixurl(item.images[0])" @click="toProduct(item)"></image>
				<view class="text">
					<view class="title">
						{{item.title || ''}}
					</view>
					<view class="spec">
						{{item.attributes || ''}}
					</view>
				</view>
				<view class="append">
					<view class="price">
						￥{{item.price || '0.00'}}
					</view>
					<view class="quantity">
						x {{item.quantity || 0}}
					</view>
					<view class="action-box" v-if="orderInfo.status == 3 && item.buyer_comment==0">
						<button class="action-btn recom" @click="toComment(item)">评价</button>
					</view>
				</view>
			</view>
		</view>
		<view class="total-box">
			<view class="desc-item">
				<text>订单编号</text>
				<text>{{orderInfo.order_sn}}</text>
			</view>
			<!-- <view class="desc-item">
				<text>订单状态</text>
				<text>{{orderInfo.state_tip}}</text>
			</view> -->
			<view class="desc-item" v-if="orderInfo.express_no">
				<text>快递单号</text>
				<text>{{orderInfo.express_name}} {{orderInfo.express_no}}</text>
			</view>
			<view class="desc-item">
				<text>商品总价</text>
				<text>￥ {{orderInfo.products_price || '0.00'}}</text>
			</view>
			<view class="desc-item">
				<text>运费</text>
				<text>￥{{orderInfo.delivery_price || '0.00'}}</text>
			</view>
			<view class="desc-item coupon" v-if="orderInfo.discount_price > 0">
				<text>优惠</text>
				<text>- ￥{{orderInfo.discount_price || '0.00'}}</text>
			</view>
			<view v-if="orderInfo.money_price > 0" class="desc-item coupon">
				<text>余额抵扣</text>
				<text>- ￥{{orderInfo.money_price || '0.00'}}</text>
			</view>
			<view v-if="orderInfo.score_price > 0" class="desc-item coupon">
				<text>积分抵扣</text>
				<text>- ￥{{orderInfo.score_price || '0.00'}}</text>
			</view>
			<view v-if="orderInfo.is_pay_deposit > 0" class="desc-item coupon">
				<text>定金</text>
				<text>- ￥{{orderInfo.deposit_price || '0.00'}}</text>
			</view>
			<view class="desc-item total">
				<text>应付总额</text>
				<text>￥{{orderInfo.order_price || '0.00'}}</text>
			</view>
			<view class="desc-item pay-total">
				<text>实付款</text>
				<text>￥{{orderInfo.payed_price || '0.00'}}</text>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex';
	export default {
		data() {
			return {
				checkAuth: true,
				orderInfo: {},
				order_sn: null,
				state: 0
			}
		},
		computed: {
			...mapState({
				user: state => state.user.userinfo
			})
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.order_sn = options.order_sn
			this.state = options.state
			this.loadData()
		},
		methods: {
			loadData() {
				this.$http.get('shop/api/order/info', {params: {
					order_sn: this.order_sn
				}}).then(data => {
					this.orderInfo = data
				})
			},
			toProduct(item) {
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.product_id
				})
			},
			toComment(item) {
				uni.navigateTo({
					url: '/pages/order/comment?id=' + item.id + '&state=' + this.state
				})
			}
		}
	}
</script>

<style lang="scss">
	page {
		background-color: #FFFFFF;
	}
	.state-tip {
		padding: 80rpx 60rpx;
		background: linear-gradient(to right, #f47272, #cd0326);
		font-size: 40rpx;
		border-radius: 10rpx;
		color: #F8F8F8;
	}
	.order-detail-content {
		font-size: $font-base;
		color: #555;
		padding: 10upx;

		.list {
			display: flex;
			flex-direction: column;
			.item {
				display: flex;
				flex-direction: row;
				padding: 10upx;

				image {
					width: 100px;
					height: 100px;
					border-radius: 20upx;
				}

				.text {
					display: flex;
					flex-direction: column;
					flex: 1;
					padding: 10upx;

					.title {
						display: -webkit-box;
						-webkit-box-orient: vertical;
						-webkit-line-clamp: 2;
						line-height: 150%;
						overflow: hidden;
					}

					.spec {
						margin: 10upx 0;
						font-size: $font-base - 2upx;
						color: gray;
					}
				}

				.append {
					display: flex;
					flex-direction: column;
					justify-content: center;
					align-items: center;
					padding: 0 16upx;

					.quantity {
						margin-top: 10upx;
						font-size: $font-base - 4upx;
						color: gray;
					}
				}
			}
		}

		.total-box {
			padding: 20upx;

			.desc-item {
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				margin: 20upx 0;
				color: gray;
				text {
					font-size: $font-base - 2upx;
				}
				&.total text {
					color: #555;
					font-size: $font-base + 2upx;
				}
				&.pay-total {
					margin-top: 16upx;
					padding-top: 16upx;
					border-top: 1px solid #ccc;
					text {
						color: #444;
						font-size: $font-base + 2upx;
						font-weight: 700;
					}
				}
				&.coupon {
					color: #fa436a;
				}


			}
		}
		.action-box{
			display: flex;
			justify-content: flex-end;
			align-items: center;
			height: 100upx;
			position: relative;
			padding-right: 30upx;
		}
		.action-btn{
			width: 160upx;
			height: 60upx;
			margin: 0;
			margin-left: 24upx;
			padding: 0;
			text-align: center;
			line-height: 60upx;
			font-size: $font-sm + 2upx;
			color: $font-color-dark;
			background: #fff;
			border-radius: 100px;
			&:after{
				border-radius: 100px;
			}
			&.recom{
				background: #fff9f9;
				color: $base-color;
				&:after{
					border-color: #f7bcc8;
				}
			}
		}
	}
</style>
