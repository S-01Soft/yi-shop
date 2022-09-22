<template>
	<view class="list-cell log-out-btn u-flex-row" v-if="isLogged" @click="toLogout" :style="{margin: option.margin, padding: option.padding}">
		<text class="cell-tit">退出登录</text>
	</view>
</template>

<script>
	import { mapActions, mapState } from 'vuex'
	export default {
		props: {
			option: {
				default: {}
			}
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo,
				isLogged: state => state.user.isLogged
			})
		},
		methods: {
			...mapActions(['logout']),
			toLogout() {
				uni.showModal({
					content: '确定要退出登录么',
					success: (e) => {
						if (e.confirm) {
							this.logout().then(() => {
								this.viewList = []
								uni.navigateBack()
							})
						}
					}
				});
			},
		}
	}
</script>

<style lang="scss" scoped>
	
		.list-cell {
			display: flex;
			align-items: baseline;
			padding: 20upx $page-row-spacing;
			line-height: 60upx;
			position: relative;
			background: #fff;
			justify-content: center;
	
			&.log-out-btn {
	
				.cell-tit {
					color: $uni-color-primary;
					text-align: center;
					margin-right: 0;
					font-size: $font-base + 6upx;
				}
			}
		}
</style>
