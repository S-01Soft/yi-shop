<template>
	<yi-modal title="开具发票" v-model="show" @ok="submit" @open="open" @cancel="close" content-padding="10px">
		<view class="card-container">
			<view class="card-title">开票信息</view>
			<view v-if="list.length">
				<view class="item">
					<view class="title">公司名称</view>
					<view>{{info.name}}</view>
				</view>
				<view class="item">
					<view class="title">地址</view>
					<view>{{info.address}}</view>
				</view>
				<view class="item">
					<view class="title">纳税人识别号</view>
					<view>{{info.no}}</view>
				</view>
				<view class="item">
					<view class="title">开户银行</view>
					<view>{{info.bank}}</view>
				</view>
				<view class="item">
					<view class="title">银行账号</view>
					<view>{{info.account}}</view>
				</view>
			</view>
			
			<view class="u-flex-row u-flex-js-center">
				<view v-if="!list.length" class="btn btn-danger" @click="$u.route('pages/invoice/info')">添加</view>
				<view v-else class="btn btn-danger" @click="changeInfo">更换</view>
			</view>
		</view>
		<view class="card-container">
			<view class="card-title">送达地址</view>
			<view class="item">
				<view>
					<view>{{address.address}} {{address.street}} </view>
					<view style="color: #666;">{{address.contactor_name}} {{address.phone}}</view>
				</view>
			</view>
			<view class="u-flex-row u-flex-js-center">
				<view v-if="!addressList.length" class="btn btn-danger" @click="$u.route('pages/address/edit?type=add')">添加地址</view>
				<view v-else class="btn btn-danger" @click="handleChangeAddress">更换</view>
			</view>
		</view>
		<invoice-info v-model="invoice_show" :columns="list" @ok="handleChooseInfo"></invoice-info>
		<address-box v-model="address_show" :list="addressList" @ok="handleChooseAddress"></address-box>
	</yi-modal>
</template>

<script>
	import { mapState, mapActions } from 'vuex';
	import invoiceInfo from './invoice-info'
	import addressBox from './address-box'
	export default {
		components: {
			invoiceInfo, addressBox
		},
		data() {
			return {
				show: false,
				invoice_show: false,
				address_show: false,
				info: {},
				address: {}
			}
		},
		props: {
			order: {
				default: {}
			}
		},
		computed: {
			...mapState({
				list: state => state.invoice.list,
				addressList: state => state.user.address
			}),
		},
		async mounted() {
			await this.getInvoiceInfo();
			this.info = this.list.length ? this.list[0] : {};
			await this.getUserAddress();
			this.address = this.addressList.length ? this.addressList[0] : {};
		},
		methods: {
			...mapActions(['getInvoiceInfo', 'getUserAddress']),
			changeInfo() {
				this.invoice_show = true
			},
			handleChooseInfo(item) {
				this.info = item;
			},
			handleChangeAddress() {
				this.address_show = true;
			},
			handleChooseAddress(item) {
				this.address = item
			},
			open() {
				this.show = true;
			},
			submit() {
				this.$http.post('shop/api/invoice/submit', {
					address_id: this.address.id, info_id: this.info.id, order_sn: this.order.order_sn
				}, { custom: { loading: true} } ).then(data => {
					this.show = false;
					this.$emit('ok');
				})
			},
			close() {
				this.show = false;
			}
		}
	}
</script>

<style scoped lang="scss">
	.item {
		padding: 10rpx;
	}
	.title {
		font-weight: 700;
	}
	.card-container {
		border: 1px solid #ddd;
		border-radius: 20rpx;
		padding: 20rpx;
		margin: 10rpx;
		.card-title {
			font-size: 36rpx;
			text-align: center;
		}
	}
	
	.btn {
		padding: 6rpx 20rpx;
		border-radius: 40rpx;
		font-size: 12px;
		margin: 0 10rpx;
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
</style>