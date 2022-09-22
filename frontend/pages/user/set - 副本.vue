<template>
	<view class="container pr-10 pl-10" v-if="isLogged">
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
				label="重复密码"
				prop="form.repassword"
				borderBottom>
				<u-input v-model="form.repassword" border="none" placeholder="重复密码" clearable></u-input>
			</u-form-item>
		</u--form>
		
		<view style="padding: 20rpx;" class="u-block">
			<u-button @click="submit" type="error">提交</u-button>
		</view>
	</view>
	
</template>

<script>
	import {  
	    mapMutations, mapActions, mapState
	} from 'vuex';
	import checkAuth from '@/mixins/checkAuth';
	export default {
		mixin: [checkAuth],
		data() {
			return {
				CHECK_AUTH: true,
				title: '',
				key: '',
				form: {
					nickname: '',
					avatar: '',
					password: '',
					repassword: ''
				},
			};
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo,
				isLogged: state => state.user.isLogged
			}),
			token() {
				return uni.getStorageSync('token')
			}
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.form.nickname = this.userinfo.nickname;
			this.form.avatar = this.userinfo.avatar
		},
		methods:{
			...mapMutations(['SAVE_USERINFO']),
			edit(title, key){
				this.title = title
				this.key = key
				this.$refs.$prop.open()
			},
			
			afterRead(event) {
				var self = this;
				uni.uploadFile({
					url: this.$tools.fixurl('/shop/api/user/upload'),
					filePath: event.file.thumb,
					name: 'file',
					header: {
						token: this.token
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
			handleImageChange(res, index, list, name) {
				res = JSON.parse(res.data);
				if (res.code != 1) {
					uni.showToast({
						title: res.message
					});
					return;
				}
				this.form.avatar = this.$tools.fixurl(res.data)
			},
			handleRemoveImage(index, list) {
				this.form.avatar = this.userinfo.avatar
			},
			submit(val) {
				this.$http.post('shop/api/user/editInfo', this.form).then(data => {
					uni.showToast({
						title: '修改成功'
					})
					this.SAVE_USERINFO(data)
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
	page{
		background: $page-color-base;
	}
	.container {
		margin-top: 10upx;
		background: #fff;
	}
</style>
