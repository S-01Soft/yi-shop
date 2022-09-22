<template>
	<view>
		<yi-modal v-model="show" title="转账付款" okText="我已转账" @ok="handleOk" @cancel="handleCancel()">
				<view class="c-list">
					<view class="c-row">
						<view class="c-title">公司名称：</view>
						<view>{{info.company_name}}</view>
					</view>
					<!-- <view class="c-row">
						<view class="c-title">统一社会信用代码：</view>
						<view>{{info.company_no}}</view>
					</view> -->
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
						<view class="u-flex-row u-flex-align-center"> <text>{{orderSn}}</text></view>
					</view>
				</view>
				<view class="tips u-block">
					<text>请向以上账户转账</text>
					<text style="color: red;font-size: 18px;">￥{{price}}</text>
					<text>，留言请附订单号：</text><text style="color: red;font-size: 18px;">{{orderSn}}</text><text>，转账成功后我们会尽快安排发货。</text>
				</view>
				<view>
					<yi-copy :value="infoText">
						<view style="background: #eeeaea; padding: 5px 10px;margin: 5px 0; border-radius: 5px; text-align: center; color: #4f4a4a;">点此复制转账信息</view>
					</yi-copy>
				</view>
		</yi-modal>
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
		computed: {
			infoText() {
				let info = this.info;
				let no = this.orderSn
				return `公司名称：${info.company_name}\r\n开户行：${info.bank}\r\n银行帐号：${info.account}\r\n订单号：${no}\r\n金额：${this.price}元\r\n转账留言请附订单号`;
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
	
	.tips {
		padding: 5px 0;
		text-indent: 10px;
		color: #888;
		word-break: break-all;
	}
</style>