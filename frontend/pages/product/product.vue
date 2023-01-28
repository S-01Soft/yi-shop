<template>
	<view class="main-box">
		<view class="carousel">
			<swiper indicator-dots circular=true duration="400">
				<swiper-item class="swiper-item" v-for="(item,index) in product.image" :key="index">
					<view class="image-wrapper">
						<image :src="$tools.fixurl(item)" class="loaded" mode="aspectFill"></image>
					</view>
				</swiper-item>
			</swiper>
		</view>
		<view class="introduce-section">
			<view class="title u-flex-row">
				{{product.title || ''}}
			</view>
			<view class="product-desc">{{product.description || ''}}</view>
			<view class="price-box u-flex-row">
				<text class="price-tip">￥</text>
				<text class="price">{{currentSku && currentSku.price || 0}}</text>
				<!-- <text class="m-price">{{currentSku && currentSku.market_price || 0}}</text> -->
			</view>
			<view class="bot-row u-flex-row">
				<text>销量: {{product.sold_count || 0}} {{product.unit && product.unit.code || ''}}</text>
				<text>库存: {{currentSku && currentSku.stock || 0}} {{product.unit && product.unit.code || ''}}</text>
				<view class="iconfont iconfenxiang" @tap="share" v-if="$tools.has_addon('shopshare') && id && currentSku && currentSku.id"></view>
			</view>
		</view>

		<view class="c-list">
			<view class="c-row b-b" @click="toggleSpec">
				<text class="tit">购买类型</text>
				<view class="con">
					<text class="selected-text">
						{{(currentSku && currentSku.value) || ''}}
					</text>
				</view>
				<text class="iconfont iconyou1"></text>
			</view>
			<view class="c-row b-b">
				<text class="tit">数量</text>
				<view class="con quantity-box">
					<u-number-box v-model="quantity"></u-number-box>
				</view>
			</view>
			<view class="c-row b-b" v-if="product.is_presale && desc">
				<text class="tit">说明</text>
				<view class="con t-r red">{{desc}}</view>
			</view>
			<view class="c-row b-b" @click="couponVisible = 1" v-if="couponList && couponList.length">
				<text class="tit">优惠券</text>
				<text class="con t-r red">领取优惠券</text>
				<text class="iconfont iconyou1"></text>
			</view>
			<view class="c-row b-b" v-if="product.promotion && product.promotion.length">
				<text class="tit">促销活动</text>
				<view class="con-list">
					<text v-for="(item, i) in product.promotion" :key="i">{{item}}</text>
				</view>
			</view>
			<product-service :product="product"></product-service>
		</view>

		<shop-groupon-box v-if="$tools.has_addon('shopgroupon')" :product="product" :group-number="product.groupon && product.groupon.group_number" :value="id" @add-group="addGroup"></shop-groupon-box>
		
		<product-comment :product="product"></product-comment>
		
		<view class="detail-desc">
			<view class="d-header">
				<text>图文详情</text>
			</view>
			<u-parse :content="product.content" :domain="appInfo.config.shop && appInfo.config.shop.base.domain"></u-parse>
		</view>

		<!-- 底部操作菜单 -->
		<view class="page-bottom u-flex-row">
			<navigator url="/pages/index/index" open-type="switchTab" class="p-b-btn">
				<text class="iconfont iconshouye1 "></text>
				<text>首页</text>
			</navigator>
			<navigator url="/pages/cart/index" open-type="switchTab" class="p-b-btn">
				<text class="iconfont icongouwuche2"></text>
				<text>购物车</text>
			</navigator>
			<view class="p-b-btn" :class="{active: product.is_favorite}" @click="toFavorite">
				<text class="iconfont iconshoucang7"></text>
				<text>收藏</text>
			</view>

			<view class="action-btn-group u-flex-row groupon-btns" v-if="product.groupon">
				<view class="btn" @click="buy()">
					<text>￥{{currentSku.price}}</text>
					<text>单独购买</text>
				</view>
				<view class="btn" @click="buy({order_type: 1})">
					<text>￥{{current_group_price}}</text>
					<text>发起拼团</text>
				</view>
			</view>
			<view class="action-btn-group u-flex-row" v-else :class="product.on_sale && currentSku && currentSku.stock > 0 ? '' : 'disabled'">
				<button type="primary" class="action-btn no-border add-cart-btn" @click="addCart">加入购物车</button>
				<button type="primary" class="action-btn no-border buy-now-btn" @click="buy()">立即购买</button>
			</view>

		</view>


		<!-- 规格-模态层弹窗 -->
		<view class="popup spec" :class="specClass" @touchmove.stop.prevent="stopPrevent" @click="toggleSpec">
			<!-- 遮罩层 -->
			<view class="mask"></view>
			<view class="layer attr-content" @click.stop="stopPrevent">
				<view class="a-t u-flex-row">
					<image :src="showImage"></image>
					<view class="right">
						<text class="price">{{currentSku && currentSku.price}}</text>
						<text class="stock">库存：{{currentSku && currentSku.stock}} {{product.unit && product.unit.code}}</text>
						<view class="selected u-block">
							已选：
							<text class="selected-text">
								{{currentSku && currentSku.value}}
							</text>
						</view>
					</view>
				</view>
				<view v-for="(item,index) in product.attr_items" :key="index" class="attr-list">
					<text>{{product.attr_group[index]}}</text>
					<view class="item-list u-flex-row">
						<text v-for="(childItem, childIndex) in item" v-if="childItem.pid === item.id" :key="childIndex" class="tit"
						 :class="{selected: attrChoose[index] === childIndex, disabled: skusStates[index][childIndex] == false}" @click="selectSpec(index, childIndex, item)">
							{{childItem}}
						</text>
					</view>
				</view>
				<view class="attr-quantity">
					<text>数量</text>
					<u-number-box v-model="quantity"></u-number-box>
					<!-- <number-box class="number-box" :disabled="true" :step="1" v-model="quantity" :min="1"></number-box> -->
				</view>
				<button class="btn" @click="finish" v-if="currentSku && currentSku.stock > 0">完成</button>
				<button class="btn disabled" v-else>库存不足</button>
			</view>
		</view>
		
		<shop-product-share-image ref="share" :pid="product && product.id" :sku_id="currentSku && currentSku.id"></shop-product-share-image>

		<shop-coupon-box v-model="couponVisible" @pop="couponPop" :list="couponList"></shop-coupon-box>
		
	</view>
