<template>
	<view class="">
		<view class="mode1" v-if="option.mode == 1">
			<view v-for="(item,index) in option.list" :key="index">
				<image @click="$jump(item.nav)" style="width: 100%;" :src="$tools.fixurl(item.image)" mode="widthFix"></image>
			</view>
		</view>
		<view class="mode2 flex-row" v-if="option.mode == 2">
			<view v-for="(item,index) in option.list" :key="index" class="flex-1">
				<image style="width: 100%;" :src="$tools.fixurl(item.image)" mode="widthFix"></image>
			</view>			
		</view>
		<view class="mode3 flex-row" v-if="option.mode == 3">
			<view v-for="(item,index) in option.list" :key="index" class="flex-1">
				<image @click="$jump(item.nav)" style="width: 100%;" :src="$tools.fixurl(item.image)" mode="widthFix"></image>
			</view>			
		</view>
		<view class="mode4 flex-row" v-if="option.mode == 4">
			<view class="left flex-1">
				<image @click="$jump(option.list[0].nav)" style="width: 100%;" :src="$tools.fixurl(option.list[0].image)" mode="widthFix"></image>
			</view>
			<view class="right flex-1 flex-column">
				<image @click="$jump(option.list[1].nav)" style="width: 100%;" :src="$tools.fixurl(option.list[1].image)" mode="widthFix"></image>		
				<image @click="$jump(option.list[2].nav)" style="width: 100%;" :src="$tools.fixurl(option.list[2].image)" mode="widthFix"></image>				
			</view>
		</view>
		<view class="mode5 flex-row" v-if="option.mode == 5">			
			<view v-for="(item,index) in option.list" :key="index" class="flex-1">
				<image @click="$jump(item.nav)" style="width: 100%;" :src="$tools.fixurl(item.image)" mode="widthFix"></image>
			</view>
		</view>
		<view class="mode6 u-block" v-if="option.mode == 6">
			<view @click="$jump(item.nav)" v-for="(item,index) in option.list" :key="index" class="mode6-img-item" style="width: 50%;">
				<image style="width: 100%;" :src="$tools.fixurl(item.image)" mode="widthFix"></image>
			</view>
			
			<view class="clear"></view>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'shop-images',
		props: {
			option: {}
		},
		mounted() {
		}
	}
</script>

<style lang="scss" scoped>
	.mode6 {
		.mode6-img-item {
			float: left;
		}
	}
</style>
