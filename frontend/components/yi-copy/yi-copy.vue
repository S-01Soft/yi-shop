<template>
	<view @click="copy">
		<slot>
			<u-icon name="file-text" size="18"></u-icon>
		</slot>
	</view>
</template>

<script>
	export default {
		name:"yi-copy",
		data() {
			return {
				text: ''
			};
		},
		watch: {
			value(v) {
				this.text = v;
			},
			text(v) {
				this.$emit('input', v);
			}
		},
		mounted() {
			this.text = this.value
		},
		props: {
			value: {
				default: ''
			},
			showOk: {
				default: true
			},
			okText: {
				default: '内容已复制'
			}
		},
		methods: {
			copy() {
				uni.setClipboardData({
					data: this.text,
					success: () => {
						if (this.showOk) {
							uni.showToast({
								title: this.okText
							})
						}
					}
				})
			}
		}
	}
</script>

<style>

</style>