<template>
	<view class="content">
		<view class="row b-b">
			<text class="tit">公司名称</text>
			<input class="input" type="text" v-model="form.name" placeholder="公司名称" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">纳税人识别号</text>
			<input class="input" type="text" v-model="form.no" placeholder="纳税人识别号" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">地址</text>
			<input type="text" v-model="form.address" class="input" placeholder="地址" />
		</view>
		<view class="row b-b">
			<text class="tit">电话</text>
			<input class="input" type="text" v-model="form.phone" placeholder="电话" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">开户银行</text>
			<input class="input" type="text" v-model="form.bank" placeholder="开户银行" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">银行账号</text>
			<input class="input" type="text" v-model="form.account" placeholder="银行账号" placeholder-class="placeholder" />
		</view>
		<view class="row default-row">
			<text class="tit">设为默认</text>
			<switch :checked="form.is_default == 1" color="#fa436a" @change="switchChange" />
		</view>
		<view class="u-block mt-20 mr-20 ml-20">
			<u-button @click="confirm" type="error">提交</u-button>			
		</view>
	</view>
</template>

<script>
	import { mapActions } from 'vuex'
	
	export default {
		data() {
			return {
				CHECK_AUTH: true,
				type: 'add',
				id: 0,
				form: {
					name: '',
					no: '',
					phone: '',
					address_id: null,
					address: '',
					bank: '',
					account: '',
					is_default: 0
				}
			}
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			let title = '新增开票信息';
			if(options.type==='edit'){
				title = '编辑开票信息'
				this.id = options.id
				this.getInfo()
			}
			this.type = options.type;
			uni.setNavigationBarTitle({
				title
			})
		},
		methods: {
			...mapActions(['getInvoiceInfo']),
			switchChange(e){
				this.form.is_default = e.detail.value ? 1 : 0;
			},
			getInfo() {
				this.$http.get('shop/api/invoice/info?id=' + this.id).then(data => {
					this.form = data;
				})
			},
			confirm(){
				let data = this.form;
				if(!data.name){
					uni.showToast({
						title: '请填写公司名称',
						icon: 'none'
					})
					return;
				}
				if(!data.address){
					uni.showToast({
						title: '请填写地址',
						icon: 'none'
					})
					return;
				}
				this.$http.post('shop/api/invoice/editInfo?type=' + this.type, this.form).then(res => {				
					uni.showToast({
						title: `${this.type=='edit' ? '修改': '添加'}成功`
					})
					this.getInvoiceInfo()
					setTimeout(() => {
						uni.navigateBack()
					}, 800)
				})
			},
		}
	}
</script>

<style lang="scss">
	page{
		background: $page-color-base;
		padding-top: 16upx;
	}

	.row{
		display: flex;
		flex-direction: row;
		align-items: center;
		position: relative;
		padding:0 30upx;
		height: 110upx;
		background: #fff;
		
		.tit{
			flex-shrink: 0;
			width: 240upx;
			font-size: 30upx;
			color: $font-color-dark;
		}
		.address-box {
			flex: 1;
		}
		.input{
			flex: 1;
			font-size: 30upx;
			color: $font-color-dark;
		}
		.icon-shouhuodizhi{
			font-size: 36upx;
			color: $font-color-light;
		}
	}
	.default-row{
		margin-top: 16upx;
		.tit{
			flex: 1;
		}
		switch{
			transform: translateX(16upx) scale(.9);
		}
	}
	.add-btn{
		display: flex;
		align-items: center;
		justify-content: center;
		width: 690upx;
		height: 80upx;
		margin: 60upx auto;
		font-size: $font-lg;
		color: #fff;
		background-color: $base-color;
		border-radius: 10upx;
		box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);
	}
</style>
