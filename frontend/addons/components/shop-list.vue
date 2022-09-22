<template>
	<view :style="{padding: option.padding}">
		<view :style="{background: option.background}">
			<i-cell v-for="(item,index) in option.list" :key="index" :icon="item.icon" :iconColor="item.color" :title="item.title" :tips="item.tips" @eventClick="$jump(item.nav)"></i-cell>
			
		</view>
	</view>
</template>

<script>
	import Refresh from '@/mixins/refresh';
	export default {
		mixins: [Refresh],
		name: 'shop-user-list',
		props: {
			option: {}
		}
	}
</script>

<style lang="scss" scoped>
</style>
