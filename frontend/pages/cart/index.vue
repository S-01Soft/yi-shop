<template>
	<view class="container">
		<!-- 空白页 -->
		<view v-if="cartList.length == 0" class="empty">
			<u-empty text="空空如也" mode="car"></u-empty>
		</view>
		<view v-else>
			<!-- 列表 -->
			<view class="cart-list">
				<block v-for="(item, index) in cartList" :key="item.id">
					<view class="cart-item" :class="{'b-b': index!==cartList.length-1}">
						<view class="image-wrapper">
							<image style="width: 100%;" :src="item.product && item.product.image[0] && $tools.fixurl(item.product.image[0])" class="loaded" mode="widthFix" lazy-load @click="goProductDetail(item)"></image>
							<view v-if="!item.product.on_sale" class="off-line">
								已下架
							</view>
							<view class="iconfont icon2xuanzhong checkbox" :class="{checked: item.is_selected}" @click="check('item', index)"></view>
						</view>
						<view class="item-right">
							<text class="clamp title" @click="goProductDetail(item)">{{item.product && item.product.title || '[商品不存在]'}}</text>
							<text class="attr">{{item.product && item.sku.value}}</text>
							<view class="price-box">
								<text class="price">¥{{item.product && item.sku.price || 0}}</text>
							</view>
								<u-number-box :value="item.quantity" @change="numberChange(item, index, $event)"></u-number-box>
						</view>
						<text class="del-btn iconfont iconshanchu2" @click="deleteCartItem(index)"></text>
					</view>
				</block>
			</view>
			<!-- 底部菜单栏 -->
			<view class="action-section">
				<view class="checkbox">
					<view class="iconfont icon2xuanzhong checkbox" :class="{checked: allChecked}" style="font-size: 26px;z-index: 99;" @click="check('all')"></view>
					<view class="clear-btn" :class="{show: allChecked}" @click="clearCart">
						清空
					</view>
				</view>
				<view class="total-box">
					<text class="price">¥{{total}}</text>
				</view>
				<button type="primary" class="no-border confirm-btn" @click="createOrder">去结算</button>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapActions,
		mapMutations
	} from 'vuex';
	export default {
		data() {
			return {
				// allChecked: true,
				// empty: false, //空白页现实  true|false
			};
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.getCartProducts()
		},
		watch: {
			
		},
		computed: {
			...mapState({
				user: state => state.user.userinfo,
				cartList: state => state.cart.products
			}),
			hasLogin() {
				return this.user.id ? true : false
			},
			total() {
				let total = 0
				this.cartList.forEach((item, index) => {
					if (item.is_selected) total += parseFloat(item.quantity) * parseFloat(item.sku.price)
				})
				return total.toFixed(2)
			},
			allChecked() {
				let res = true
				this.cartList.forEach((item, index) => {
					if (!item.is_selected) res = false
				})
				return res
			}
		},
		methods: {
			...mapActions(['getCartProducts', 'cartUpdateProduct', 'cartUpdateStatus', 'clearCartProducts']),
			...mapMutations(['CART_PRODUCT_CHECK_TOGGLE', 'CART_PRODUCT_CHECK_ALL', 'SAVE_CART_PRODUCT_QUANTITY']),
			navToLogin() {
				uni.navigateTo({
					url: '/pages/public/login'
				})
			},
			//选中状态处理
			check(type, index) {
				if (type === 'item') {
					this.CART_PRODUCT_CHECK_TOGGLE(index)
				} else {
					this.CART_PRODUCT_CHECK_ALL(this.allChecked)
				}
			},
			//数量
			numberChange(data, index, val) {
				let item = this.cartList[index]
				let form = {
					sku_id: item.sku.id,
					quantity: val.value
				}
				this.cartUpdateProduct(form).then(res => {
					res.index = index;
					this.SAVE_CART_PRODUCT_QUANTITY(res);
				}).catch(e => {})
			},
			//删除
			deleteCartItem(index) {
				uni.showLoading()
				this.$http.post('shop/api/cart/delete', {ids: [this.cartList[index].id]}).then(res => {
					uni.hideLoading()
					this.getCartProducts()
				})
			},
			goProductDetail(item) {
				if (!item.product) return
				uni.navigateTo({
					url: '/pages/product/product?id=' + item.product.id
				})
			},
			//清空
			clearCart() {
				uni.showModal({
					content: '清空购物车？',
					success: (e) => {
						if (e.confirm) {
							uni.showLoading()
							this.clearCartProducts().then(res => {
								uni.hideLoading()
							}).catch(e => uni.hideLoading())
						}
					}
				})
			},
			//创建订单
			createOrder() {
				let data = [];
				this.cartList.forEach(item => {
					if (!item.product) {
						uni.showToast({
							title: '商品不存在',
							icon: 'none'
						})
						return
					}
					if (item.is_selected) {
						data.push(item.id)
					}
				})
				let form = {
					ids: data
				}
				this.cartUpdateStatus(form).then(res => {
					uni.navigateTo({
						url: '/pages/order/create'
					})
				})

			}
		},
		onPullDownRefresh() {
			this.getCartProducts().then(res => {
					uni.stopPullDownRefresh()
			}).catch(e => uni.stopPullDownRefresh())
		}
	}
