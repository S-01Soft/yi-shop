<template>
	<view class="content">
		<view class="navbar u-flex-row">
			<view 
				v-for="(item, index) in navList" :key="index" 
				class="nav-item" 
				:class="{current: tabCurrentIndex === index}"
				@click="tabClick(index)"
			>
				{{item.text}}
			</view>
		</view>

		<swiper :current="tabCurrentIndex" class="swiper-box" :style="{height: swiperHeight}" duration="300" @change="changeTab">
			<swiper-item class="tab-content" v-for="(tabItem,tabIndex) in navList" :key="tabIndex">
				<scroll-view 
					class="list-scroll-content" 
					scroll-y
					@scrolltolower="loadData"
				>
					<!-- <view v-if="tabItem.loaded === true && tabItem.orderList.length === 0" style="margin-top: 30%;">
						<u-empty></u-empty>
					</view> -->
					
					<!-- 订单列表 -->
					<view v-for="(item,index) in tabItem.orderList" :key="index"
						class="order-item">
						<view class="i-top b-b u-flex-row">
							<text class="time">{{item.created_at_txt}}</text>
							<u-tag style="margin-right: 10rpx;" type="error" text="拼团" v-if="item.order_type == 1" size="mini" mode="plain"></u-tag>
							<text class="state">{{item.state_tip}}</text>
							<view class="after-sale-status u-flex-row" v-if="item.after_sale_status_tip" @click="showTip(item)">
								<text>{{item.after_sale_status_tip.text}}</text>
								<u-icon name="question-circle-fill" color="#fa436a"></u-icon>
							</view>
							<!-- <text v-if="item.status==-1" 
								class="del-btn iconfont iconshanchu1"
								@click="deleteOrder(item, index)"></text> -->
						</view>
						
						<scroll-view  @click="goOrderDetail(item, tabItem)" v-if="item.products.length > 1" class="goods-box" scroll-x>
							<view v-for="(goodsItem, goodsIndex) in item.products" :key="goodsIndex"
								class="goods-item">
								<image class="goods-img" :src="$tools.fixurl(goodsItem.images[0])" mode="aspectFill"></image>
							</view>
						</scroll-view>
						<view v-if="item.products.length === 1" 
							@click="goOrderDetail(item, tabItem)"
							class="goods-box-single u-flex-row"
							v-for="(goodsItem, goodsIndex) in item.products" :key="goodsIndex">
							<image class="goods-img" :src="$tools.fixurl(goodsItem.images[0])" mode="aspectFill"></image>
							<view class="right">
								<text class="title clamp">{{goodsItem.title}}</text>
								<text class="attr-box">{{goodsItem.attributes}}  x {{goodsItem.quantity}} {{goodsItem.unit}}</text>
								<text class="price">{{goodsItem.price}}</text>
							</view>
						</view>
						
						<view class="price-box u-flex-row">
							共
							<text class="num">{{item.products.length}}</text>
							件商品 订单金额
							<text class="price">{{item.order_price}}</text>
						</view>
						<view class="action-box b-t u-flex-row">
							<button class="action-btn" v-if="[1,2,5].indexOf(parseInt(item.status)) > -1 && item.after_sale_status != 1" @click="goAfterSale(item, tabItem)">申请售后</button>
							<button class="action-btn" v-if="item.after_sale_status == 1" @click="cancelPostSale(item, tabItem)">取消售后</button>
							<button class="action-btn" v-if="item.status == 0" @click="cancelOrder(item)">取消订单</button>
							<button class="action-btn recom" v-if="isShow(item, 'pay')" @click="goPay(item)">立即支付</button>
							<button class="action-btn" v-if="item.is_delivery == 1 && item.status > 1 && item.is_received == 0 && appInfo.config.shop && appInfo.config.shop.express.express_open == 1" @click="goShipStatus(item)">查询物流</button>
							<button class="action-btn recom" v-if="item.status == 2 && item.is_received == 0" @click="receive(item)">确认收货</button>
							<button class="action-btn recom" v-if="item.status == 3 && item.buyer_comment == 0" @click="goComment(item, tabItem)">去评价</button>
							<button class="action-btn" v-if="[-1, 3, 6].indexOf(item.status) != -1" @click="deleteOrder(item, index)">删除订单</button>
						</view>
					</view>
					<u-loadmore style="margin-top: 40rpx;" :status="tabItem.loadingType"></u-loadmore>
				</scroll-view>
			</swiper-item>
		</swiper>
		<after-sale-box @callback="handleOrderRefund" :value="refund_order_sn" ref="$afterSaleBox" type="center"></after-sale-box>
		<express-box ref="$expressBox" :order="currentOrder"></express-box>
	</view>
