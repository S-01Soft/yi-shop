<template>
	<view class="container">
		<view class="wrapper">
			<view class="input-content">
				<view class="login-way">
					<view class="right nav" :class="{active: loginWay}" @click="setLoginWay(1)">短信验证码登录</view>
					<view class="left nav" :class="{active: !loginWay}" @click="setLoginWay(0)">账号密码登录</view>
				</view>
				<!--账号密码登录-->
				<view class="input-item" v-if="loginWay == 0">
					<text class="tit">账户</text>
					<input type="text" class="input" v-model="username" placeholder="请输入用户名/手机号码/邮箱" maxlength="20" data-key="input" />
				</view>
				<view class="input-item" v-if="loginWay == 0">
					<text class="tit">密码</text>
					<input type="mobile" class="input" v-model="password" placeholder="8-18位不含特殊字符的数字、字母组合" placeholder-class="input-empty" maxlength="20"
					 password data-key="password"  @confirm="toLogin" />
				</view>

				<!--验证码登录-->
				<view class="input-item" v-if="loginWay == 1">
					<text class="tit">手机号</text>
					<input type="text" class="input" v-model="mobile" placeholder="请输入手机号码" maxlength="11" data-key="mobile" />
				</view>
				<view class="input-item" v-if="loginWay == 1">
					<view class="verify-code-selection u-flex-row">
						<text class="tit">验证码</text>
						<text class="btn-get-code" @click="getCode">获取验证码</text>
					</view>

					<input type="number" class="input" value="" placeholder="请输入验证码" placeholder-class="input-empty" maxlength="8" data-key="code"
					 @input="inputChange" @confirm="toLogin" />
				</view>
			</view>
			
			<view class="tips">
				<text v-if="loginWay == 1">未注册的手机验证后将自动创建账号</text>
			</view>
			
			<button class="confirm-btn" @click="toLogin" :disabled="logining">登录</button>
			<view class="third-party">
				<view class="txt-otherlogin u-block">其他登录方式</view>
				<view class="third-type">
					
						<view class="loginType">
							<!-- #ifdef H5 -->
							<button v-if="$wxApi.isweixin()" class="wechat item btn" @click="vendorLogin('WechatH5')">
								<u-icon size="45" name="weixin-fill" color="rgb(83,194,64)"></u-icon>
								<view class="txt">微信</view>
							</button>
							<!-- #endif -->
							
							<!-- #ifdef MP-WEIXIN -->
							<button v-if="canIUseGetUserProfile" class="wechat item btn" @click="getUserProfile">
								<u-icon size="45" name="weixin-fill" color="rgb(83,194,64)"></u-icon>
								<view class="txt">微信</view>
							</button>
							<button v-else class="wechat item btn" open-type="getUserInfo" @getuserinfo="onGotUserInfo">
								<u-icon size="45" name="weixin-fill" color="rgb(83,194,64)"></u-icon>
								<view class="txt">微信</view>
							</button>
							<!-- #endif -->
							
							<!-- #ifdef APP-PLUS -->
							<button class="wechat item btn" @click="vendorLogin('WechatApp')">
								<u-icon size="45" name="weixin-fill" color="rgb(83,194,64)"></u-icon>
								<view class="txt">微信</view>
							</button>
							<!-- #endif -->
						</view>
				</view>
			
				<view class="hint u-block" v-if="appInfo.config.shop && (appInfo.config.shop.agreement.user_agreement || appInfo.config.shop.agreement.privacy_agreement)">
					登录即代表您同意{{appInfo.config.shop.base.app_name}}
					<text class="link" @click="go(1)" v-if="appInfo.config.shop.agreement.user_agreement">《用户协议》</text>
					<text class="link" @click="go(2)" v-if="appInfo.config.shop.agreement.privacy_agreement">《隐私政策》</text>，并授权 {{appInfo.config.shop.base.app_name}} 使用您的账号信息（如昵称、头像、收获地址等）以便您统一管理
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapMutations,
		mapActions
	} from 'vuex';
	import Account from '@/common/Account';
	import LoggedOutMixin from '@/mixins/loggedOut';
	export default {
		mixins: [LoggedOutMixin],
		data() {
			return {
				username: '',
				password: '',
				logining: false,
				referer: null,
				loginWay: 1,
				mobile: '',
				code: '',
				canIUseGetUserProfile: false,
				LOGGED_OUT: true,
				// #ifdef H5
				loginCode: '',
				// #endif
			}
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.referer = options.referer ? options.referer : null
			// #ifdef H5
			if (options.code) {
				this.getToken(options.code)
			}
			// #endif
			
			// #ifdef MP-WEIXIN
			if (wx.getUserProfile) {
				this.canIUseGetUserProfile = true
			}
			// #endif
		},
		computed: {
			...mapState(['appInfo'])
		},
		methods: {
			...mapActions(['login', 'getCartProducts', 'getUserinfo']),
			inputChange(e) {
				const key = e.currentTarget.dataset.key;
				this[key] = e.detail.value;
			},
			setLoginWay(val) {
				this.loginWay = val
			},
			getCode() {
				uni.showLoading({
					title: '短信发送中'
				})
				this.$http.post('shop/api/index/getVerifyCode', {mobile: this.mobile}).then(res => {
					uni.hideLoading()
					uni.showToast({
						title: "短信已发送"
					})
				})
			},
			getToken(code) {
				this.$http.post('third/api/vendor/code2Token', {code}).then(data => {					
						uni.setStorageSync('token', data)
						this.getUserinfo()
						this.goReferer()
				})
			},
			navBack() {
				if (getCurrentPages().length == 1) uni.switchTab({
					url: '/pages/index/index'
				});
				else uni.navigateBack();
			},
			toRegist() {
				uni.navigateTo({
					url: "/pages/public/registe"
				})
			},
			go(type) {
				switch (type) {
					case 1:
						this.$jump({
							type: 5,
							target: this.appInfo.config.shop.agreement.user_agreement
						})
						break;
					case 2:
						this.$jump({
							type: 5,
							target: this.appInfo.config.shop.agreement.privacy_agreement
						})
						break;
				}
			},
			toCenter() {
				uni.reLaunch({
					url: '/pages/user/index'
				})
			},
			async toLogin() {
				this.logining = true;
				let sendData = {}
				if (this.loginWay == 0) {
					const { username, password } = this
					sendData = { username, password, loginWay: this.loginWay }
				} else if (this.loginWay == 1) {
					const { mobile, code } = this
					sendData = {
						mobile, code, loginWay: this.loginWay
					}
				}
				this.login(sendData).then(res => {
					this.logining = false
					this.getCartProducts()
					this.goReferer()
				}).catch(e => {
					this.logining = false
				})
			},
			async vendorLogin(code, data) {
				let account = Account.init(code)
				switch (code) {
					case 'WechatH5' : {
						account.login(this.referer);
						break;
					}
					case 'WechatMiniApp' : {
						let res = await account.login(data)
						uni.setStorageSync('token', res.token)
						this.getUserinfo()
						this.goReferer()
						break;
					}
					case 'WechatApp' : {
						account.login(data, res => {
							uni.setStorageSync('token', res.token)
							this.getUserinfo()
							this.goReferer()
						}, e => {})
						break;
					}
				}
			},
			goReferer() {
				if (this.referer) {
					uni.reLaunch({
						url: decodeURIComponent(this.referer),
						fail: this.toCenter(),
					})
				} else {
					this.toCenter()
				}
			},
			getUserProfile: function(e) {
				wx.getUserProfile({
					desc: '用于完善会员资料',
					success: res => {
						let data = {							
							encryptedData:res.encryptedData,
							iv: res.iv,
							rawData: res.rawData
						}
						this.vendorLogin('WechatMiniApp', data)
					}
				})
			},
			onGotUserInfo: function(e) {
				let detail = e.detail
				let data = {
					encryptedData:detail.encryptedData,
					iv: detail.iv,
					rawData: detail.rawData
				}
				this.vendorLogin('WechatMiniApp', data)
			}
		},

	}
