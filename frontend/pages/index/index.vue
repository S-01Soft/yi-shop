<template>
	<view class="content">
		<i-page :refresh="refresh" @request-ok="handleRequest" @refresh-ok="handleRefreshOk" @refresh-fail="handleRefreshFail" :page-id="appInfo.config && appInfo.config.page_setting && appInfo.config.page_setting.home"></i-page>
		<view class="text-gray u-flex-row u-flex-center u-flex-align-center mb-10">
			<view class="mr-10">Powered by</view> <view><u-link class="link" href="https://www.yiadmin.net" text="YiAdmin"></u-link> </view> 
		</view>
	</view>
</template>
<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				refresh: false
			}
		},
		computed: {
			...mapState({
				tabbar: state => state.menu,
				appInfo: state => state.appInfo
			})
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

<style scoped>
	.link {
		display: block;
		flex: none;
	}
</style>
