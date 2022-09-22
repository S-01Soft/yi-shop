<template>
	<view class="content">
		<i-page :refresh="refresh" @request-ok="handleRequest" @refresh-ok="handleRefreshOk" @refresh-fail="handleRefreshFail" :page-id="appInfo.config && appInfo.config.page_setting && appInfo.config.page_setting.user"></i-page>
		<view class="text-gray u-flex-row u-flex-center u-flex-align-center mb-10">
			<view class="mr-10">Powered by</view> <view><u-link class="link" href="https://www.yiadmin.net" text="YiAdmin"></u-link> </view> 
		</view>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	export default {
		data() {
			return {
				refresh: false
			}
		},
		computed: {
			...mapState({
				tabbar: state => state.menu,
				appInfo: state => state.appInfo,
				userinfo: state => state.user.userinfo,
			})
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			// #ifdef APP-PLUS
			plus.runtime.getProperty(plus.runtime.appid, (wgtInfo) => {
				this.appVersion = wgtInfo.version
			})
			// #endif
			this.wechatShare();
		},
		methods: {
			...mapActions(['logout', 'getUserinfo']),
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
</style>
