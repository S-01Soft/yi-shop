<template>
	<view class="content">
		<scroll-view scroll-y class="left-aside" v-if="appInfo.config.shop && appInfo.config.shop.category.cat_mode !== '1'">
			<view v-for="(item, index) in list" :key="item.id" class="f-item b-b" :class="{active: index === currentIndex}" @click="tabtap(item, index)">
				{{item.name}}
			</view>
		</scroll-view>
		<scroll-view scroll-with-animation scroll-y class="right-aside" @scroll="asideScroll" :scroll-top="tabScrollTop" v-if="appInfo.config.shop && appInfo.config.shop.category.cat_mode !== '1'">			
			<view class="t-list" v-if="appInfo.config.shop && appInfo.config.shop.category.cat_mode === '2'">
				<view @click="navToList(item)" class="t-item" v-for="(item, index) in slist" :key="index">
					<image class="image" :src="$tools.fixurl(item.image)"></image>
					<text>{{item.name}}</text>
				</view>
			</view>
			<view class="" v-if="appInfo.config.shop && appInfo.config.shop.category.cat_mode === '3'">
				<view v-for="item in slist" :key="item.id" class="s-list" :id="'main-'+item.id">
					<text class="s-item" @click="navToList(item)">{{item.name}}</text>
					<view class="t-list">
						<view @click="navToList(titem)" class="t-item" v-for="(titem, index) in item.childlist" :key="index">
							<image class="image" :src="$tools.fixurl(titem.image)"></image>
							<text>{{titem.name}}</text>
						</view>
					</view>
				</view>
			</view>
		</scroll-view>
		<scroll-view scroll-y="true" v-if="appInfo.config.shop && appInfo.config.shop.category.cat_mode === '1'">
			<view class="t-list">
				<view @click="navToList(item)" class="t-item" v-for="(item, index) in list" :key="index">
					<image class="image" :src="$tools.fixurl(item.image)"></image>
					<text>{{item.name}}</text>
				</view>
				
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import { mapState } from 'vuex';
	
	export default {
		data() {
			return {
				sizeCalcState: false,
				tabScrollTop: 0,
				currentIndex: 0,
				list: [],
				flist: [],
				slist: [],
			}
		},
		async onLoad(options){
			options = await this.onLoadStart(options);
			this.loadData();
			this.wechatShare();
		},
		computed: {
			...mapState({
				appInfo: state => state.appInfo
			})
		},
		methods: {
			async loadData(cb){
				this.$http.get('shop/api/category').then(data => {
					this.list = data
					if (this.list.length) {
						this.currentIndex = 0
						this.getSlist()
					}
					cb && cb()
				})
			},
			getSlist() {
				this.slist = this.list[this.currentIndex].childlist
			},
			//一级分类点击
			tabtap(item, index){
				this.currentIndex = index
				this.getSlist()
			},
			//右侧栏滚动
			asideScroll(e){
				if(!this.sizeCalcState){
					this.calcSize();
				}
				let scrollTop = e.detail.scrollTop;
				let tabs = this.slist.filter(item=>item.top <= scrollTop).reverse();
				if(tabs.length > 0){
					this.currentId = tabs[0].pid;
				}
			},
			//计算右侧栏每个tab的高度等信息
			calcSize(){
				let h = 0;
				this.slist.forEach(item=>{
					let view = uni.createSelectorQuery().select("#main-" + item.id);
					view.fields({
						size: true
					}, data => {
						item.top = h;
						h += data.height;
						item.bottom = h;
					}).exec();
				})
				this.sizeCalcState = true;
			},
			navToList(item){
				uni.navigateTo({
					url: `/pages/product/list?cat_id=` + item.id
				})
			}
		},
		onPullDownRefresh() {
			this.loadData(() => {
				uni.stopPullDownRefresh()
			})
		}
	}
</script>

<style lang='scss'>
	.content {
		display: flex;
		flex-direction: row;
	}
	.left-aside {
		flex-shrink: 0;
		width: 200upx;
		height: 100%;
		background-color: #fff;
	}
	.f-item {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 100upx;
		font-size: 28upx;
		color: $font-color-base;
		position: relative;
		&.active{
			color: $base-color;
			background: #f8f8f8;
			&:before{
				content: '';
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
				height: 36upx;
				width: 8upx;
				background-color: $base-color;
				border-radius: 0 4px 4px 0;
				opacity: .8;
			}
		}
	}

	.right-aside{
		flex: 1;
		overflow: hidden;
		padding-left: 20upx;
	}
	.s-item{
		display: flex;
		flex-direction: row;
		align-items: center;
		height: 70upx;
		padding-top: 8upx;
		font-size: 28upx;
		color: $font-color-dark;
	}
	.t-list{
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		width: 100%;
		background: #fff;
		padding-top: 12upx;
		&:after{
			content: '';
			flex: 99;
			height: 0;
		}
	}
	.t-item{
		flex-shrink: 0;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		width: 176upx;
		font-size: 26upx;
		color: #666;
		padding-bottom: 20upx;
		
		.image{
			width: 140upx;
			height: 140upx;
			border-radius: 10rpx;
		}
	}

</style>
