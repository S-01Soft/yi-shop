<template>
	<view v-if="isLogged">
		<view v-if="messages.length == 0" style="padding-top: 20%;"><u-empty></u-empty></view>
		<view v-else>
			<view class="item" v-for="(item, index) in messages" :key="index" @tap="onMessageClick(item, index)">
				<text class="time">{{item.create_time_txt}}</text>
				<view class="content">
					<text class="title">{{item.title}}</text>
					<view class="img-wrapper" v-if="item.image">
						<image class="pic" :src="item.image"></image>
					</view>
					<text class="description">
						{{item.description}}
					</text>
					<view class="bot b-t u-flex-row">
						<text :class="item.read_time ? '' : 'red-dot'">查看详情</text>
						<text class="more-icon iconfont iconyou"></text>
					</view>
				</view>
			</view>
			<view class="loading" v-if="loading">
				加载中……
			</view>
			
			<view class="no-more" v-if="!hasMore">
				没有了……
			</view>
			
		</view>
		<u-popup :show="show" @open="open" @close="close">
			<rich-text class="msg-box" :nodes="msgNodes"></rich-text>
		</u-popup>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				messages: [],
				hasMore: false,
				current: 0,
				loading: false,
				page: 1,
				redDots: [],
				msgNodes: [],
				CHECK_AUTH: true,
				isreload: false,
				show: false
			}
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.getMessages()
		},
		computed: {
			...mapState({
				isLogged: state => state.user.isLogged
			})
		},
		methods: {
			reload() {
				this.page = 1;
				this.hasMore = true;
				this.isreload = true;
				this.getMessages();
				uni.stopPullDownRefresh()
			},
			async getMessages() {
				this.loading = true;
				this.hasMore = true;
				let data = await this.$http.post('shop/api/user/getMessages?page=' + this.page, {type: 'shop'});
				if (this.isreload) this.messages = data.data
				else this.messages = this.messages.concat(data.data);
				this.loading = false;
				this.isreload = false
				if (data.current_page < data.last_page) {
					this.hasMore = true;
					this.page ++
				} else {
					this.hasMore = false
				}
			},
			onMessageClick(item, index) {
				if (!item.read_time) {
					this.$http.post('shop/api/user/readMessage', {id: item.id})
					this.$set(this.messages[index], 'read_time', Math.ceil(Date.now() / 1000))					
				}
				if (!item.params || item.params.length == 0) uni.navigateTo({
					url: '/pages/message/show?id=' + item.id
				});
				else this.$jump(item.params)
			},
			open() {
				this.show = true;
			},
			close() {
				this.show = false;
			}
		},
		onPullDownRefresh() {
			this.reload();
		},
		onReachBottom() {
			if (this.hasMore) this.getMessages()
		}
	}
</script>

<style lang='scss'>
	page {
		background-color: #f7f7f7;
		padding-bottom: 30rpx;
	}
	
	.head {
		width: 80vw;
		margin: auto;
		margin-top: 8upx;
	}

	.item {
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.time {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 80rpx;
		padding-top: 10rpx;
		font-size: 26rpx;
		color: #acacac;
	}

	.content {
		width: 85%;
		padding: 0 24rpx;
		background-color: #fff;
		border-radius: 4rpx;
	}

	.title {
		display: flex;
		align-items: center;
		padding: 10rpx 0;
		font-size: 32rpx;
		color: #303133;
	}
	.red-dot {
		position: relative;
		padding-left: 10px;
	}
	.red-dot::before {
		content: ' ';
		display: inline-block;
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: #e81515;
		border: 1px solid #e81515;
		margin-right: 5px;
		position: absolute;
		top: 40%;
		left: 0;
		text-align: center;
	}

	.img-wrapper {
		width: 100%;
		height: 260upx;
		position: relative;
	}

	.pic {
		display: block;
		width: 100%;
		height: 100%;
		border-radius: 6upx;
	}

	.cover {
		display: flex;
		justify-content: center;
		align-items: center;
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, .5);
		font-size: 36upx;
		color: #fff;
	}

	.description {
		display: inline-block;
		padding: 16upx 0;
		font-size: 28upx;
		color: #606266;
		line-height: 38upx;
	}

	.bot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		height: 80upx;
		font-size: 24upx;
		color: #707070;
		position: relative;
	}

	.more-icon {
		font-size: 32upx;
	}
	.loading, .no-more {
		text-align: center;
		color: #888;
		font-size: $font-base;
		margin: 30upx 0 10upx 0;
	}
	.msg-box {
		word-break: break-word;
	}
</style>
