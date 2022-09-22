<template>
	<view v-if="option.list && option.list.length"  class="cate-section clear" :style="{margin: option.margin, padding: option.padding, background: option.background}">
		<view style="width: 100%;">
			<view class="nav-list clear" v-for="(row, i) in list" :key="i">
				<view class="nav-item" @tap="navToLink(item.nav)" v-for="(item, j) in row" :key="j" style="float:left;" :style="{width: 100 / option.count + '%', padding: option.itemPadding}">
					<view class="item" style="text-align: center;">
						<view class="" style="margin: 0 auto;"  :style="{border: option.imgBorder,borderRadius: option.imgBorderRadius, boxShadow: option.imgBoxShadow, width: option.imgWidth, height: option.imgHeight, padding: option.imgPadding}">
							<image :style="{borderRadius: option.imgBorderRadius}" style="width: 100%;height: 100%;" mode="aspectFit" :src="$tools.fixurl(item.image)"></image>
						</view>
						
						<view v-if="item.title && item.title.length" class="title text-center" :style="{fontSize: option.itemTitleSize,color: option.itemTitleColor}">{{item.title}}</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'shop-navs',
		props: {
			option: {}
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
						if (rows[j]) item.push(rows[j]);
					}
					result.push(item);
				}
				return result;
			}
		},
		methods: {
			navToLink(item) {
				this.$jump(item)
			}
		}
	}
</script>

<style lang="scss" scoped>
	/* 分类 */
	.cate-section {
		display: flex;
		justify-content: space-around;
		align-items: center;
		flex-wrap: wrap;
		padding: 30upx 22upx;
		background: #fff;
		margin-bottom: 16upx;
		
		.nav-list {
			display: flex;
		}
	
		.cate-item {
			display: flex;
			flex-direction: column;
			align-items: center;
			font-size: $font-sm + 2upx;
			color: $font-color-dark;
		}
	/*
		image {
			width: 88upx;
			height: 88upx;
			margin-bottom: 14upx;
			border-radius: 50%;
			opacity: .7;
			box-shadow: 4upx 4upx 20upx rgba(250, 67, 106, 0.3);
		}*/
	}
</style>
