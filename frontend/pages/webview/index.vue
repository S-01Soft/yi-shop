<template>
	<web-view :webview-styles="webviewStyles" :src="weburl"></web-view>
</template>

<script>
	import config from '@/common/config';
	export default {
		data() {
			return {
				weburl: '',
				webviewStyles: {
					progress: {
						color: '#FF3333'
					}
				}
			}
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			let url = options.url;
			this.weburl = decodeURIComponent(url);
			uni.setNavigationBarTitle({
				title: ' '
			});
			// #ifdef H5
			window.addEventListener('message', e => {
				if (e.origin == config.domain) {
					uni.setNavigationBarTitle({
						title: e.data.title
					});
				}
			});
			// #endif
		},
		methods: {}
	}
</script>

<style>
</style>
