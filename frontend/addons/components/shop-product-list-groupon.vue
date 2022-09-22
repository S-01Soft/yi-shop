<template>
	<view v-if="products && products.length" class="section" :style="{margin: option.margin}">
		<view style="background:#f5f5f5;padding-bottom:6upx;" v-if="option.title.length">
			<view @tap="goCategory" class="cate-title-box" style="background:#ffffff;padding: 20upx;border-left:6upx solid #fc7592;font-size:28upx;">
				<view class="title">
					{{option.title}}
				</view>
				<view class="iconfont iconyou1">
				</view>
			</view>
		</view>
		<view :style="{padding: option.padding, background: option.background}">
			<view class="list type-0" v-if="option.layouttype == 0">
				<view class="item" v-for="(item, index) in products" :key="index" @tap="goDetail(item)">
					<view class="box" :style="{margin: option.itemMargin, padding: option.itemPadding, border: option.itemBorder,borderRadius: option.itemBorderRadius}">
						<view class="img" :style="{width: option.imgWidth, height: option.imgHeight}">
							<image :style="{borderRadius: option.itemBorderRadius}" :src="item.image && $tools.fixurl(item.image[0])" mode="aspectFit"></image>
						</view>
						<view class="text">
							<view class="clamp title">{{item.title}}</view>
							<view class="price" :style="{width: option.imgWidth}">
								<text class="market_price">￥{{item.skus[0].price}}</text>
								<text class="group_price">团￥{{item.groupon.group_price[item.skus[0].id]}}</text>
							</view>
						</view>
					</view>
				</view>
			</view>

			<view class="list type-1" v-if="option.layouttype == 1">
				<view class="item" v-for="(item, index) in products" :key="index" @tap="goDetail(item)">
					<view class="box" :style="{margin: option.itemMargin, padding: option.itemPadding, border: option.itemBorder,borderRadius: option.itemBorderRadius}">
						<view class="img" :style="{width: option.imgWidth, height: option.imgHeight}">
							<image :style="{borderRadius: option.itemBorderRadius,width: option.imgWidth, height: option.imgHeight, borderRadius: option.itemBorderRadius}"
							 :src="item.image && $tools.fixurl(item.image[0])" mode="aspectFit"></image>
						</view>
						<view class="text">
							<view class="clamp title">{{item.title}}</view>
							<view class="price">
								<text class="market_price">￥{{item.skus[0].price}}</text>
								<text class="group_price">团￥{{item.groupon.group_price[item.skus[0].id]}}</text>
							</view>
						</view>
					</view>
				</view>
			</view>

			<view class="list type-2" v-if="option.layouttype == 2">

				<scroll-view class="floor-list" scroll-x>
					<view class="scoll-wrapper">
						<view v-for="(item, i) in products" :key="i" class="floor-item" :style="{margin: option.itemMargin, padding: option.itemPadding, border: option.itemBorder, borderRadius: option.itemBorderRadius}"
						 @click="goDetail(item)">
							<image :style="{width: option.imgWidth, height: option.imgHeight, borderRadius: option.itemBorderRadius}" :src="$tools.fixurl(item.image[0])"
							 mode="aspectFill"></image>
							<text :style="{width: option.imgWidth}" class="title clamp">{{item.title}}</text>

							<view class="price" :style="{width: option.imgWidth}">
								<text class="group_price">团￥{{item.groupon.group_price[item.skus[0].id]}}</text>
							</view>
						</view>
						<view @click="goCategory" :style="{margin: option.itemMargin, padding: option.itemPadding, border: option.itemBorder, borderRadius: option.itemBorderRadius}">
							<view class="more">
								<text>查看更多</text>
								<text>More+</text>
							</view>
						</view>
					</view>
				</scroll-view>
			</view>

		</view>
	</view>
</template>