</template>

<script>
	import {
		mapActions,
		mapState
	} from 'vuex'
	import ProductService from './child/service'
	import ProductComment from './child/comment'
	export default {
		components: {
			ProductService, ProductComment
		},
		data() {
			return {
				id: null,
				product: {},
				quantity: 1,
				attrChoose: {},
				specClass: 'none',
				couponList: [],
				couponVisible: 0,
				current_group_price: 0,
				skusValueAsKey: [],
				skusStates: [],
				userSelected: false,
				actionType: '',
				extendData: {}
			};
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			if (options.scene) {
				options = this.$tools.getQuery(null, '?' + decodeURIComponent(options.scene))
			}
			this.id = parseInt(options.id);
			// this.getInfo();
			this.getInfo({
				id: this.id
			}).then(data => {
				if (data.on_sale == 0) uni.showToast({
					title: '商品已下架',
					icon: 'none'
				})
				if (data.skus.length == 1) this.userSelected = true;
				this.product = data;
				this.convertSkusKey(this.product.skus)
				this.specInit(options.sku_id);
				this.wechatShare();
			});
			if (this.$tools.has_addon('shopcoupon')) this.getCoupon()
		},
		computed: {
			...mapState({
				user: state => state.user.userinfo,
				appInfo: state => state.appInfo,
				isLogged: state => state.user.isLogged
			}),
			currentSku() {
				let attrValues = []
				for (let i in this.attrChoose) {
					attrValues.push(this.product.attr_items[i][this.attrChoose[i]])
				}
				const val = attrValues.join(',')
				for (let i in this.product.skus) {
					if (this.product.skus[i].value == val) return this.product.skus[i]
				}
			},
			showImage() {
				if (this.currentSku && this.currentSku.image) return this.$tools.fixurl(this.currentSku.image);
				if (this.product.image && this.product.image[0]) return this.$tools.fixurl(this.product.image[0])
				return '';
			},
			depositPrice() {
				if (this.product.is_presale != 1) return 0;
				let price = this.product.deposit_type == 1 ? this.currentSku.price * this.quantity * this.product.deposit / 100 : this.product.deposit;
				return price.toFixed(2)
			},
			desc(){
				return this.appInfo?.config?.shop?.product?.desc || ''
			}
		},
		watch: {
			currentSku: {
				handler(val) {
					this.getGroupPrice()
				},
				deep: true
			}
		},
		methods: {
			...mapActions(['getProductInfo', 'cartAddProduct', 'getCartProducts']),
			async getInfo(cb) {
				const query = `
				query ($product_id: Int!) {
				  product(id: $product_id) {
				    id
				    title
				    description
				    sold_count
				    is_favorite
				    services {
				      title
				      description
				    }
				    unit {
				      name
				      code
				    }
				    attr_group
				    attr_items
				    on_sale
				    image
				    skus {
				      id
				      keys
				      value
				      price
				      market_price
				      image
				      stock
				    }
				  }
				}
				`
				const result = await this.$gql.fetch(query, {
					product_id: this.id
				});
				result.error('product')
				return Promise.resolve(result.get('product'))
			},
			//规格弹窗开关
			getGroupPrice() {
				if (this.product.groupon) {
					let groupPrice = this.product.groupon.group_price
					this.current_group_price = groupPrice[this.currentSku.id]
				} else this.current_group_price = 0
			},
			toggleSpec() {
				this.userSelected = true;
				if (this.specClass === 'show') {
					this.specClass = 'hide';
					setTimeout(() => {
						this.specClass = 'none';
					}, 250);
				} else if (this.specClass === 'none') {
					this.specClass = 'show';
				}
			},
			specInit(sku_id = 0) {
				for (let index in this.product.attr_group) {
					let i = 0;
					if (sku_id) {
						for (let k in this.product.skus) {
							if (sku_id == this.product.skus[k].id) {
								i = k;
							}
						}
					}
					
					this.$set(this.attrChoose, index, i)
				}
				this.getSkusStates()
			},
			//选择规格
			selectSpec(index, childIndex, item) {
				this.$set(this.attrChoose, index, childIndex)
				this.getSkusStates()
			},
			getSkusStates() {
				let result = []
				let data = this.attrChoose
				this.product.attr_items.forEach((item, index) => {
					let row = []
					item.forEach((subItem, subIndex) => {
						if (subIndex != data[index]) {
							let value = this.getSkuItem(index, subIndex, subItem)
							if (this.skusValueAsKey[value].stock <= 0) row[subIndex] = false
							else row[subIndex] = true
						} else {
							row[subIndex] = true
						}
					})
					result.push(row)
				})
				this.skusStates = result
			},
			getSkuItem(index, subIndex, subItem) {
				let result = []
				let data = this.attrChoose
				for (let key in data) {
					if (index == key) {
						result.push(subItem)
					} else {
						result.push(this.product.attr_items[key][data[key]])
					}
				}
				return result.join(',')
			},
			convertSkusKey(skus) {
				skus.forEach((item, index) => {
					this.skusValueAsKey[item.value] = item
				})
				
			},
			finish() {
				switch (this.actionType) {
					case 'BUY': 
						this.buy()
					break;
					case 'ADD_CART':
						this.addCart()
					break;
				}
				this.toggleSpec()
			},
			//分享
			share() {
				this.$refs.share.open();
			},
			getShareParam() {
				let title = this.product.title;
				let img = this.showImage
				let desc = this.product.description;
				return {title, img, desc, link: null};
			},
			//收藏
			async toFavorite() {
				if (!this.isLogged) {
					this.goLogin()
					return
				}
				let form = {
					product_id: this.product.id,
					state: this.product.is_favorite ? false : true
				}
				const query = `
					mutation Debug ($product_id: Int!, $state: Boolean!) {
						favorite(product_id: $product_id, state: $state)
					}
				`
				const result = await this.$gql.fetch(query, form, 'Debug');
				const err = result.getError('favorite');
				if (err) result.error('favorite');
				else {
					this.$set(this.product, 'is_favorite', form.state)
				}
			},
			checkOnsale() {
				if (this.product.on_sale == 0) {
					uni.showToast({
						title: '商品已下架',
						icon: 'none'
					})
					return false
				}
				return true
			},
			buy(Obj) {
				if (Obj) this.$set(this, 'extendData', Obj);
				// this.$set(this, 'extendData', Object.assign(Obj, this.extendData))
				if (!this.isLogged) {
					this.goLogin()
					return false
				}
				if (!this.checkOnsale()) return;
				if (!this.userSelected) {
					this.actionType = 'BUY'
					this.toggleSpec()
					return
				}
				if (this.currentSku.stock < this.quantity) {
					uni.showToast({
						title: '库存不足',
						icon: 'none'
					})
					return
				}
				let url = `/pages/order/create?sku_id=` + this.currentSku.id + '&quantity=' + this.quantity;
				if (Object.keys(this.extendData).length > 0)
				url += '&' + this.$tools.queryStringify(this.extendData)
				uni.navigateTo({
					url
				})
			},
			async addCart() {
				if (!this.isLogged) {
					this.goLogin()
					return
				}
				if (!this.checkOnsale()) return;
				if (!this.userSelected) {
					this.actionType = 'ADD_CART'
					this.toggleSpec()
					return
				}
				if (this.currentSku.stock < this.quantity) {
					uni.showToast({
						title: '库存不足',
						icon: 'none'
					})
					return
				}
				let form = {
					sku_id: this.currentSku.id,
					quantity: this.quantity
				}
				this.cartAddProduct(form).then(res => {
					this.getCartProducts()
					uni.showToast({
						title: '加入成功'
					})
				}).catch(e => {

				})
			},
			stopPrevent() {},
			parseProduct(product) {
				product.content = this.$parseHtml(product.content)
				return product
			},
			goLogin() {
				uni.navigateTo({
					url: '/pages/public/login?referer=' + encodeURIComponent('/pages/product/product?id=' + this.id)
				})
			},
			getCoupon() {
				this.$http.post('shopcoupon/api/coupon/getProductCoupon', {
					id: this.id
				}).then(data => {
					this.couponList = data
				})
			},
			couponPop(index) {
				this.$set(this.couponList[index], 'has', 1)
			},
			addGroup(data) {
				this.buy(data)
			}
		},
		onShareAppMessage() {
			let path = `/pages/product/product?id=${this.product.id}&sku_id=${this.currentSku.id}`;
			if (this.user && !this.$tools.isEmpty(this.user.id)) path += `&${this.appInfo.config.shop.share.id_name}=${this.user.uid}`;
			let imageUrl = this.showImage
			return {
				title: this.product.title,
				path,
				imageUrl: imageUrl
			}
		}
	}
