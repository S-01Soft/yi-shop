<template>
	<view class="">		
		<view class="c-row b-b" v-if="product.services && product.services.length">
			<text class="tit">服务</text>
			<view class="bz-list con u-flex-row">
				<view class="item u-block" @click="handleServiceClick(item)" v-for="(item, index) in product.services" :key="index">{{item.title}}<text v-show="index != product.services.length - 1" class="sp">·</text></view>
			</view>
		</view>
		
		<yi-modal title="服务说明" v-model="show" ok-text="知道了" :show-ok="true" @ok="show=false" :show-cancel="false" @open="open" @close="close" :close-on-click-overlay="true">
			<view class="service-content">
				<view class="pt-10 pb-10" v-for="(item,index) in product.services" :key="index">
					<view class="text-bold pt-10 pb-10 service-title"> · {{item.title}}</view>
					<view class="pr-20 pl-20 pt-10 pb-10">
						<u-parse :content="item.description" :domain="appInfo.config.shop && appInfo.config.shop.base.domain"></u-parse>
					</view>
				</view>
			</view>
		</yi-modal>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				show: false,
				service: {}
			}
		},
		computed: {
			...mapState({
				appInfo: state => state.appInfo
			})
		},
		props: {
			product: {
				default: {}
			}
		},
		methods: {
			handleServiceClick(item) {
				this.service = item;
				this.show = true;
			},
			open() {
				this.show = true;
			},
			close() {
				this.show = false;
			}
		}
	}
</script>

<style lang="scss" scoped>
	
	.c-row {
		display: flex;
		flex-direction: row;
		align-items: center;
		padding: 20upx 30upx;
		position: relative;
	}
	
	.tit {
		width: 140upx;
	}
	.sp {
		margin: 0 10rpx;
		font-weight: 700;
	}
	
	.con {
		flex: 1;
		color: $font-color-dark;
	
		.selected-text {
			margin-right: 10upx;
		}
	}
	
	.con-list {
		flex: 1;
		display: flex;
		flex-direction: column;
		color: $font-color-dark;
		line-height: 40upx;
	}
	
	.service-content {
		padding: 20rpx;
		.service-title {
			font-size: 30rpx;
		}
	}
</style>
