<template>
	<view>
		<u-popup mode="center" :show="show" @close="close" @open="open" :round="10">
			<view style="width: 85vw;">
				<view class="title">转账付款</view>
				<view class="content">
					<view class="c-list">
						<view class="c-row">
							<view class="c-title">公司名称：</view>
							<view>{{info.company_name}}</view>
						</view>
						<view class="c-row">
							<view class="c-title">统一社会信用代码：</view>
							<view>{{info.company_no}}</view>
						</view>
						<view class="c-row">
							<view class="c-title">开户行：</view>
							<view>{{info.bank}}</view>
						</view>
						<view class="c-row">
							<view class="c-title">银行账户：</view>
							<view>{{info.account}}</view>
						</view>
						<view class="c-row">
							<view class="c-title">订单号：</view>
							<view>{{orderSn}}</view>
						</view>
					</view>
					<view class="tips u-block">
						<text>请向已上账户转账付款</text>
						<text style="color: red;font-size: 18px;">￥{{price}}</text>
						<text>，转账成功后我们会尽快安排出货。</text>
						
					</view>
				</view>
				<view class="footer">
					<view class="danger" @click="handleCancel">取消</view>
					<view class="primary" @click="handleOk">转账成功</view>
				</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				show: false,
				info: {
					company_name: '', company_no: '', bank: '', account: ''
				}
			}
		},
		props: {
			value: {
				default: true
			},
			price: {
				default: 0
			},
			orderSn: {
				default: ''
			}
		},
		watch: {
			value(v) {
				this.show = v;
			},
			show(v) {
				this.$emit('input', v);
			}
		},
		mounted() {
			this.init();
		},
		methods: {
			async init() {
				this.info = await this.$http.post('shop/api/index/getAccount');
				
			},
			open() {},
			close() {},
			async handleOk() {
				await this.$http.post('shop/api/order/transfer', {out_order_sn: this.orderSn})
				this.show = false;
				this.$emit('ok');
			},
			handleCancel() {
				this.show = false;
				this.$emit('cancel')
			}
		}
	}
</script>
<style scoped lang="scss">

	.red-dot {
		margin-left: 10rpx;
		background: red;
		border-radius: 50%;
		font-size: 34rpx;
		width: 20rpx;
		height: 20rpx;
		display: inline-block;
	}
	.title {
		    text-align: center;
		    padding: 30rpx 0;
		    font-size: 36rpx;
		    background: #d98ee9;
		    color: #fff;
		    border-top-left-radius: 20rpx;
		    border-top-right-radius: 20rpx;
	}
	.content {
		padding: 40rpx;
		
		.c-list {
			.c-row {
				padding: 5px 0;
				view {
					color: #888;
				}
				.c-title {
					font-weight: 700;
					margin: 5px 0;
					color: #222;
				}
			}
		}
	}
	.tips {
		padding: 5px 0;
		text-indent: 10px;
		color: #888;
	}
	.footer {
		display: flex;
		flex-direction: row;
		padding: 20rpx 0;
			
		.primary {
			flex: 1;
			text-align: center;
			color: red;
		}
		
		.danger {
			flex: 1;
			text-align: center;
			color: #888;
		}
	}
</style>