</template> 

<script>
	import afterSaleBox from './child/afterSaleBox'
	import expressBox from './child/expressBox';
	import { mapState, mapActions } from 'vuex'
	export default {
		components: {
			afterSaleBox,
			expressBox, 
		},
		data() {
			return {
				checkAuth: true,
				tabCurrentIndex: 0,
				navList: [{
						state: 0,
						text: '全部',
						loadingType: 'more',
						orderList: [],
						reload: true,
						page: 1
					},
					{
						state: 1,
						text: '待付款',
						loadingType: 'more',
						orderList: [],
						reload: true,
						page: 1
					},
					{
						state: 2,
						text: '待发货',
						loadingType: 'more',
						orderList: [],
						reload: true,
						page: 1
					},
					{
						state: 3,
						text: '待收货',
						loadingType: 'more',
						orderList: [],
						reload: true,
						page: 1
					},
					{
						state: 4,
						text: '已收货',
						loadingType: 'more',
						orderList: [],
						reload: true,
						page: 1
					},
					 {
						state: 5,
						text: '售后',
						loadingType: 'more',
						orderList: [],
						reload: true,
						page: 1
					} 
				],
				swiperHeight: '',
				refund_order_sn: '',
				currentOrder: null
			};
		},
		computed: {
			...mapState({
				appInfo: state => state.appInfo
			})
		},
		async onLoad(options){
			options = await this.onLoadStart(options);
			options.state = options.state || 0;
			this.tabCurrentIndex = +options.state;
			// #ifndef MP
			this.loadData()
			// #endif
			// #ifdef MP-ALIPAY | MP-TOUTIAO
			this.loadData()
			// #endif
			// #ifdef MP
			if(options.state == 0){
				this.loadData()
			}
			// #endif
			let info = uni.getSystemInfoSync()
			this.swiperHeight = info.windowHeight - 44 + 'px'
		},
		 
		methods: {
			isShow(item, name) {
				switch (name) {
					case 'pay':
						if (item.status != 0) return false;
						if (item.is_pay_deposit && !item.choose_stock) return false;
						if (item.is_pay_deposit && item.transferred) return false;
						return true;
					
				}
				
			},
			//获取订单列表
			async loadData(source, success, error) {
				//这里是将订单挂载到tab列表下
				let index = this.tabCurrentIndex;
				let navItem = this.navList[index];
				let state = navItem.state;
				if(source === 'tabChange' && navItem.loaded === true && navItem.reload == false){
					//tab切换只有第一次需要加载数据
					return;
				}
				if(navItem.loadingType === 'loading'){
					//防止重复加载
					return;
				}
				if (navItem.reload == true) navItem.orderList = []
				
				navItem.loadingType = 'loading';
				const query = `
					query($state: Int = 0, $page: Int = 1) {
						orders(state: $state, page: $page) {
							pagination {
								last_page, current_page
							}
							data {
								order_sn
								status
								state_tip
								order_price
								created_at_txt
								order_type
								after_sale_status_tip {
									tip
									text
								}
								after_sale_status
								is_delivery
								is_received
								buyer_comment
								express_code
								express_no
								express {
									name
								}
								products {
									id
									title
									price
									images
									attributes
									quantity
									unit
								}
							}
						}
					}
				`
				const result = await this.$gql.fetch(query, {state: navItem.state, page: navItem.page});
				const { pagination, data } = result.get('orders');
				data.forEach(item => {
					navItem.orderList.push(item)
				})
				navItem.loaded = true
				navItem.reload = false
				this.$set(navItem, 'page', navItem.page + 1)
				if (pagination.current_page == pagination.last_page) navItem.loadingType = 'nomore'
				else navItem.loadingType = 'more';
				typeof success == 'function' && success(data);
			}, 
			goOrderDetail(item, tabItem) {
				uni.navigateTo({
					url: `/pages/order/detail?order_sn=${item.order_sn}&state=${tabItem.state}`
				})
			},
			goComment(item, tabItem) {
				if (item.products.length > 1) {
					this.goOrderDetail(item, tabItem)
				} else {
					uni.navigateTo({
						url: `/pages/order/comment?id=${item.products[0].id}&state=${tabItem.state}`
					})
				}
				
			},
			//swiper 切换
			changeTab(e){
				this.tabCurrentIndex = e.target.current || e.detail.current;
				this.loadData('tabChange');
			},
			//顶部tab点击
			tabClick(index){
				this.tabCurrentIndex = index;
				this.loadData('tabChange')
			},
			//删除订单
			deleteOrder(item, index){
				let form = {
					order_sn: item.order_sn
				}
				uni.showModal({
					content: '确认删除订单吗？',
					success: async (e) => {
						if (e.confirm) {
							const query = `
								mutation($order_sn: String!) {
									deleteOrder(order_sn: $order_sn)
								}
							`
							const result = await this.$gql.fetch(query, {
								order_sn: item.order_sn
							});
							const err = result.getError('deleteOrder');
							if (err) result.show(err);
							else {
								this.setReload(0)
								this.setReload(this.tabCurrentIndex)
								this.loadData()
							}
						}
					}
				})
			},
			//取消订单
			cancelOrder(item){
				let form = {
					order_sn: item.order_sn
				}
				uni.showModal({
					content: '确认取消订单吗？',
					success: async (e) => {
						if (e.confirm) {
							const query = `
								mutation($order_sn: String!) {
									cancelOrder(order_sn: $order_sn) {
										order_sn
									}
								}
							`;
							const result = await this.$gql.fetch(query, form);
							const err = result.getError('cancelOrder');
							if (err) return result.show(err);
							this.setReload(0)
							this.setReload(this.tabCurrentIndex)
							this.loadData()
						}
					}
				})
			},
			cancelPostSale(item) {
				uni.showModal({
					content: '确定取消售后吗？',
					success: async e => {
						if (e.confirm) {
							const query = `
								mutation ($order_sn: String!) {
									cancelPostSale(order_sn: $order_sn)
								}
							`
							const result = await this.$gql.fetch(query, {
								order_sn: item.order_sn
							});
							const err = result.getError('cancelPostSale');
							if (err) result.show(err);
							else {
								this.setReload(this.tabCurrentIndex);
								this.loadData();
							}							
						}
					}
				})
			},
			goPay(item) {
				uni.navigateTo({
					url: '/pages/pay/index?order_sn=' + item.order_sn
				})
			},
			
			// 订单收货
			receive(item) {
				let form = {
					order_sn: item.order_sn
				}
				uni.showModal({
					content: '确认收货吗？',
					success: async(e) => {
						if (e.confirm) {
							const query = `
								mutation($order_sn: String!) {
									receiveOrder(order_sn: $order_sn)
								}
							`
							const result = await this.$gql.fetch(query, form);
							const err = result.getError('receiveOrder');
							if (err) return result.show(err);
							this.setReload('all')
							this.loadData()
						}
					}
				})
					
			},
			setReload($index) {
				if ($index == 'all') {
					this.navList.forEach((item, index) => {
						item.reload = true
						item.page = 1
						this.$set(this.navList, index, item)
					})
				} else {
					let item = this.navList[$index]
					item.reload = true
					item.page = 1
					this.$set(this.navList, $index, item)
				}
			},
			goAfterSale(item, tabItem) {
				this.refund_order_sn = item.order_sn
				this.$refs.$afterSaleBox.open()
			},
			goShipStatus(item) {
				this.currentOrder = item;
				setTimeout(() => {
					this.$refs.$expressBox.open();
				}, 1)
			},
			handleOrderRefund() {
				this.setReload('all')
				this.loadData()
			},
			showTip(item) {
				uni.showToast({
					icon: 'none',
					title: item.after_sale_status_tip.tip
				})
			}
		},
		onPullDownRefresh() {
			let index = this.tabCurrentIndex;
			let navItem = this.navList[index];
			navItem.orderList = [];
			navItem.loadingType = 'more';
			navItem.page = 1;
			this.loadData(null, () => {
				uni.stopPullDownRefresh()
			}, () => {
				uni.stopPullDownRefresh()
			})
			
		}
	}