</script>

<style lang='scss'>
	page {
		background: #fff;
	}
	.container {
		padding-bottom: 134upx;

		/* 空白页 */
		.empty {
			position: fixed;
			left: 0;
			top: 0;
			width: 100%;
			height: 100vh;
			padding-bottom: 100upx;
			display: flex;
			justify-content: center;
			flex-direction: column;
			align-items: center;
			background: #fff;

			image {
				width: 240upx;
				height: 160upx;
				margin-bottom: 30upx;
			}

			.empty-tips {
				display: flex;
				font-size: $font-sm+2upx;
				color: $font-color-disabled;

				.navigator {
					color: $uni-color-primary;
					margin-left: 16upx;
				}
			}
		}
	}

	/* 购物车列表项 */
	.cart-item {
		display: flex;
		flex-direction: row;
		position: relative;
		padding: 30upx 40upx;

		.image-wrapper {
			width: 230upx;
			height: 230upx;
			flex-shrink: 0;
			position: relative;

			image {
				border-radius: 8upx;
			}
			
			.off-line {
				height: 100%;
				width: 100%;
				position: absolute;
				display: flex;
				justify-content: center;
				text-align: center;
				background: #dddddd50;
				color: #555;
				font-weight: 700;
			}
		}

		.checkbox {
			position: absolute;
			left: -16upx;
			top: -16upx;
			z-index: 8;
			font-size: 44upx;
			line-height: 1;
			padding: 4upx;
			color: $font-color-disabled;
			background: #fff;
			border-radius: 50px;
		}

		.item-right {
			display: flex;
			flex-direction: column;
			flex: 1;
			overflow: hidden;
			position: relative;
			padding-left: 30upx;

			.title,
			.price {
				font-size: $font-base + 2upx;
				color: $font-color-dark;
				height: 40upx;
				line-height: 40upx;
			}

			.attr {
				font-size: $font-sm + 2upx;
				color: $font-color-light;
				height: 50upx;
				line-height: 50upx;
			}

			.price-box {
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				align-items: center;

				.out-stock {
					color: red;
					font-size: $font-base;
				}
			}

			.price {
				height: 50upx;
				line-height: 50upx;
			}

		}

		.del-btn {
			padding: 4upx 10upx;
			font-size: 34upx;
			height: 50upx;
			color: $font-color-light;
		}
	}

	/* 底部栏 */
	.action-section {
		/* #ifdef H5 */
		margin-bottom: 100upx;
		/* #endif */
		position: fixed;
		left: 0;
		right: 0;
		bottom: 30upx;
		z-index: 95;
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 100upx;
		padding: 0 30upx;
		background: #ffffff;

		.checkbox {
			height: 52upx;
			position: relative;

			image {
				width: 52upx;
				height: 100%;
				position: relative;
				z-index: 5;
			}
		}

		.clear-btn {
			position: absolute;
			left: 26upx;
			top: 2px;
			z-index: 4;
			width: 0;
			height: 48upx;
			line-height: 48upx;
			padding-left: 38upx;
			font-size: $font-base;
			color: #fff;
			background: $font-color-disabled;
			border-radius: 0 50px 50px 0;
			opacity: 0;
			transition: .2s;

			&.show {
				opacity: 1;
				width: 120upx;
			}
		}

		.total-box {
			flex: 1;
			display: flex;
			flex-direction: column;
			text-align: right;
			padding-right: 40upx;

			.price {
				font-size: $font-lg;
				color: $font-color-dark;
			}

			.coupon {
				font-size: $font-sm;
				color: $font-color-light;

				text {
					color: $font-color-dark;
				}
			}
		}

		.confirm-btn {
			padding: 0 38upx;
			margin: 0;
			border-radius: 100px;
			height: 76upx;
			line-height: 76upx;
			font-size: $font-base + 2upx;
			background: $uni-color-primary;
		}
	}

	/* 复选框选中状态 */
	.action-section .checkbox.checked,
	.cart-item .checkbox.checked {
		color: $uni-color-primary;
	}
</style>