<script>
	import Refresh from '@/addons/mixins/refresh';
	export default {
		name: 'shop-product-list-groupon',
		mixins: [Refresh],
		data() {
			return {
				products: []
			}
		},
		props: {
			option: {}
		},
		mounted() {
			if (this.$tools.has_addon('shopgroupon')) this.init();
		},
		methods: {
			init() {
				return new Promise((resolve, reject) => {
					this.$http.get('shopgroupon/api/index/list', {params: {
						per_page: this.option.count
					}}).then(res => {
						this.products = res.data.data;
						resolve(res);
					}).catch(e => {
						reject(e);
					});
				})
			},
			goDetail(item) {
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.id
				})
			},
			goCategory() {
				uni.navigateTo({
					url: '/pages/product/list?groupon=1'
				})
			},
			refreshHandle() {
				return this.init();
			}
		}
	}
</script>

<style lang="scss" scoped>
	.section {
		display: flex;
		flex-direction: column;

		.cate-title-box {
			display: flex;
			flex-direction: row;
			justify-content: space-between;
		}
		
		
		.price {
		
			.market_price {
				font-size: 30upx;
				color: #222;
			}
		
			.group_price {
				font-size: 24upx;
				color: $uni-color-primary;
				border: 2upx solid $uni-color-primary;
				border-radius: 8upx;
				padding: 4upx;
				margin-left: 10upx;
			}
		}

		.title {

			font-size: $font-sm+2upx;
			color: $font-color-dark;
		}

		.type-0.list {
			display: flex;
			flex-wrap: wrap;

			.item {
				
				width: 50vw;

				.box {
					display: flex;
					flex-direction: column;
					align-items: center;

					.img {
						padding: 1px;

						image {
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

						view {
							width: 100%;
						}
					}
				}

			}
		}


		.type-1 {

			.box {
				display: flex;


				.text {
					padding: 20upx;
					display: flex;
					flex-direction: column;
					justify-content: center;

					.title {
						font-size: $font-base;
						margin-bottom: 10upx;
					}
					
					.text {
						padding: 20upx;
						display: flex;
						flex-direction: column;
						justify-content: center;
					}

					.price {
						font-size: $font-sm+2upx;
						color: $uni-color-primary;
					}
				}

			}

		}

		/* 分类推荐楼层 */
		.type-2 {
			width: 100%;
			overflow: hidden;

			.cat_name {
				background: #fff;
				margin-left: 5px;
				padding: 20upx 0 20upx 20upx;
				border-left: 3px solid #FC7592;
				font-size: $font-base + 4upx;
			}

			.floor-img-box {
				width: 100%;
				height: 320upx;
				position: relative;

				&:after {
					content: '';
					position: absolute;
					left: 0;
					top: 0;
					width: 100%;
					height: 100%;
					background: linear-gradient(rgba(255, 255, 255, .06) 30%, #f8f8f8);
				}
			}

			.floor-img {
				width: 100%;
				height: 100%;
			}

			.floor-list {
				white-space: nowrap;
				padding: 20upx;
				padding-right: 50upx;
				border-radius: 6upx;
				margin-left: 10upx;
				background: #fff;
				box-shadow: 1px 1px 5px rgba(0, 0, 0, .2);
				position: relative;
				z-index: 1;
			}

			.scoll-wrapper {
				display: flex;
				align-items: flex-start;
			}

			.floor-item {
				margin-right: 20upx;

				image {
					width: 180upx;
					height: 180upx;
					border-radius: 6upx;
				}

				.price {
					color: $uni-color-primary;
					font-size: $font-sm+2upx;
					margin-top: 10upx;
					.group_price {
						margin-left: 0;
					}
				}
			}

			.more {
				display: flex;
				align-items: center;
				justify-content: center;
				flex-direction: column;
				flex-shrink: 0;
				width: 180upx;
				height: 180upx;
				border-radius: 6upx;
				background: #f3f3f3;
				font-size: $font-base;
				color: $font-color-light;

				text:first-child {
					margin-bottom: 4upx;
				}
			}
		}

	}
</style>
