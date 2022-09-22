<template>
	<view class="x-coupon" v-if="list && list.length">
		<view class="" v-if="option.title && option.title.length" style="padding:.4rem;border-left: 2px solid #fc7592;margin-bottom: 4upx;">
			{{option && option.title}}
		</view>
		<scroll-view scroll-x>
			<view class="coupon_wrap u-flex-row">
				<view class="coupon" :class="item.user_coupon ? 'bg-gray' : 'bg-red'" v-for="(item,index) in list" :key="index">
					<view class="corner" v-if="item.stock != -1">剩{{item.stock}}张</view>
					<view class="corner" v-else>数量充足</view>
					<view class="coupon_content u-block">
						<view class="coupon_left u-block">
							<view class="u-block">
								<view class="coupon_value u-block">
									<text class="coupon_yen">¥</text>
									{{item.money}}
								</view>
								<view class="coupon_limit">满{{item.order_min_amount}}元可用</view>
								<view class="coupon_desc" style="-webkit-line-clamp: 2;">
									{{item.name}}
								</view>
							</view>
						</view>
						<view class="coupon_right disabled" v-if="item.user_coupon">
							<view class="coupon_action">已领取</view>
						</view>
						<view class="coupon_right" @tap="addCoupon(item, index)" v-else>
							<view class="coupon_action">立即领取</view>
						</view>
					</view>
				</view>

			</view>
		</scroll-view>
	</view>
</template>

<script>
	import Refresh from '@/mixins/refresh';
	export default {
		mixins: [Refresh],
		name: 'shop-coupon',
		data() {
			return {
				list: []
			}
		},
		props: {
			option: {}
		},
		mounted() {
			if (this.$tools.has_addon('shopcoupon')) this.init();
		},
		methods: {
			init() {
				return new Promise((resolve,reject) => {
					this.$http.post('shopcoupon/api/coupon/list', {count: this.option.count}).then(data => {
						this.list = data;
					})
				})
			},
			refreshHandle() {
				return this.init();
			},
			addCoupon(item, index) {
				if (!this.user.id) {
					uni.navigateTo({
						url: '/pages/public/login'
					}) 
				} else {
					this.$http.post('/shopcoupon/api/coupon/addCoupon', {id: item.code}).then(data => {
						this.$set(this.list[index], 'user_coupon', data);
					})
				}
				
				
			}
		}
	}
</script>

