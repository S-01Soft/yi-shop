<template>
	<view class="">
		<u-popup mode="center" :show="show" :round="true" :custom-style="{width: '80%'}" @close="close">
			<image v-if="imgUrl" @load="onLoadHandle" @error="onErrorHandle" :src="imgUrl" mode="widthFix"
				style="width:100%;border-radius: 20rpx;"></image>
			<view class="box">
				<!-- #ifdef H5 -->
				<view class="share-tips">请长按保存图片</view>
				<!-- #endif -->
				<!-- #ifdef MP||APP-PLUS -->
				<view class="btn btn-primary btn-sm" @tap="save">保存图片</view>
				<!-- #endif -->

				<view class="close-btn" @click="close">关闭</view>
			</view>
		</u-popup>
		<u-toast ref="toast" />
	</view>
</template>

<script>
	import {
		mapState
	} from 'vuex';
	export default {
		data() {
			return {
				imgUrl: null,
				isLoaded: false,
				show: false
			}
		},
		props: {
			pid: null,
			sku_id: null
		},
		computed: {
			...mapState({
				user: state => state.user.userinfo,
				appInfo: state => state.appInfo
			})
		},
		methods: {
			async open() {
				setTimeout(() => {
					if (!this.isLoaded) {
						uni.showLoading({
							title: '加载中……'
						})
					}
				}, 300)
				this.imgUrl = await this.getImageUrl()
				if (this.imgUrl) this.show = true;
			},
			async getImageUrl() {
				uni.showLoading({
					title: '加载中……'
				})
				try {
					let imgUrl = await this.$http.get('shopshare/api/index/index', {params: {
						id: this.pid,
						sku_id: this.sku_id,
						platform: this.$tools.getPlatform()
					}});
					uni.hideLoading()
					return imgUrl;
				} catch (e) {
					this.isLoaded = true;
					uni.hideLoading()
				}
			},
			close() {
				this.show = false;
			},
			onLoadHandle() {
				this.isLoaded = true;
				uni.hideLoading()
			},
			onErrorHandle() {
				this.isLoaded = true;
				uni.showToast({
					title: '加载失败，请重试',
					icon: 'none'
				})
			},
			save() {
				uni.showToast({
					title: '下载中……',
					icon: 'loading'
				})
				uni.downloadFile({
					url: this.imgUrl,
					success: res => {
						uni.hideToast()
						if (res.statusCode === 200) {
							uni.saveImageToPhotosAlbum({
								filePath: res.tempFilePath,
								success: ret => {
									uni.showToast({
										title: '保存成功'
									})
								}
							})
						}
					},
					error: e => {
						uni.showToast({
							title: '保存失败',
							icon: none
						})
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.btn {
		position: relative;
		display: block;
		margin-left: auto;
		margin-right: auto;
		padding-left: 28px;
		padding-right: 28px;
		box-sizing: border-box;
		font-size: 36px;
		text-align: center;
		text-decoration: none;
		line-height: 2.55555556;
		border-radius: 10px;
		-webkit-tap-highlight-color: transparent;
		overflow: hidden;
		color: #000;
		background-color: #f8f8f8;
	}

	.btn-sm {
		height: 60rpx;
		line-height: 60rpx;
		font-size: 28rpx;
	}

	.btn-primary {
		background: #fa436a;
		color: #fff;
	}

	.box {
		display: flex;
		flex-direction: column;
		justify-content: center;

		.share-tips {
			padding: 10rpx 40rpx;
			margin-top: 20rpx;
			color: #555;
			text-align: center;
		}

		.text {
			color: #555;
		}

		.btn {
			width: 100%;
		}

		.close-btn {
			margin-top: 20rpx;
			margin-bottom: 20rpx;
			text-align: center;
			color: #fa436a;
		}
	}
</style>
