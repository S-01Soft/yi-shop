<template>
	<view class="container p-20" v-if="isLogged">
		<u--form
			labelPosition="left"
			:model="form"
			label-width="80"
			ref="form">
			<u-form-item
				label="头像"
				prop="form.avatar"
				borderBottom>
				<view class="u-flex-row u-flex-align-center">
					<u-avatar :src="$tools.fixurl(form.avatar)" class="pr-20"></u-avatar>
					<u-upload placeholder="更换头像" :max-count="1" ref="uUpload" width="100" height="100" 
					:header="{token: userinfo.token}" :action="$tools.fixurl('/cms/api/image')" 
					@afterRead="afterRead" @delete="handleRemoveImage"></u-upload>
				</view>
			</u-form-item>
			<u-form-item
				label="昵称"
				prop="form.nickname"
				borderBottom>
				<u-input v-model="form.nickname" border="none" placeholder="请输入昵称" clearable></u-input>
			</u-form-item>
			<u-form-item
				label="密码"
				prop="form.password"
				borderBottom>
				<u-input v-model="form.password" border="none" placeholder="留空则不修改" clearable></u-input>
			</u-form-item>
			<u-form-item
				label="邮箱"
				prop="form.email"
				borderBottom>
				<u-input v-model="form.email" border="none" placeholder="绑定邮箱":disabled="true" disabledColor="#fff">
					<template slot="suffix">
						<u-button @click="changeEmail" :text="userinfo.email ? '更换邮箱' : '绑定邮箱'" type="success" size="mini" ></u-button>
					</template>
				</u-input>
			</u-form-item>
			<u-form-item
				label="手机号"
				prop="form.mobile"
				borderBottom>
				<u-input v-model="form.mobile" border="none" placeholder="绑定手机号" :disabled="true" disabledColor="#fff">
					<template slot="suffix">
						<u-button @click="changeMobile" :text="userinfo.mobile ? '更换手机号' : '绑定手机号'" type="success" size="mini" ></u-button>
					</template>
				</u-input>
			</u-form-item>
		</u--form>
		
		<view style="padding: 20rpx;" class="u-block">
			<u-button @click="submit" style="margin-top: 20rpx;" type="error">提交</u-button>			
		</view>
		
		<yi-modal v-model="verify.show" :title="verify.title" @ok="verifyOk" @cancel="verify.show = false">
			<view class="pt-60 pb-60 p-30">
				<u--form label-width="70">
					<u-form-item borderBottom label="新邮箱" v-if="verify.form.type == 'email'">
						<u-input v-model="verify.form.email" clearable placeholder="请输入邮箱" border="none"></u-input>
					</u-form-item>
					<u-form-item borderBottom label="新手机号" v-if="verify.form.type == 'sms'">
						<u-input v-model="verify.form.mobile" clearable placeholder="请输入手机号" border="none"></u-input>
					</u-form-item>
					<u-form-item borderBottom label="验证码" prop="verify.form.code">
						<u-input placeholder="验证码" border="none" v-model="verify.form.code">
							<template slot="suffix">
								<u-code ref="uCode" @change="codeChange" seconds="30"></u-code>
								<u-button @tap="getVerifyCode" type="success" size="mini">{{verify.tips}}</u-button>
							</template>
						</u-input>
					</u-form-item>
				</u--form>
			</view>
		</yi-modal>
		
		<yi-modal v-model="confirm.show" title="安全验证" @ok="confirm.show = false" @cancel="confirm.show = false">
			<view class="pt-60 pb-60 p-30">
				<u--form label-width="70">
					<u-alert title="需要进行安全验证,请选择一种验证方式" class="mb-20"></u-alert>
					<u-form-item borderBottom label="验证方式">
					  <u-radio-group
						v-model="confirm.form.type"
						placement="row"
					  >
						<u-radio
						  :customStyle="{marginBottom: '8px'}"
						  v-for="(item, index) in confirmTypes"
						  :key="index"
						  :label="item.title"
						  :name="item.value"
						>
						</u-radio>
					  </u-radio-group>
					</u-form-item>
					<u-form-item borderBottom label="验证码" prop="confirm.form.code">
						<u-input placeholder="验证码" border="none" v-model="confirm.form.code">
							<template slot="suffix">
								<u-code ref="uCode" @change="codeChange" seconds="30"></u-code>
								<u-button @tap="getConfirmCode" type="success" size="mini">{{verify.tips}}</u-button>
							</template>
						</u-input>
					</u-form-item>
				</u--form>
			</view>
		</yi-modal>
	</view>
	
</template>

