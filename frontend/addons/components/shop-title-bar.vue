<template>
	<view class="shop-title-bar text-center u-block" @click="$jump(option.nav)" :style="{paddingTop: option.padding + 'px', paddingBottom: option.padding + 'px', background: option.backgroundColor, color: option.titleColor, fontSize: option.titleSize + 'px'}">{{option.title}}</view>
</template>

<script>
	export default {
		name: 'shop-title-bar',
		props: {
			option: {}
		}
	}
</script>

<style lang="scss" scoped>
	.shop-title-bar::before {
		display: inline-block;
		content: "";
		width: 15%;
		height: 5px;
		border-top: 1px solid #888;
		margin-right: 10px;
	}

	.shop-title-bar::after {
		display: inline-block;
		content: "";
		width: 15%;
		height: 5px;
		border-top: 1px solid #888;
		margin-left: 10px;
	}
</style>
