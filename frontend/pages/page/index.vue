<template>
	<view class="content">
		<i-page :refresh="refresh" @request-ok="handleRequest" @refresh-ok="handleRefreshOk" @refresh-fail="handleRefreshFail" :page-id="id"></i-page>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				id: 0,
				refresh: false
			}
		},
		computed: {
			...mapState({
				appInfo: state => state.appInfo
			})
		},
		async onLoad(options) {			
			options = await this.onLoadStart(options);
			this.id = options.id;
		},
		methods: {
			handleRefreshOk() {
				this.refresh = false
				uni.stopPullDownRefresh()
			},
			handleRefreshFail() {
				this.refresh = false
				uni.stopPullDownRefresh()
			},
			handleRequest(data) {
				if (data.option.title)
				uni.setNavigationBarTitle({
					title: data.option.title
				});
			}
		},
		onPullDownRefresh() {
			this.refresh = true;
		},
		provide() {
			return {
				page: this
			}
		}
	}
</script>

<style>
</style>