<script>
	import {  
	    mapMutations, mapActions, mapState
	} from 'vuex';
	import isLoginMixin from '@/mixins/isLogin'
	export default {
		mixins: [isLoginMixin],
		data() {
			return {
				CHECK_AUTH: true,
				title: '',
				key: '',
				form: {
					nickname: '',
					avatar: '',
					password: '',
					email: '',
					mobile: ''
				},
				extend: {},
				verify: {
					show: false,
					title: '',
					tips: '获取验证码',
					type: '',
					form: {
						type: '',
						mobile: '',
						email: '',
						code: '',
						action: 1
					}
				},
				confirm: {
					show: false,
					types: [
						{title: '短信', value: 'sms'}, {title: '邮件', value: 'email'}
					],
					form: {
						type: 'sms',
						code: '',
						action: 2
					}
				}
			};
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo
			}),
			confirmTypes() {
				let list = [];
				if (this.userinfo.mobile) list.push(this.confirm.types[0])
				if (this.userinfo.email) list.push(this.confirm.types[1])
				return list;
			}
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.form.nickname = this.userinfo.nickname;
			this.form.avatar = this.userinfo.avatar;
			this.form.email = this.userinfo.email;
			this.form.mobile = this.userinfo.mobile;
		},
		methods:{
			...mapMutations(['SAVE_USERINFO']),
			...mapActions(['getUserinfo']),
			edit(title, key){
				this.title = title
				this.key = key
				this.$refs.$prop.open()
			},
			changeEmail() {
				this.verify.title = '绑定邮箱'
				this.verify.type = '邮箱';
				this.verify.show = true;
				this.verify.form.type = 'email';
			},
			changeMobile() {
				this.verify.title = '绑定手机号'
				this.verify.type = '手机号';
				this.verify.show = true;
				this.verify.form.type = 'sms';
			},
			verifyOk() {
				let value = this.verify.form.type == 'email' ? this.verify.form.email : this.verify.form.mobile;
				if (!value) return uni.showToast({icon: 'none', title: `${this.verify.type}不能为空`})
				if (!this.verify.form.code) return uni.showToast({title: '验证码不能为空', icon: 'none'})
				if (this.verify.form.type == 'email') {
					this.form.email = this.verify.form.email;
					this.extend = {
						email: {
							type: 'email',
							code: this.verify.form.code
						}
					}
				}
				if (this.verify.form.type == 'sms') {
					this.form.mobile = this.verify.form.mobile;
					this.extend = {
						mobile: {
							type: 'sms',
							code: this.verify.form.code
						}
					}
				}
				this.verify.show = false;
			},
			async confirmOk() {
				let data = await this.$http.post('cms/api/user/editinfo', {form: this.form});
				if (data.code == 1) {
					this.$u.toast("信息修改成功");
					this.confirm.show = false;					
				} else if (data.code == 2) {
					this.confirm.show = true;
				}
			},
			codeChange() {
			},
			getVerifyCode() {
				const { type, email, mobile, action } = this.verify.form;
				let form = {type, email, mobile, action}
				this.getCode(form);
			},
			getConfirmCode() {
				const { type, action } = this.confirm.form;
				const form = { type, action };
				this.getCode(form);
			},
			getCode(form) {
				if (this.$refs.uCode.canGetCode) {
				  uni.showLoading({
					title: '正在获取验证码'
				  })
				  this.$http.post('cms/api/user/get_verify_code', {form}).then(data => {
					  uni.hideLoading();
					  uni.$u.toast('验证码已发送');
					  this.$refs.uCode.start();
				  })
				} else {
				  uni.$u.toast('倒计时结束后再发送');
				}
			},
			afterRead(event) {
				var self = this;
				uni.uploadFile({
					url: this.$tools.fixurl('/cms/api/image'),
					filePath: event.file.thumb,
					name: 'file',
					header: {
						token: this.userinfo.token
					},
					success(res) {
						res = JSON.parse(res.data)
						if (res.code != 1) self.$u.toast(res.message)
						else self.form.avatar = self.$tools.fixurl(res.data);
					}
				})
			},
			handleRemoveImage(index, list) {
				this.form.avatar = this.userinfo.avatar
			},
			submit(val) {
				this.$http.post('cms/api/user/editinfo', {form: this.form, extend: this.extend, type: this.confirm.form.type, code: this.confirm.form.code}).then(data => {
					if (data.code == 1) {
						this.getUserinfo();
						this.$u.toast('修改成功');
						this.confirm.show = false;
					} else if (data.code == 2) {
						this.confirm.show = true;
					}
				})
			},
			navTo(url) {
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang='scss'>
	page {
		height: 100%;
	}
	.container {
		margin-top: 10upx;
		background: #fff;
		height: 100%;
	}
</style>
