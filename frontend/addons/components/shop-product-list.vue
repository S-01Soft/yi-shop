<template>
	<view class="section" :style="{margin: option.margin}">
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
			<view class="list type-0 u-block" v-if="option.layouttype == 0">
				<view class="item" style="float: left;" v-for="(item, index) in products" :key="index" @tap="goDetail(item)">
					<view class="box" :style="{margin: option.itemMargin, padding: option.itemPadding, border: option.itemBorder,borderRadius: option.itemBorderRadius}">
						<view class="img" :style="{width: option.imgWidth, height: option.imgHeight}">
							<image :style="{borderRadius: option.itemBorderRadius}" :src="item.image && $tools.fixurl(item.image[0])" mode="aspectFit"></image>
						</view>
						<view class="text">
							<view class="clamp clamp-2 title">{{item.title}}</view>
							<view class="price">￥{{item.skus[0].price}}</view>
						</view>
					</view>
				</view>
			</view>

			<view class="list type-1" v-if="option.layouttype == 1">
				<view class="item" v-for="(item, index) in products" :key="index" @tap="goDetail(item)">
					<view class="box u-flex-row" :style="{margin: option.itemMargin, padding: option.itemPadding, border: option.itemBorder,borderRadius: option.itemBorderRadius}">
						<view class="img" :style="{width: option.imgWidth, height: option.imgHeight}">
							<image :style="{borderRadius: option.itemBorderRadius,width: option.imgWidth, height: option.imgHeight, borderRadius: option.itemBorderRadius}" :src="item.image && $tools.fixurl(item.image[0])"
							 mode="aspectFit"></image>
						</view>
						<view class="text">
							<view class="clamp title">{{item.title}}</view>
							<view class="price">￥{{item.skus[0].price}}</view>
						</view>
					</view>
				</view>
			</view>

			<view class="list type-2" v-if="option.layouttype == 2">
				<scroll-view class="floor-list" scroll-x="true" style="white-space: nowrap;" @touchmove.stop>
					<view class="scoll-wrapper u-flex-row">
						<view v-for="(item, i) in products" :key="i" class="floor-item" :style="{margin: option.itemMargin, padding: option.itemPadding, border: option.itemBorder, borderRadius: option.itemBorderRadius}"
						 @click="goDetail(item)">
							<image :style="{width: option.imgWidth, height: option.imgHeight, borderRadius: option.itemBorderRadius}" :src="$tools.fixurl(item.image[0])" mode="aspectFill"></image>
							<text :style="{width: option.imgWidth}" class="title clamp">{{item.title}}</text>
							<text :style="{width: option.imgWidth}" class="price">￥{{item.skus[0].price}}</text>
						</view>
					</view>
				</scroll-view>
			</view>

		</view>
	</view>
</template>

<script>
	import Refresh from '@/mixins/refresh';
	export default {
		name: 'shop-product-list',
		mixins: [Refresh],
		data() {
			return {
				loading: false,
				products: []
			}
		},
		props: {
			option: {}
		},
		mounted() {
			this.init();
		},
		methods: {
			init() {
				let form = {
					per_page: this.option.count
				};
				switch (parseInt(this.option.type)) {
					case 0:
						{
							form.cat_id = this.option.target || 0;
							break;
						}
					case 1:
						{
							form.tag_id = this.option.target;
							break;
						}
				}
				return new Promise(async (resolve, reject) => {
					const query = `
						query ($cat_id: Int, $tag_id: Int) {
						  products(category_id: $cat_id, tag_id: $tag_id) {
						    data {
						      id
						      title
						      description
							  image
						      skus {
								  price
							  }
						    }
						  }
						}
					`;
					this.loading = true;
					const result = await this.$gql.fetch(query, form);
					this.loading = false;
					const err = result.getError('products');
					if (err) return result.show(err)
					const { products } = result.get();
					this.products = products.data
					// this.$http.get('shop/api/product/getList', {params: form}).then(data => {
					// 	this.products = data.data;
					// 	resolve(data);
					// }).catch(e => {
					// 	reject(e);
					// });
				});
			},
			goDetail(item) {
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.id
				})
			},
			goCategory() {
				let key = this.option.type == 0 ? 'cat_id' : 'tag_id';
				let target = this.option.target;
				uni.navigateTo({
					url: `/pages/product/list?${key}=${target}`
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

		.title {

			font-size: $font-sm+2upx;
			color: $font-color-dark;
		}

		.type-0.list {
			display: flex;
			flex-direction: row;
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

					.title {
						font-size: $font-base;
						margin-bottom: 10upx;
					}

					.price {
						font-size: $font-sm+2upx;
						color: $uni-color-primary;
					}
				}

			}

		}


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
