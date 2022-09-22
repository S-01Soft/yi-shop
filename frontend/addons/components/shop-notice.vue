<template>
	<view class="" v-if="option.list && option.list.length" :style="{margin: option.margin, padding: option.padding}">
		<notice-bar :switch-time="parseInt(option.interval)" :bg-color="option.background" :color="option.color" :text="text" @click="handleClick" @change="handleChange"></notice-bar>
	</view>
</template>

<script>
	export default {
		name: 'shop-notice',
		data() {
			return {
				item: {}
			}
		},
		props: {
			option: {},
		},
		computed: {
			text() {
				if (!this.option.list) return '';
				let res = [];
				this.option.list.forEach((item, index) => {
					res.push(item.title)
				})
				return res.join('|');
			}
		},
		methods: {
			handleClick(index, text) {
				let item = this.option.list[index];
				this.$jump(item.nav)
			},
			handleChange(res) {
				this.item = this.option.list[res.detail.current]
			}
		}
		
	}
</script>

<style>
</style>