</script>

<style lang='scss'>
	page {
		background: #fff;
	}

	.container {
		position: relative;
		width: 100vw;
		height: 100vh;
		overflow: hidden;
		background: #fff;
	}

	.wrapper {
		position: relative;
		z-index: 90;
		background: #fff;
		padding-bottom: 40upx;
	}

	.back-btn {
		position: absolute;
		left: 40upx;
		z-index: 9999;
		padding-top: var(--status-bar-height);
		top: 40upx;
		font-size: 70upx;
		color: $font-color-dark;
	}

	.left-top-sign {
		font-size: 120upx;
		color: $page-color-base;
		position: relative;
		text-align: center;
	}

	.right-top-sign {
		position: absolute;
		top: 80upx;
		right: -30upx;
		z-index: 95;

		&:before,
		&:after {
			display: block;
			content: "";
			width: 400upx;
			height: 80upx;
			background: #b4f3e2;
		}

		&:before {
			transform: rotate(50deg);
			border-radius: 0 50px 0 0;
		}

		&:after {
			position: absolute;
			right: -198upx;
			top: 0;
			transform: rotate(-50deg);
			border-radius: 50px 0 0 0;
			/* background: pink; */
		}
	}

	.left-bottom-sign {
		position: absolute;
		left: -270upx;
		bottom: -320upx;
		border: 100upx solid #d0d1fd;
		border-radius: 50%;
		padding: 180upx;
	}

	.welcome {
		position: relative;
		left: 50upx;
		top: 50upx;
		font-size: 46upx;
		color: #555;
		text-shadow: 1px 0px 1px rgba(0, 0, 0, .3);
	}

	.input-content {
		padding: 60upx 60upx 0 60upx;
	}

	.input-item {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		justify-content: center;
		padding: 0 30upx;
		
		background: $page-color-light;
		height: 120upx;
		border-radius: 4px;
		margin-bottom: 50upx;

		&:last-child {
			margin-bottom: 0;
		}

		.tit {
			height: 50upx;
			line-height: 56upx;
			font-size: $font-sm+2upx;
			color: $font-color-base;
		}

		&input {
			height: 60upx;
			font-size: $font-base + 2upx;
			background: $page-color-light;
			color: $font-color-dark;
			width: 100%;
		}
		.input {
			width: 100%;
		}
	}

	.confirm-btn {
		height: 76upx;
		line-height: 76upx;
		border-radius: 50px;
		margin: 70rpx 40rpx 0 40rpx;
		background: $uni-color-primary;
		color: #fff;
		font-size: $font-lg;

		&:after {
			border-radius: 100px;
		}
	}

	.forget-section {
		font-size: $font-sm+2upx;
		color: $font-color-spec;
		text-align: center;
		margin-top: 40upx;
	}

	.register-section {
		position: absolute;
		left: 0;
		bottom: 50upx;
		width: 100%;
		font-size: $font-sm+2upx;
		color: $font-color-base;
		text-align: center;

		text {
			color: $font-color-spec;
			margin-left: 10upx;
		}
	}

	.login-way {
		display: flex;
		flex-direction: row;
		font-size: $font-base;
		justify-content: center;
		margin-bottom: 10upx;

		.left,
		.right {
			margin: 10upx;
		}

		.nav {
			padding: 8upx 50upx;
			border-radius: 40upx;
			border: 1px solid #ccc;
			color: #ccc;
		}

		.active {
			border: 1px solid orangered;
			color: #F5F5F5;;
			background-color: #fa436a;
		}
	}
	
	.tips {
		padding: 20rpx 60rpx;
		color: #888;
	}

	.verify-code-selection {
		display: flex;
		justify-content: space-between;
		width: 100%;

		.btn-get-code {
			display: flex;
			align-items: center;
			font-size: $font-base;
			border: 2upx solid #FF4500;
			color: #FF4500;
			border-radius: 44upx;
			padding: 4upx 20upx;
		}
	}
	
	.third-party {
		display: flex;
		flex-direction: column;
		justify-content: center;
		margin-top: 20upx;
		
		.txt-otherlogin {
			margin-top: 20rpx;
			margin-bottom: 20rpx;
			color: #00000033;
			text-align: center;
			
			&::before {
				display: inline-block;
				content: "";
				width: 25%;
				height: 5px;
				border-top: 1px solid #00000033;
				margin-right: 10px;
			}
			&::after {
				display: inline-block;
				content: "";
				width: 25%;
				height: 5px;
				border-top: 1px solid #00000033;
				margin-left: 10px;
			}
			
		}
		
		
		.loginType {
			display: flex;
			padding: 0 100rpx;
			justify-content: center;
			.item {
				display: flex;
				flex-direction: column;
				align-items: center;
				color: $u-content-color;
				font-size: 28rpx;
				
				.txt {
					height: 100%;
					line-height: 100%;
				}
			}
			
			
			button, button:hover {
				background: #fff;
				color: #222;
				border: none;
			}
			button:after {
				border: none;
			}
		}
		
	}
	
	
	.hint {
		padding: 20rpx 40rpx;
		font-size: 20rpx;
		color: $u-tips-color;
		
		.link {
			color: red;
		}
	}
</style>
