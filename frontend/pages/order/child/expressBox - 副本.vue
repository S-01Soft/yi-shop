<template>
	<u-popup :show="show" @open="open" @close="close" mode="center" :round="10" :closeable="true" customStyle="width: 85%">
		<div class="content">			
			<view class="e_info u-flex-row" v-if="!loading && !error">
				<view class="e_name">
					{{order.express && order.express.name}}
				</view>
				<view class="e_code">
					{{order.express_no}}
				</view>
			</view>
			<view class="list" v-if="!loading && !error">
				<view class="item" v-for="(item, index) in content" :key="index">
					<text>{{item.content}}</text>
					<text>{{item.time}}</text>
					<text class="ic"><text class="i"></text></text>
				</view>
			</view>
			<view class="loading text-center text-gray" v-if="loading">
				加载中……
			</view>
			<view class="error" v-if="error">
				{{error}}
			</view>
		</div>
	</u-popup>
</template>

<script>
	export default {
		data() {
			return {
				content: [],
				loading: true,
				error: null,
				show: false
			}
		},
		props: {
			order: {}
		},
		methods: {
			open() {
				this.loading = true
				this.error = null;
				this.content = [];
				this.$http.post('shop/api/order/getExpressInfo', {order_sn: this.order.order_sn}).then(data => {
					this.loading = false
					this.content = data;
				}).catch(res => {
					this.error = res.message
					this.loading = false
				})
				this.show = true;
			},
			close() {
				this.$set(this, 'content', [])
				this.error = null
				this.show = false;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: #f5f5f5;
		border-radius: 10rpx;
		padding: 50rpx 40rpx;
	}
	.list {
		display: flex;
		flex-direction: column;
		.item {
			display: flex;
			flex-direction: column;
			border-left: 1px solid #dcdcdc;
			padding-bottom: 10px;
			padding-left: 15px;
			position: relative;
			color: #888;
			align-items: flex-start!important;
			.ic {
				display: block;
				position: absolute;
				left: -16upx;
				top: 6upx;
				width: 16px;
				height: 16px;
				background-color: #dcdcdc;
				border-radius: 38upx;
				.i {
					display: block;
					width: 12px;
					height: 12px;
					border-radius: 6px;
					position: absolute;
					left: 2px;
					top: 2px;
				}
			}
			&:first-child {
				color: #00bb42;
				.ic {
					background-color: #b0ffd4;
				}
				.i {
					background-color: #00aa2c;
				}
			}
			text:first-child {
				font-size: $font-base + 4;
				margin-bottom: 10upx;
			}
		}
		
	}
	.e_info {
			display: flex;
			margin-bottom: 16upx;
			color: #333;
			.e_code {
				margin-left: 10upx;
			}
		}
	.loading {
		display: flex;
		justify-content: center;
		padding: 20upx 0;
		color: #888;
	}
	.error {
		display: flex;
		justify-content: center;
		padding: 20rpx 20rpx;
		color: #ff1a1a;
	}
</style>
