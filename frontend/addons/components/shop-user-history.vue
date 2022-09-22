<template>
	<view class="history-section">

		<view class="sec-header" v-if="viewList.length">
			<text :class="option.icon" :style="{color: option.color}"></text>
			<text>{{option.title}}</text>
		</view>
		<scroll-view scroll-x class="h-list" v-if="viewList.length">
			<image v-for="(item, index) in viewList" v-if="item.product" :key="index" @click="navTo('/pages/product/product?id=' + item.product.id)"
			 :src="$tools.fixurl(item.product.image[0])"></image>
		</scroll-view>
	</view>
</template>

<script>
	import Refresh from '@/common/libs/mixins/refresh';
	export default {
		mixins: [Refresh],
		data() {
			return {
				viewList: []
			}
		},
		props: {
			option: {}
		},
		mounted() {
			this.init();
		},
		methods: {
			init(){
				return new Promise ((resolve, reject) => {
					Promise.all([this.getViewList()]).then(res => {
						resolve(res)
					}).catch(e => { reject(e) })
				})
			},
			navTo(url) {
				uni.navigateTo({
					url
				})
			},
			getViewList() {
				this.$http.post('shop/api/user/viewList').then(data => {
					this.viewList = data.data
				})
			},
			refreshHandle() {
				return this.init();
			}
		}
	}
</script>

<style lang="scss" scoped>
	.history-section {
		margin-top: 20upx;
		background: #fff;
		border-radius: 10upx;
	
		.sec-header {
			margin-top: 20upx;
			padding-top: 20upx;
			display: flex;
			align-items: center;
			font-size: $font-base;
			color: $font-color-dark;
			line-height: 40upx;
			margin-left: 30upx;
	
			.iconfont {
				font-size: 44upx;
				margin-right: 16upx;
				line-height: 40upx;
			}
		}
	
		.h-list {
			white-space: nowrap;
			padding: 30upx 30upx 0;
	
			image {
				display: inline-block;
				width: 160upx;
				height: 160upx;
				margin-right: 20upx;
				border-radius: 10upx;
			}
		}
	}
	
	
</style>
