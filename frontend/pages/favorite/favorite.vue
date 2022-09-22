<template>
	<view class="section">
		<view class="list type-0 u-block">
			<view class="item" style="float: left;" v-for="(item, index) in list" :key="index" @tap="goDetail(item)">
				<view class="box">
					<view class="img">
						<image :src="item.product.image && $tools.fixurl(item.product.image[0])" mode="aspectFit">
						</image>
					</view>
					<view class="text">
						<view class="clamp title">{{item.product.title}}</view>
						<view class="price">￥{{item.product.price}}</view>
					</view>
				</view>
			</view>
		</view>
		<u-loadmore v-if="list.length" :status="status" />
		<view v-else style="padding-top: 100rpx;">
			<u-empty></u-empty>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				page: 0,
				list: [],
				status: 'loadmore'
			}
		},
		onLoad() {
			this.getList();
		},
		methods: {
			async getList() {
				let page = this.page + 1;
				this.status = 'loading'
				const data = await this.$http.post('shop/api/user/favoriteList?page=' + page);
				if (data.current_page >= data.last_page) {
					this.status = 'nomore';
				} else this.status = 'loadmore';
				this.page = data.current_page;
				this.list = this.list.concat(data.data);
			},
			goDetail(item) {
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.product.id
				})
			}
		},
		onReachBottom() {
			if (this.status == 'nomore') return;
			this.getList();
		}
	}
</script>

<style scoped lang="scss">
	.section {
		display: flex;
		flex-direction: column;

		.cate-title-box {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
		}

		.title {

			font-size: $font-sm+2upx;
			color: $font-color-dark;
		}

		.type-0.list {
			.item {
				width: 50vw;

				.box {
					display: flex;
					flex-direction: column;
					align-items: center;
					padding: 10rpx;
					
					.img {
						height: 300rpx;
						width: 300rpx;
						
						image {
							border-radius: 10rpx;
							width: 100%;
							height: 100%;
						}
					}

					.text {
						display: flex;
						flex-direction: column;
						justify-content: flex-start;
						align-items: flex-start;
						width: 100%;

						.title {
							margin: 10upx;
						}

						.price {
							font-size: 26upx;
							color: #fa436a;
						}
					}
				}

			}
		}

	}
</style>
