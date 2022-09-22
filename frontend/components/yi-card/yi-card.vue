<template>
	<view>
		<view class="c-item">
			<view class="c-item-title" :style="{borderTopRightRadius: round, borderTopLeftRadius: round, background: titleBackground, color: titleColor}">
				<slot name="title"></slot>
			</view>
			<view class="c-item-content" style="background-color: #fff;" :style="{borderBottomRightRadius: round, borderBottomLeftRadius: round}">
				<slot name="body"></slot>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
			}
		},
		props: {
			round: {
				default: '5px'
			},
			titleBackground: {
				default: '#402147'
			},
			titleColor: {
				default: '#fff'
			}
		},
		methods: {
		}
	}
</script>

<style scoped lang="scss">
	.c-item {
		margin: 20rpx 0;
		border: 1px solid #ddd;
		border-radius: 10rpx;
		.c-item-title {
			font-size: 34rpx;
			padding: 20rpx 10rpx;
			color: #fff;
		}
		.c-item-content {
			padding: 30rpx 20rpx;
			view {
				padding: 10rpx 0;
			}
		}
	}
</style>