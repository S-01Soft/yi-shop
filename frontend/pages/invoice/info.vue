<template>
	<view>
		<view class="content b-t">
			<view v-if="list.length == 0" style="padding-top: 20%;">
				<u-empty></u-empty>
			</view>
			<yi-card v-for="(item, index) in list" :key="index" title-background="#ddd" title-color="#222">
				<template slot="title">
				<view class="u-flex-row u-flex-between">
					<text>{{item.name}}</text>
					<u-tag v-if="item.is_default == 1" text="默认" size="mini" type="error"></u-tag>
				</view>
				</template>
				<template slot="body">
					<view class="card-item">纳税人识别号：{{item.no}}</view>
					<view class="card-item">地址：{{item.address}}</view>
					<view class="card-item">电话：{{item.phone}}</view>
					<view class="card-item">开户银行：{{item.bank}}</view>
					<view class="card-item">银行账号：{{item.account}}</view>
					<view class="u-flex-row" style="margin-top: 20rpx;justify-content: end;">
						<text class="btn btn-primary" @click.stop="edit('edit', item.id)">编辑</text>
						<text class="btn btn-danger" @click.stop="del(item.id)">删除</text>
						<!-- <text class="iconfont iconbianji1" @click.stop="edit('edit', item.id)"></text>
						<text class="del-btn iconfont iconshanchu1" @click.stop="del(item.id)"></text> -->
					</view>
				</template>
			</yi-card>
			<!-- <view class="list b-b u-flex-row" v-for="(item, index) in list" :key="index">
				<view class="wrapper">
					<view class="address-box u-flex-row">
						<text v-if="item.is_default == 1" class="tag">默认</text>
						<text class="address">{{item.address}}</text>
					</view>
					<view class="u-box u-flex-row">
						<text class="name">{{item.name}}</text>
						<text class="mobile">{{item.phone}}</text>
					</view>
				</view>
				<text class="iconfont iconbianji1" @click.stop="edit('edit', item.id)"></text>
				<text class="del-btn iconfont iconshanchu1" @click.stop="del(item.id)"></text>
			</view> -->
			
			<view class="u-block mt-20 mr-20 ml-20 footer">
				<u-button @click="edit('add')" type="error">新增</u-button>
			</view>
		</view>
		
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	export default {
		data() {
			return {
				CHECK_AUTH: true,
			}
		},
		computed: {
			...mapState({
				list: state => state.invoice.list
			})
		},
		async onLoad(options){
			options = await this.onLoadStart(options);
			this.getInvoiceInfo()
		},
		methods: {
			...mapActions(['getInvoiceInfo']),
			// async init() {
			// 	this.list = await this.$http.get('shop/api/invoice/info');
			// },
			edit(type, id) {
				this.$u.route(`pages/invoice/edit?type=${type}&id=${id}`)
			},
			del(id) {
				uni.showModal({
					title: '确定删除吗？',
					success: res => {
						if (res.confirm) {
							this.$http.post('shop/api/invoice/deleteInfo', { id }).then(() => {
								this.getInvoiceInfo()
							});
						}
					}
				})
			}
		}
	}
</script>

<style lang='scss'>
	page{
		padding-bottom: 120upx;
	}
	.card-item {
		padding: 10rpx;
		color: #555;
	}
	.content{
		position: relative;
		padding: 20upx 20upx;
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
	
	.footer {
		position: fixed;
		left: 0;
		right: 0;
		bottom: 50rpx;
	}
	/* .list{
		display: flex;
		align-items: center;
		padding: 20upx 30upx;
		background: #fff;
		position: relative;
	}
	.wrapper{
		display: flex;
		flex-direction: column;
		flex: 1;
	}
	.address-box{
		display: flex;
		align-items: center;
		.tag{
			font-size: 24upx;
			color: $base-color;
			margin-right: 10upx;
			background: #fffafb;
			border: 1px solid #ffb4c7;
			border-radius: 4upx;
			padding: 4upx 10upx;
			line-height: 1;
		}
		.address{
			font-size: $font-base;
			color: $font-color-dark;
			flex: 1;
		}
	}
	.u-box{
		font-size: 28upx;
		color: $font-color-light;
		margin-top: 16upx;
		.name{
			margin-right: 30upx;
		}
	}
	.iconshanchu1{
		display: flex;
		align-items: center;
		height: 80upx;
		font-size: 40upx;
		color: $font-color-light;
		padding-left: 30upx;
	}
	
	.del-btn {
		color:#fa436a;
	} */
</style>
