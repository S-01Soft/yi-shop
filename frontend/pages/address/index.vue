<template>
	<view v-if="isLogged">
		<view class="content b-t">
			<view v-if="address && address.length == 0" style="padding-top: 20%;">
				<u-empty></u-empty>
			</view>
			<view class="list b-b u-flex-row" v-for="(item, index) in address" :key="index" @click="checkAddress(item)">
				<view class="wrapper">
					<view class="address-box u-flex-row">
						<text v-if="item.is_default == 1" class="tag">默认</text>
						<text class="address">{{item.address}} {{item.street}}</text>
					</view>
					<view class="u-box u-flex-row">
						<text class="name">{{item.contactor_name}}</text>
						<text class="mobile">{{item.phone}}</text>
					</view>
				</view>
				<text class="iconfont iconbianji1" @click.stop="addAddress('edit', item)"></text>
				<text class="del-btn iconfont iconshanchu1" @click.stop="delAddress(item)"></text>
			</view>
			
			<view class="u-block mt-20 mr-20 ml-20 footer">
				<u-button @click="addAddress('add')" type="error">新增地址</u-button>
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
				source: 0,
				addressList: [],
				option: {}
			}
		},
		async onLoad(options){
			options = await this.onLoadStart(options);
			this.source = options.source;
			this.option = options;
			await this.getUserAddress()
		},
		computed: {
			...mapState({
				address: state => state.user.address,
				isLogged: state => state.user.isLogged
			})
		},
		methods: {
			...mapActions(['getUserAddress']),
			checkAddress(item){
				if(this.source == 1){
					this.option.address_id = item.id
					uni.navigateTo({
						url: '/pages/order/create?' + this.$tools.queryStringify(this.option)
					})
				}
			},
			addAddress(type, item){
				let url = `/pages/address/edit?type=${type}`
				if (item) url += `&id=${item.id}`
				uni.navigateTo({
					url: url
				})
			},
			delAddress(item) {
				uni.showModal({
					title: '提示',
					content: '确定删除地址吗？',
					success: res => {
						if (res.confirm) {
							this.$http.post('area/api/user/delAddress', {address_id: item.id}).then(res => {
								this.getUserAddress()
							})
						}
					}
				})
			},
			refreshList(data, type){
				this.addressList.unshift(data);
			}
		}
	}
</script>

<style lang='scss'>
	page{
		padding-bottom: 120upx;
	}
	.content{
		position: relative;
	}
	.list{
		display: flex;
		align-items: center;
		padding: 20upx 30upx;;
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
	
	.footer {
		position: fixed;
		left: 0;
		right: 0;
		bottom: 50rpx;
	}
	.del-btn {
		color:#fa436a;
	}
</style>
