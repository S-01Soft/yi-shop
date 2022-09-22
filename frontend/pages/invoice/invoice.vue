<template>
	<view class="container">
		<view v-if="list.length == 0" style="padding-top: 20%;">
			<u-empty></u-empty>
		</view>
		<yi-card v-for="(item,i) in list" :key="i" title-background="#ddd" title-color="#222">
			<template slot="title">
				{{item.name}}
			</template>
			<template slot="body">
				<view class="item">
					订单号：{{item.order_sn}}
				</view> 
				<view class="item">
					送达地址： {{item.address}} {{item.contactor_name}} {{item.contactor_phone}}
				</view>
				<view class="item">
					提交时间：{{uni.$u.timeFormat(item.created_at, 'yyyy-mm-dd hh:MM')}}
				</view>
				<view v-if="item.status">
					<view class="item">
						发票号码：{{item.invoice_no}}
					</view class="item">
					<view class="item">开票时间：{{uni.$u.timeFormat(item.invoice_time, 'yyyy-mm-dd hh:MM')}}</view>
				</view>
				<view class="u-flex-row" style="margin-top: 10rpx;">
					<u-tag type="warning" text="待开票" v-if="item.status == 0"></u-tag>
					<u-tag type="success" text="已开票" v-if="item.status == 1"></u-tag>
					<view class="btn btn-danger" @click="handleShowExpressTrace(item)" v-if="item.status == 1">查看物流</view>
				</view>
			</template>
		</yi-card>
		<yi-express ref="$express" :no="row.express_no" :code="row.express_code"></yi-express>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				CHECK_AUTH: true,
				list: [],
				page: 1,
				has_more: false,
				row: {},
				show_express: false
			}
		},
		mounted() {
			this.init();
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
		},
		methods: {
			async init() {
				let data = await this.$http.post('shop/api/invoice/list');
				if (data.last_page >= data.current_page) {
					this.page ++;
					this.has_more = true;
				} else {
					this.has_more = false;
				}
				this.list = this.list.concat(data.data);
			},
			handleShowExpressTrace(item) {
				this.row = item;
				this.$nextTick(() => {
					this.$refs.$express.open();
				})
			}
		},
		onReachBottom() {
			this.init();
		}		
	}
</script>

<style scoped lang="scss">
	.container {
		padding: 20rpx;
	}
	
	
	.btn {
		padding: 6rpx 20rpx;
		border-radius: 40rpx;
		font-size: 12px;
		margin: 0 10rpx;
		justify-content: center;
	}
	
	.btn-primary {
		border: 1px solid #bbb;
		color: #555;
	}
	.btn-danger {
		border: 1px solid #f56c6c;
		background-color: #fff9f9;
		color: #fa436a;
	}
	.item {
		padding: 10rpx;
	}
</style>