</script>

<style lang="scss">
	page, .content{
		background: $page-color-base;
		height: 100%;
	}
	
	.swiper-box{
		/* #ifndef MP-ALIPAY */
		height: calc(100% - 40px);
		/* #endif */
	}
	.list-scroll-content{
		height: 100%;
	}
	
	.navbar{
		display: flex;
		height: 40px;
		padding: 0 5px;
		background: #fff;
		box-shadow: 0 1px 5px rgba(0,0,0,.06);
		position: relative;
		z-index: 10;
		.nav-item{
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			font-size: 15px;
			color: $font-color-dark;
			position: relative;
			&.current{
				color: $base-color;
				&:after{
					content: '';
					position: absolute;
					left: 50%;
					bottom: 0;
					transform: translateX(-50%);
					width: 44px;
					height: 0;
					border-bottom: 2px solid $base-color;
				}
			}
		}
	}

	.uni-swiper-item{
		height: auto;
	}
	.order-item{
		display: flex;
		flex-direction: column;
		padding-left: 30upx;
		background: #fff;
		margin-top: 16upx;
		.i-top{
			display: flex;
			align-items: center;
			height: 80upx;
			padding-right:30upx;
			font-size: $font-base;
			color: $font-color-dark;
			position: relative;
			.time{
				flex: 1;
			}
			.state{
				color: #888;
			}
			.del-btn{
				padding: 10upx 0 10upx 36upx;
				font-size: $font-lg;
				color: $font-color-light;
				position: relative;
				&:after{
					content: '';
					width: 0;
					height: 30upx;
					border-left: 1px solid $border-color-dark;
					position: absolute;
					left: 20upx;
					top: 50%;
					transform: translateY(-50%);
				}
			}
		}
		/* 多条商品 */
		.goods-box{
			height: 160upx;
			padding: 20upx 0;
			white-space: nowrap;
			.goods-item{
				width: 120upx;
				height: 120upx;
				display: inline-block;
				margin-right: 24upx;
			}
			.goods-img{
				display: block;
				width: 100%;
				height: 100%;
				border-radius: 10upx;
			}
		}
		/* 单条商品 */
		.goods-box-single{
			display: flex;
			padding: 20upx 0;
			.goods-img{
				display: block;
				width: 120upx;
				height: 120upx;
				border-radius: 10upx;
			}
			.right{
				flex: 1;
				display: flex;
				flex-direction: column;
				padding: 0 30upx 0 24upx;
				overflow: hidden;
				.title{
					font-size: $font-base + 2upx;
					color: $font-color-dark;
					line-height: 1;
				}
				.attr-box{
					font-size: $font-sm + 2upx;
					color: $font-color-light;
					padding: 10upx 12upx;
				}
				.price{
					font-size: $font-base + 2upx;
					color: $font-color-dark;
					&:before{
						content: '￥';
						font-size: $font-sm;
						margin: 0 2upx 0 8upx;
					}
				}
			}
		}
		
		.price-box{
			display: flex;
			justify-content: flex-end;
			align-items: baseline;
			padding: 20upx 30upx;
			font-size: $font-sm + 2upx;
			color: $font-color-light;
			.num{
				margin: 0 8upx;
				color: $font-color-dark;
			}
			.price{
				font-size: $font-lg;
				color: $font-color-dark;
				&:before{
					content: '￥';
					font-size: $font-sm;
					margin: 0 2upx 0 8upx;
				}
			}
		}
		.action-box{
			display: flex;
			justify-content: flex-end;
			align-items: center;
			height: 100upx;
			position: relative;
			padding-right: 30upx;
		}
		.action-btn{
			width: 160upx;
			height: 60upx;
			margin: 0;
			margin-left: 24upx;
			padding: 0;
			text-align: center;
			line-height: 60upx;
			font-size: $font-sm + 2upx;
			color: $font-color-dark;
			background: #fff;
			border-radius: 100px;
			&:after{
				border-radius: 100px;
			}
			&.recom{
				background: #fff9f9;
				color: $base-color;
				&:after{
					border-color: #f7bcc8;
				}
			}
		}
		.group-wait {
			color: #fa436a;
		}
	}
	
	.load1,
	.load2,
	.load3 {
		height: 24px;
		width: 24px
	}
	
	.load2 {
		transform: rotate(30deg)
	}
	
	.load3 {
		transform: rotate(60deg)
	}
	
	.load1 view:nth-child(1) {
		animation-delay: 0s
	}
	
	.load2 view:nth-child(1) {
		animation-delay: .13s
	}
	
	.load3 view:nth-child(1) {
		animation-delay: .26s
	}
	
	.load1 view:nth-child(2) {
		animation-delay: .39s
	}
	
	.load2 view:nth-child(2) {
		animation-delay: .52s
	}
	
	.load3 view:nth-child(2) {
		animation-delay: .65s
	}
	
	.load1 view:nth-child(3) {
		animation-delay: .78s
	}
	
	.load2 view:nth-child(3) {
		animation-delay: .91s
	}
	
	.load3 view:nth-child(3) {
		animation-delay: 1.04s
	}
	
	.load1 view:nth-child(4) {
		animation-delay: 1.17s
	}
	
	.load2 view:nth-child(4) {
		animation-delay: 1.3s
	}
	
	.load3 view:nth-child(4) {
		animation-delay: 1.43s
	}
	
	.after-sale-status {
		display: flex;
		align-items: center;
		padding-left: 10upx;
		color: $base-color;
	}
	
	@-webkit-keyframes load {
		0% {
			opacity: 1
		}
	
		100% {
			opacity: .2
		}
	}
</style>