</script>

<style lang='scss'>
	page {
		background: $page-color-base;
		padding-bottom: 160upx;
		width: 100vw;
	}
	
	.main-box {
		width: 100vw;
	}

	.iconyou1 {
		font-size: $font-base + 2upx;
		color: #888;
	}

	.carousel {
		height: 722upx;
		position: relative;

		swiper {
			height: 100%;
		}

		.image-wrapper {
			width: 100%;
			height: 100%;
		}

		.swiper-item {
			display: flex;
			justify-content: center;
			align-content: center;
			height: 750upx;
			overflow: hidden;

			image {
				width: 100%;
				height: 100%;
			}
		}

	}

	/* 标题简介 */
	.introduce-section {
		background: #fff;
		padding: 20upx 30upx;

		.title {
			font-size: 32upx;
			color: $font-color-dark;
			line-height: 150%;
			
			text {
				background-color: #fa436a;
				color: #fff;
				border-radius: 10rpx;
				padding: 0 20rpx;
				margin-right: 10rpx;
			}
		}
		.product-desc {
			font-size: 24rpx;
			color: #999;
			line-height: 150%;
		}

		.price-box {
			display: flex;
			align-items: baseline;
			height: 64upx;
			padding: 10upx 0;
			font-size: 26upx;
			color: $uni-color-primary;
		}

		.price {
			font-size: $font-lg + 2upx;
		}

		.m-price {
			margin: 0 12upx;
			color: $font-color-light;
			text-decoration: line-through;
		}

		.coupon-tip {
			align-items: center;
			padding: 4upx 10upx;
			background: $uni-color-primary;
			font-size: $font-sm;
			color: #fff;
			border-radius: 6upx;
			line-height: 1;
			transform: translateY(-4upx);
		}

		.bot-row {
			display: flex;
			align-items: center;
			height: 50upx;
			font-size: $font-sm;
			color: $font-color-light;

			text {
				flex: 1;
			}
		}
	}

	/* 分享 */
	.share-section {
		display: flex;
		align-items: center;
		color: $font-color-base;
		background: linear-gradient(to right, #fdf5f6, #fbebf6);
		padding: 12upx 30upx;

		.share-icon {
			display: flex;
			align-items: center;
			width: 70upx;
			height: 30upx;
			line-height: 1;
			border: 1px solid $uni-color-primary;
			border-radius: 4upx;
			position: relative;
			overflow: hidden;
			font-size: 22upx;
			color: $uni-color-primary;

			&:after {
				content: '';
				width: 50upx;
				height: 50upx;
				border-radius: 50%;
				left: -20upx;
				top: -12upx;
				position: absolute;
				background: $uni-color-primary;
			}
		}

		.icon-xingxing {
			position: relative;
			z-index: 1;
			font-size: 24upx;
			margin-left: 2upx;
			margin-right: 10upx;
			color: #fff;
			line-height: 1;
		}

		.tit {
			font-size: $font-base;
			margin-left: 10upx;
		}

		.icon-bangzhu1 {
			padding: 10upx;
			font-size: 30upx;
			line-height: 1;
		}

		.share-btn {
			flex: 1;
			text-align: right;
			font-size: $font-sm;
			color: $uni-color-primary;
		}

		.icon-you {
			font-size: $font-sm;
			margin-left: 4upx;
			color: $uni-color-primary;
		}
	}

	.c-list {
		font-size: $font-sm + 2upx;
		color: $font-color-base;
		background: #fff;
		display: block;

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

		.con {
			flex: 1;
			color: $font-color-dark;

			.selected-text {
				margin-right: 10upx;
			}
		}

		.quantity-box {
			display: flex;
			justify-content: flex-end;
			align-items: flex-end;
		}

		.bz-list {
			height: 40upx;
			font-size: $font-sm+2upx;
			color: $font-color-dark;

			text {
				display: inline-block;
				margin-right: 30upx;
			}
		}

		.con-list {
			flex: 1;
			display: flex;
			flex-direction: column;
			color: $font-color-dark;
			line-height: 40upx;
		}

		.red {
			color: $uni-color-primary;
		}
	}
	
	.detail-desc {
		background: #fff;
		margin-top: 16upx;
		.d-header {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 80upx;
			font-size: $font-base + 2upx;
			color: $font-color-dark;
			position: relative;

			text {
				padding: 0 20upx;
				background: #fff;
				position: relative;
				z-index: 1;
			}

			&:after {
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translateX(-50%);
				width: 300upx;
				height: 0;
				content: '';
				border-bottom: 1px solid #ccc;
			}
		}
	}

	/* 规格选择弹窗 */
	.attr-content {

		.a-t {
			display: flex;
			padding: 0 20rpx;

			image {
				width: 170upx;
				height: 170upx;
				flex-shrink: 0;
				margin-top: -40upx;
				border-radius: 8upx;
				;
			}

			.right {
				display: flex;
				flex-direction: column;
				padding-left: 24upx;
				font-size: $font-sm + 2upx;
				color: $font-color-base;
				line-height: 42upx;

				.price {
					font-size: $font-lg;
					color: $uni-color-primary;
					margin-bottom: 10upx;
				}

				.selected-text {
					margin-right: 10upx;
				}
			}
		}

		.attr-list {
			display: flex;
			flex-direction: column;
			font-size: $font-base + 2upx;
			color: $font-color-base;
			padding-top: 30upx;
			padding-left: 10upx;
		}

		.attr-quantity {
			display: flex;
			flex: 1;
			flex-direction: row;
			font-size: $font-base;
			color: $font-color-base;
			padding-top: 30upx;
			padding-left: 10upx;
			align-items: center;

			.number-box {
				margin-left: 40upx;
			}
		}

		.item-list {
			padding: 20upx 0 0;
			display: flex;
			flex-wrap: wrap;

			text {
				display: flex;
				align-items: center;
				justify-content: center;
				background: #eee;
				margin-right: 20upx;
				margin-bottom: 20upx;
				border-radius: 100upx;
				min-width: 60upx;
				height: 60upx;
				padding: 0 20upx;
				font-size: $font-base;
				color: $font-color-dark;
			}

			.selected {
				background: #fbebee;
				color: $uni-color-primary;
			}
		}
	}

	/*  弹出层 */
	.popup {
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		z-index: 99;

		&.show {
			display: block;

			.mask {
				animation: showPopup 0.2s linear both;
			}

			.layer {
				animation: showLayer 0.2s linear both;
			}
		}

		&.hide {
			.mask {
				animation: hidePopup 0.2s linear both;
			}

			.layer {
				animation: hideLayer 0.2s linear both;
			}
		}

		&.none {
			display: none;
		}

		.mask {
			position: fixed;
			top: 0;
			width: 100%;
			height: 100%;
			z-index: 1;
			background-color: rgba(0, 0, 0, 0.4);
		}

		.layer {
			position: fixed;
			z-index: 99;
			bottom: 0;
			width: 100%;
			min-height: 40vh;
			border-radius: 10upx 10upx 0 0;
			background-color: #fff;
			padding-bottom: 140upx;

			.btn {
				height: 66upx;
				line-height: 66upx;
				border-radius: 100upx;
				background: $uni-color-primary;
				font-size: $font-base + 2upx;
				color: #fff;
				margin: 30upx auto 20upx;
				position: fixed;
				bottom: 0;
				right: 10upx;
				left: 10upx;
			}
			.item-list {
				.disabled {
					background: #EFEEEE;
					color: #B1B1B1;
				}
			}
			.disabled {
				background: $font-color-disabled;
			}
		}

		@keyframes showPopup {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}

		@keyframes hidePopup {
			0% {
				opacity: 1;
			}

			100% {
				opacity: 0;
			}
		}

		@keyframes showLayer {
			0% {
				transform: translateY(120%);
			}

			100% {
				transform: translateY(0%);
			}
		}

		@keyframes hideLayer {
			0% {
				transform: translateY(0);
			}

			100% {
				transform: translateY(120%);
			}
		}
	}

	/* 底部操作菜单 */
	.page-bottom {
		position: fixed;
		left:0;
		right: 0;
		bottom: 0;
		z-index: 95;
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100upx;
		background: rgba(255, 255, 255, .9);

		.p-b-btn {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			font-size: $font-sm;
			color: $font-color-base;
			width: 96upx;
			height: 80upx;

			.iconfont {
				font-size: 40upx;
				line-height: 48upx;
				color: $font-color-light;
			}

			&.active,
			&.active .iconfont {
				color: $uni-color-primary;
			}

			.icon-fenxiang2 {
				font-size: 42upx;
				transform: translateY(-2upx);
			}

			.icon-shoucang {
				font-size: 46upx;
			}
		}

		.action-btn-group {
			display: flex;
			height: 84upx;
			border-radius: 100px;
			overflow: hidden;
			background: #fa436a;
			margin-left: 20upx;
			position: relative;

			&:after {
				content: '';
				position: absolute;
				top: 50%;
				right: 50%;
				transform: translateY(-50%);
				height: 28upx;
				width: 0;
				border-right: 1px solid rgba(255, 255, 255, .5);
			}

			.action-btn {
				display: flex;
				align-items: center;
				justify-content: center;
				width: 180upx;
				height: 100%;
				font-size: $font-base;
				padding: 0;
				border-radius: 0;
				background: transparent;
			}
		}
		.disabled {
			background: #D6D6D6;
		}

		.groupon-btns {
			.btn {
				display: flex;
				flex-direction: column;
				color: #fff;
				font-size: $font-base;
				padding: 0 30upx;
				align-items: center;
				justify-content: center;
			}
		}
	}

	
</style>