<style scoped lang="scss">
	.x-coupon {
		position: relative;
		margin-top: .5rem;
		background: #fff;
		border-radius: .2rem;
		// margin: 0 auto;
		// min-width: 320px;
		// max-width: 540px;
		background: #fff;
		font-size: 14px;
		line-height: 1.5;
		color: #666;
		-webkit-text-size-adjust: 100% !important;
	}

	.coupon_wrap {
		white-space: nowrap;
		height: 5.25rem;
	}

	.coupon_wrap .coupon {
		display: inline-block;
		white-space: normal;
		width: 7.425rem;
		height: 5.25rem;
		background-size: 100% 100%;
		border-radius: .2rem;
		position: relative;
		margin-right: -.4rem;
	}
		
		.bg-red {
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASkAAADSBAMAAADklSvfAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAhUExURQAAAAAAAAcFBdh5dvZeWf////Dw8Pv7+/xhW/1hXP///0ehH+wAAAAJdFJOUwIJDUGh93nE53sgvnMAAANGSURBVHja7d0xb9NAGMbxs8QH8BnUGbvAngqEOrZi6YwqpIyVysDYSiB17MTcdog/QEC6T4nvbEf2xXdJhN/ohv87dIAGP/7d2WG6RymGYZg9Jpt1QlfJN7NfoMEH5prdtx0JZf+BTGSmlSLJvVsQkBpd9MC17j+uZ57+ZsNX6f4oC1JpkVFuZ7hQgavkg+A+lRYbtzGaO1fBq/S5t6jsX1YCM1zH6auU3TpOUDW/X1Qy066iixW48zb4FFXx/df10shM2WwdZVevqKfm95czF3zLSle3Rm4WbnM1P17X07O61G99LLvenwVDmZt+V5/WoVgLH8uu98lSMNT6zu3oZuO+C6Wqnxss30p/kqQyX/ttfR5MVS+8p7DZ68WDaKpvfaqrcKpH7X9h6hPRUGZ904YKL2Bdv+gRln0tvJdNZf60r8sIVb0qx9+FzXvkh3Aqc29TfaxjY5/CsZV4quufZ6fxUPWF9q0ejHgsU9e7UnlWhXwqszOV9xDmulgmkWr4EDZfUEmketL52KpKIlXpWVUmjVTZ+L8xJjWrLFErnUqqwQvLvtpTSYUVVlhhhRVWWGGFFVZYYYUVVlhhhRVWWGGFFVZYYYUVVlhhhRVWWGGFFVZYYYUVVlhhhRVWWGGFFVZYYYUVVlhhhRVWWGGFFVZYYYUVVlhhhRVWWGGFFVZYYYUVVlhhhRVWWGGF1fGtOF80nip1K3du7TI5q1TOQ35K8jzkR/885FROtB4ctG1fWCmkuvCt5E9KXx96Uvoxzm9f35cHnipvX1jiJ/CXu8oK7An8wxW0L/cPwqnatoLyVSTVi87HbTTiD+Hfvt3lKvoIZuO+CVUepwWjPI9sK79PKBduDDF3hW0MKYvqTTDUs/ZC2UadXLJdZd23qxQHtKu4IpNCuolGqUjny+pSbxVC2e6hohJu7bGPeqy1Z7sPqq2OEmw4apvCVBZqOKomQrlPSLZBqa5VzRUvBdqgJkqqWl+x5qxNq1roKtOtbNmGS6BlTHWXdN1ZSu3fMqb6rrb5I3WlWf372gUcRWpXarq/Lmv70uauM/Ta6/LtqrqufyxYqidUqLdP0192rJrGTYXf/7UiRjoL52tqnLoKtZ0Mw+yefxH3Mb3HgbN2AAAAAElFTkSuQmCC) no-repeat;
		}
		.bg-gray {
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASkAAADSCAYAAAAWuzbsAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF+mlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIwLTA3LTEyVDExOjM3OjQ5KzA4OjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMC0wNy0xMlQxNDo1NjozMyswODowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMC0wNy0xMlQxNDo1NjozMyswODowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpmODZjNjg1NS1iN2E1LWE4NDEtYTMzMy00MjliODA5N2RiOGYiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo4ZjlkNWE4Mi04MTQ5LWRjNDAtODlmOS00ZWQ4Y2ZmOGMyMjUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo0MDIyN2EyMC1hMzk0LWZlNGUtOGY5ZC04ZTg0OGViNDM2MjQiPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjQwMjI3YTIwLWEzOTQtZmU0ZS04ZjlkLThlODQ4ZWI0MzYyNCIgc3RFdnQ6d2hlbj0iMjAyMC0wNy0xMlQxMTozNzo0OSswODowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTkgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpmODZjNjg1NS1iN2E1LWE4NDEtYTMzMy00MjliODA5N2RiOGYiIHN0RXZ0OndoZW49IjIwMjAtMDctMTJUMTQ6NTY6MzMrMDg6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz49Ta2uAAAKHElEQVR4nO3cMWhbdx4H8H/V92LHTk/NEHrLbQ2U3OAhHQo3FLr1yg0eOpWSo8NNGVooFy6lGW5IaSl06XRDoUOnGzKUkuGmDIEOzSCOMwlO4QgHvZyhjUjsOtXD3BApVVTJek96kn62Px8Qf9l+fvr7b/1/+v7/evZTKaVGAghKgQJCU6SA0BQpIDRFCghNkQJCU6SA0KIWqSWtdo7tQXYkxqIxx9vxkm2VY7XaebSLuC36Z44yJgsf6AiDrdVWaRc5X6K2s7yF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) no-repeat;
		}

	.coupon_wrap .coupon_content {
		position: absolute;
		top: .3rem;
		left: 0;
		width: 100%;
	}

	.coupon_wrap .coupon_content .coupon_left {
		height: 4.75rem;
		width: 5rem;
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		justify-content: center;
		-webkit-box-align: center;
		-webkit-align-items: center;
		align-items: center;
		margin-left: .35rem;
		float: left;
		text-align: center;
	}

	.coupon_wrap .coupon_content .coupon_left .coupon_value {
		font-size: 1.1rem;
		color: #f2270c;
		line-height: 1.1rem;
		margin-top: .35rem;
	}

	.coupon_wrap .coupon_content .coupon_left .coupon_value .coupon_yen {
		font-size: .75rem;
	}

	.coupon_wrap .coupon_content .coupon_left .coupon_limit {
		margin-top: .05rem;
		font-size: .6rem;
		color: #333;
		line-height: .6rem;
	}

	.coupon_wrap .coupon_content .coupon_left .coupon_desc {
		margin-top: .2rem;
		font-size: .45rem;
		color: #999;
		line-height: .7rem;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		-webkit-box-orient: vertical;
		width: 4.8rem;
	}

	.coupon_wrap .coupon_content .coupon_right {
		height: 4.75rem;
		width: 1.7rem;
		float: left;
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		justify-content: center;
		-webkit-box-align: center;
		-webkit-align-items: center;
		align-items: center;
	}

	.coupon_wrap .coupon_content .coupon_right .coupon_action {
		font-size: .7rem;
		color: #fff;
		width: .75rem;
		font-weight: 700;
		line-height: .9rem;
	}

	.coupon_wrap .corner {
		position: absolute;
		height: .9rem;
		top: .4rem;
		left: .4rem;
		font-size: .4rem;
		color: #fff;
		line-height: .7rem;
		text-align: center;
		z-index: 1;
		padding: 1px .125rem;
		font-weight: 700;
		border-radius: .275rem 0 .3rem 0;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#f33d25), to(#ff8f57));
		background-image: -webkit-linear-gradient(top, #f33d25, #ff8f57);
		background-image: linear-gradient(180deg, #f33d25, #ff8f57);
		white-space: nowrap;
	}
	
	.disabled .coupon_action {
		color: #555!important;
	}
	
	
</style>
