<template>

	<view class="content">
		<u-search placeholder="请输入关键词搜索" v-model="kw" @search="search" @custom="search" :show-action="true"
			actionText="搜索" :animation="true"></u-search>
		<view class="u-flex-row lately-search" v-if="words.length">
			<text>最近搜索</text>
			<text class="iconfont iconshanchu1" @click="clear"></text>
		</view>
		<view class="u-flex-row u-flex-wrap">
			<view @click="goSearch(item)" class="e-tag" v-for="(item,index) in words" :key="index">{{item}}</view>
		</view>
		<view class="u-flex-row hot-search" v-if="hotWords.length">
			<text>热门搜索</text>
		</view>
		<view class="u-flex-row hot-search-box u-flex-wrap">
			<view @click="goSearch(item)" class="e-tag" v-for="(item,index) in hotWords" :key="index">{{item}}</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				words: [],
				kw: '',
				maxCount: 10,
				hotWords: []
			}
		},
		onLoad() {
			this.words = uni.getStorageSync('keywords') || [];
			this.getHotWords()
		},
		methods: {
			back() {
				uni.navigateBack()
			},
			search() {
				if (!this.kw) return;
				this.setWords()
				uni.navigateTo({
					url: '/pages/product/list?kw=' + this.kw
				})
				this.kw = ''
			},
			goSearch(item) {
				this.kw = item;
				this.search()
			},
			clear() {
				this.words = [];
				uni.setStorageSync('keywords', []);
			},
			getHotWords() {
				this.$http.post('shop/api/index/getHotWords').then(data => {
					this.hotWords = data;
				})
			},
			setWords() {
				let index = this.words.indexOf(this.kw);
				if (index == -1) this.words = [this.kw].concat(this.words)
				else {
					this.words.splice(index, 1);
					this.words = [this.kw].concat(this.words)
				}
				if (this.words.length > this.maxCount) {
					this.words.splice(this.maxCount, this.words.length - this.maxCount)
				}
				uni.setStorageSync('keywords', this.words)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {

		.lately-search {
			padding: 16upx 20upx;
			justify-content: space-between;
		}

		.hot-search {
			padding: 16upx 20upx;
		}
	}

	.e-tag {
		border-radius: 5px;
		background: #e5e5e5;
		padding: 2px 15px;
		margin: 5px;

	}
</style>
