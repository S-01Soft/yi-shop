<template>
    <view :style="{margin: option.margin}" @click="$jump(option.nav)">
        <view class="content" :style="{background:option.background,padding: option.padding, color: option.color, fontSize: option.size + 'px', textIndent: option.indent + 'px', textAlign: option.textAlign}">
			<u-parse :content="option.content" :domain="appInfo.config.cmsapp && appInfo.config.cmsapp.base.domain"></u-parse>
		</view>
    </view>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		name: 'shop-text',
		props: {
			option: {}
		},
		computed: {
			...mapState({
				appInfo: state => state.appInfo
			})
		}
	}
</script>

<style scoped>
	.content {
		line-height: 150%;
	}
</style>
