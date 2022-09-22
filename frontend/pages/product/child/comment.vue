<template>
	<view class="eva-section">
		<view class="e-header">
			<text class="tit">评价</text>
			<text>({{total || 0}})</text>
			<text class="tip">好评率 {{good_comment}}</text>
		</view>
		<view class="eva-box u-flex-row" v-for="(item, index) in commentList" :key="index">
			<!-- #ifdef H5 -->
			<img class="portrait" :src="$tools.fixurl(item.user.avatar)" mode="aspectFill"></img>
			<!-- #endif -->
			<!-- #ifndef H5 -->
			<image class="portrait" :src="$tools.fixurl(item.user.avatar)" mode="aspectFill"></image>
			<!-- #endif -->
			<view class="right">
				<view class="name-box u-flex-row">
					<text class="name">{{item.user.nickname}}</text>
					<text>{{item.star > 3 ? '好评' : (item.star > 2 ? '中评' : '差评')}}</text>
				</view>
				<text class="con">{{item.content || '-'}}</text>
				<view class="u-flex-row comment-image-list">
					<view class="comment-image" v-for="(v,k) in item.images" :key="k" @click="handlePreviewCommentImages(item, k)">
						<image :src="$tools.fixurl(v)" mode=""></image>
					</view>						
				</view>
				<view class="bot u-flex-row">
					<text class="attr">购买类型：{{item.sku.value}}</text>
					<text class="time">{{item.create_time_text}}</text>
				</view>
			</view>
		</view>
		<view class="loadmore text-center u-block" @click="commentLoadMore" v-if="showLoadMore">
			加载更多
		</view>
		<view class="loadmore text-center u-block" v-else>
			没有了
		</view>
	</view>
	
</template>

<script>
	export default {
		name: 'product-comment',
		data() {
			return {
				commentList: [],
				good_comment: '100%',
				showLoadMore: false,
				total: 0,
				comment_page: 1,
			}
		},
		props: {
			product: {
				default: {}
			}
		},
		watch: {
			'product.id': function(id) {
				if (id) this.getComments()
			}
		},
		mounted() {
			if (this.product.id) this.getComments()
		},
		methods: {
			getComments(type = 'init') {
				this.$http.post('shop/api/product/getComments', {
					id: this.product.id,
					page: this.comment_page
				}).then(data => {
					this.commentList = type == 'init' ? data.data : this.commentList.concat(data.data)
					this.commentCount = data.total
					this.good_comment = data.good_comment
					this.showLoadMore = data.current_page < data.last_page ? true : false;
					this.total = data.total;
				})
			},
			commentLoadMore() {
				this.comment_page += 1
				this.getComments('loadmore')
			},
			handlePreviewCommentImages(item, index) {
				uni.previewImage({
					current: index,
					urls: this.getImagesUrls(item.images)
				})
			},
			getImagesUrls(images) {
				let urls = [];
				images.forEach(item => {
					urls.push(this.$tools.fixurl(item));
				});
				return urls;
			}
		}
	}
</script>

<style lang="scss" scoped>
	
	/* 评价 */
	.eva-section {
		display: flex;
		flex-direction: column;
		padding: 20upx 30upx;
		background: #fff;
		margin-top: 16upx;
	
		.name-box {
			display: flex;
			justify-content: space-between;
		}
	
		.e-header {
			display: flex;
			flex-direction: row;
			align-items: center;
			height: 70upx;
			font-size: $font-sm + 2upx;
			color: $font-color-light;
	
			.tit {
				font-size: $font-base + 2upx;
				color: $font-color-dark;
				margin-right: 4upx;
			}
	
			.tip {
				flex: 1;
				text-align: right;
			}
	
			.icon-you {
				margin-left: 10upx;
			}
		}
	
		.loadmore {
			display: flex;
			justify-content: center;
			font-size: 24upx;
			padding: 20upx 0;
		}
	}
	
	.eva-box {
		display: flex;
		padding: 20upx 0;
	
		.portrait {
			flex-shrink: 0;
			width: 80upx;
			height: 80upx;
			border-radius: 100px;
		}
	
		.right {
			flex: 1;
			display: flex;
			flex-direction: column;
			font-size: $font-base;
			color: $font-color-base;
			padding-left: 26upx;
	
			.con {
				font-size: $font-base;
				color: $font-color-dark;
				padding: 20upx 0;
			}
	
			.bot {
				display: flex;
				justify-content: space-between;
				font-size: $font-sm;
				color: $font-color-light;
			}
		}
	}
	.comment-image-list {
		flex-wrap: wrap;
	}
	.comment-image {
		width: 140rpx; height: 140rpx;
		margin: 8rpx;
		image {
			width: 100%;
		}
	}
</style>
