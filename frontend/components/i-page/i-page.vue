<template>
	<view class="page-content" :style="{background: data && data.option && data.option.background, minHeight: height + 'px'}">
		<view class="" v-for="(item, index) in list" :key="index">
			<shop-slide v-if="item.type == 'shop-slide'" :refresh="refresh" :option="item.option"></shop-slide>
			<shop-navs v-if="item.type == 'shop-navs'" :refresh="refresh" :option="item.option"></shop-navs>
			<shop-notice v-if="item.type == 'shop-notice'" :refresh="refresh" :option="item.option"></shop-notice>
			<shop-list v-if="item.type == 'shop-list'" :refresh="refresh" :option="item.option"></shop-list>
			<shop-grid v-if="item.type == 'shop-grid'" :refresh="refresh" :option="item.option"></shop-grid>
			<shop-search v-if="item.type == 'shop-search'" :refresh="refresh" :option="item.option"></shop-search>
			<shop-images v-if="item.type == 'shop-images'" :refresh="refresh" :option="item.option"></shop-images>
			<shop-title-bar v-if="item.type == 'shop-title-bar'" :refresh="refresh" :option="item.option"></shop-title-bar>
			<shop-text v-if="item.type == 'shop-text'" :refresh="refresh" :option="item.option"></shop-text>
			<shop-product-list v-if="item.type == 'shop-product-list'" :refresh="refresh" :option="item.option"></shop-product-list>
			<shop-user-head v-if="item.type == 'shop-user-head'" :refresh="refresh" :option="item.option"></shop-user-head>
			<shop-user-order v-if="item.type=='shop-user-order'" :option="item.option" :refresh="refresh"></shop-user-order>
			<shop-user-logout v-if="item.type == 'shop-user-logout'" :refresh="refresh" :option="item.option"></shop-user-logout>
			<shop-coupon v-if="item.type == 'shop-coupon'" :refresh="refresh" :option="item.option"></shop-coupon>
		</view>
	</view>
</template>

<script>
	import Refresh from '@/mixins/refresh'
	
	import { mapActions } from 'vuex'
	
	export default {
		name: 'shop-page',
		mixins: [Refresh],
		data() {
			return {
				data: {},
				height: 0
			}
		},
		computed: {
			list() {
				return this.data.list;
			}
		},
		props: {
			pageId: {
				default: null
			},
			refresh: true
		},
		watch: {
			pageId(v) {
				this.init();
			}
		},
		mounted() {
			const sys = uni.getSystemInfoSync();
			this.height = sys.windowHeight;
			this.init();
		},
		methods: {
			...mapActions(['getAppInfo']),
			async init() {
				if (!this.pageId) return;
				this.data = await this.$http.post('shop/api/index/page', {id: this.pageId});
				this.$emit('request-ok', this.data);
			},
			async refreshHandle() {
				try {
					await this.getAppInfo();
					await this.init();
					return Promise.resolve()
				} catch (e) {
					return Promise.reject(e)
				}
			}
		},
		onReachBottom() {
			uni.$emit('onReachBottom')
		}
	}
</script>

<style lang="scss" scoped>
	.page-content {
		height: 100%;
	}
</style>
