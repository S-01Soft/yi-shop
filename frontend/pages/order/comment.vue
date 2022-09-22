<template>
	<view class="comment-content">
		<view>宝贝评分</view>
		<u-rate :minCount="1" :count="5" v-model="form.star"></u-rate>
		<view class="cell-item item-content">
			<textarea v-model="form.content" placeholder="评价内容" />
		</view>
		<view class="u-mt-10">上传图片</view>
		<view class="cell-item item-content">
			<view class="">
				<u-upload placeholder="更换头像" :max-count="1" ref="uUpload" width="100" height="100"
				:fileList="fileList" :previewFullImage="true" :maxCount="9"
				@afterRead="afterRead" @delete="handleRemoveImage"></u-upload>
			</view>
		</view>
		<view class="footer u-block">
			<button type="warn" @click="submit">提交</button>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex';
	export default {
		data() {
			return {
				checkAuth: true,
				form: {
					id: null,
					images: [],
					star: 5
				},
				fileList: [],
				state: 0
			}
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo
			})
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.form.id = options.id
			this.state = options.state;
		},
		methods: {
			starChange(e) {
				this.form.star = e.value
			},
			submit() {
				this.$http.post('shop/api/order/comment', this.form).then(data => {
					uni.redirectTo({
						url: '/pages/order/index?state=' + this.state || 0
					})
				})
			},
			afterRead(event) {
				var self = this;
				uni.uploadFile({
					url: this.$tools.fixurl('/shop/api/user/upload'),
					filePath: event.file.thumb,
					name: 'file',
					header: {
						token: uni.getStorageSync('token')
					},
					success(res) {
						res = JSON.parse(res.data)
						if (res.code != 1) self.$u.toast(res.message)
						else {
							self.form.images.push(res.data);
							self.fileList.push({
								url: self.$tools.fixurl(res.data)
							})
						}
					}
				})
			},
			handleRemoveImage(index, list) {
				this.form.images.splice(index, 1);
				this.fileList.splice(index, 1);
			},
		}
	}
</script>

<style lang="scss" scoped>
	page {
		background-color: #FFFFFF;
	}
	.comment-content {
		font-size: $font-base;
		padding: 10upx 20upx;
		.cell-item {
			display: flex;
			flex-direction: row;
			align-items: center;
		}
		.item-content {
			margin-top: 10upx;
			textarea {
				border: 1px solid #eee;
				border-radius: 10upx;
				flex: 1;
				padding: 10upx;
				
			}
		}
		.footer {
			position: fixed;
			bottom: 20upx;
			width: 100%;
			left: 0;
			
		}
	}
</style>
