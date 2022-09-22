<template>
	<view v-if="option.list && option.list.length"  class="grid-section clear" :style="{margin: option.margin,padding: option.padding, background: option.background, borderRadius: option.borderRadius}">
		<view class="flex-1">
			<view class="nav-list" v-for="(row, i) in list" :key="i">
				<view class="nav-item" @tap="$jump(item.nav)" v-for="(item, j) in row" :key="j" :style="{width: 100 / option.count + '%'}">
					<view :style="{padding: option.itemPadding}">
						<view class="item" style="text-align: center;">
							<image v-if="item.image" mode="aspectFit" :style="{border: option.imgBorder,borderRadius: option.imgBorderRadius, boxShadow: option.imgBoxShadow, width: option.imgWidth, height: option.imgHeight, padding: option.imgPadding}" :src="$tools.fixurl(item.image)"></image>
							<view class="" v-else style="display: flex;align-items: center;justify-content: center;" :style="{border: option.imgBorder,borderRadius: option.imgBorderRadius, boxShadow: option.imgBoxShadow, padding: option.imgPadding, fontSize: option.itemContentSize, color: option.itemContentColor, fontWeigh: option.itemContentWeigh}">
								<i-value-parser :value="item.content"></i-value-parser>
							</view>
							
							<view v-if="item.title && item.title.length" class="text text-center" :style="{fontSize: option.itemTitleSize,color: option.itemTitleColor}">
								<i-value-parser :value="item.title"></i-value-parser>
							</view>
						</view>
					</view>
					
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	export default {
		name: 'cms-app-grid',
		props: {
			option: {},
			refresh: true
		},
		computed: {
			list: function() {
				var result = [];
				var rows = this.option.list;
				var count = this.option.count;
				if (count == 0) return [];
				var len = Math.ceil(rows.length / count);
				for (var i = 0; i < len; i++) {
					var item = [];
					for (var j = i * count; j < (i + 1) * count; j++) {
						if (rows[j]) {
							item.push(rows[j]);
						}
					}
					result.push(item);
				}
				return result;
			}
		}
	}
</script>

<style lang="scss" scoped>	
	.grid-section {
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		flex-wrap: wrap;
		padding: 30upx 22upx;
		background: #fff;
		margin-bottom: 16upx;
		
		.title {
			font-size: $font-base;
			margin: 10upx 20upx;
			display: flex;
			align-items: center;
			
			.txt {
				margin-left: 10upx;
			}
		}
		.nav-list {
			display: flex;
			flex-direction: row;
		}
	
		.nav-item {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			font-size: $font-sm + 2upx;
			color: $font-color-dark;
			
			.item {
				display: flex;
				flex-direction: column;
			}
		}
	}
</style>
