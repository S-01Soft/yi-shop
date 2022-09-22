<template>
	<view>
		<u-popup mode="center" :show="show" @close="close" @open="open" :round="10" :safeAreaInsetBottom="false" v-bind="{...$attrs,...$props}">
			<view :style="{ width }">
				<view class="title" :style="{background: titleBackground, color: titleColor}">{{title}}</view>
				<view class="content" :style="{padding: contentPadding}">
					<slot></slot>
				</view>
				<view class="footer" v-if="footer">
					<view class="btn danger" @click="handleCancel" v-if="showCancel">{{cancelText}}</view>
					<view class="btn primary" @click="handleOk" v-if="showOk">{{okText}}</view>
				</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	export default {
		name: 'yi-modal',
		data() {
			return {
				show: false,
			}
		},
		props: {
			value: {
				default: true
			},
			width: {
				default: '85vw'
			},
			title: {
				default: ''
			},
			titleBackground: {
				default: '#402147'
			},
			titleColor: {
				default: '#ffffff'
			},
			cancelText: {
				default: '取消'
			},
			okText: {
				default: '确定'
			},
			footer: {
				default: true
			},
			showCancel: {
				default: true
			},
			showOk: {
				default: true
			},
			contentPadding: {
				default: '20px'
			}
		},
		watch: {
			value(v) {
				this.show = v;
			},
			show(v) {
				this.$emit('input', v);
			}
		},
		methods: {
			open() {
			},
			close() {
			},
			handleOk() {
				this.$emit('ok');
			},
			handleCancel() {
				this.$emit('cancel')
			}
		}
	}
</script>
<style scoped lang="scss">

	.title {
		    text-align: center;
		    padding: 30rpx 0;
		    font-size: 36rpx;
		    background: #402147;
		    color: #fff;
		    border-top-left-radius: 20rpx;
		    border-top-right-radius: 20rpx;
	}
	.footer {
		display: flex;
		flex-direction: row;
		
		.btn {
			padding: 30rpx 0;
			font-size: 30rpx;
		}
		.primary {
			flex: 1;
			text-align: center;
			color: red;
		}
		
		.danger {
			flex: 1;
			text-align: center;
			color: #888;
		}
	}
</style>
