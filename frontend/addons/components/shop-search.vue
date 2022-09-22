<template>
	
		<view class="shop-search" :style="{margin: option.margin}">
			<view class="search-box" :style="{padding: option.padding}">
				<view @click="go" class="input" :style="{backgroundColor: option.backgroundColor, textAlign: option.textAlign, border: '1px solid ' + option.borderColor, borderRadius: option.borderRadius + 'px', fontSize: '12px'}">{{option.placeholder}}</view>
			</view>
		</view>
</template>

<script>
	export default {
		props: {
			option: {}
		},
		methods: {
			submit(e) {
				uni.navigateTo({
					url: '/pages/product/list?kw=' + e.detail.value
				})
			},
			go() {
				uni.navigateTo({
					url: '/pages/search/index'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.shop-search .input {
		border: 1px solid #eee;
		padding: 8px 15px;
	}
</style>
