<template>
	<yi-modal title="查看物流轨迹" v-model="show" @ok="open" @cancel="close" :show-ok="false" cancel-text="关闭">
		<view v-if="code == 'local'">
			该单由商家自行配送
		</view>
		<view v-else>
			<view class="e_info u-flex-row" v-if="!loading && !error">
				<view class="e_name">
					<u-tag type="success" :text="express_name"></u-tag>
				</view>
				<view class="e_code u-flex-js-center">
					<text>{{no}}</text>
				</view>
			</view>
			<view class="list" v-if="!loading && !error">
				<view class="item" v-for="(item, index) in content" :key="index">
					<text>{{item.content}}</text>
					<text>{{item.time}}</text>
					<text class="ic"><text class="i"></text></text>
				</view>
			</view>
			<view class="loading text-center text-gray" v-if="loading">
				加载中……
			</view>
			<view class="error" v-if="error">
				{{error}}
			</view>
		</view>
		
	</yi-modal>
</template>

<script>
	export default {
		data() {
			return {
				content: [],
				loading: true,
				error: null,
				show: false,
				express_name: ''
			}
		},
		props: {
			code: {
				default: ''
			},
			no: {
				default: ''
			},
			phone: {
				default: ''
			}
		},
		methods: {
			open() {
				this.show = true;
				if (this.code == 'local') return ;
				this.loading = true
				this.error = null;
				this.content = [];
				this.$http.post('shop/api/express/trace', {code: this.code, no: this.no, phone: this.phone}).then(data => {
					this.loading = false
					this.content = data.list;
					this.express_name = data.name;
				}).catch(res => {
					this.error = res.message
					this.loading = false
				});
			},
			close() {
				this.show = false;
				this.$set(this, 'content', [])
				this.error = null
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: #f5f5f5;
		border-radius: 10rpx;
		padding: 50rpx 40rpx;
	}
	.list {
		display: flex;
		flex-direction: column;
		.item {
			display: flex;
			flex-direction: column;
			border-left: 1px solid #dcdcdc;
			padding-bottom: 10px;
			padding-left: 15px;
			position: relative;
			color: #888;
			align-items: flex-start!important;
			.ic {
				display: block;
				position: absolute;
				left: -16upx;
				top: 6upx;
				width: 16px;
				height: 16px;
				background-color: #dcdcdc;
				border-radius: 38upx;
				.i {
					display: block;
					width: 12px;
					height: 12px;
					border-radius: 6px;
					position: absolute;
					left: 2px;
					top: 2px;
				}
			}
			&:first-child {
				color: #00bb42;
				.ic {
					background-color: #b0ffd4;
				}
				.i {
					background-color: #00aa2c;
				}
			}
			text:first-child {
				font-size: $font-base + 4;
				margin-bottom: 10upx;
			}
		}
		
	}
	.e_info {
			display: flex;
			margin-bottom: 16upx;
			color: #333;
			.e_code {
				margin-left: 10upx;
			}
		}
	.loading {
		display: flex;
		justify-content: center;
		padding: 20upx 0;
		color: #888;
	}
	.error {
		display: flex;
		justify-content: center;
		padding: 20rpx 20rpx;
		color: #ff1a1a;
	}
</style>
