<template>
	<u-popup :show="show" @open="open" @close="close" mode="center" :round="true" class="after-sale-box u-flex-row">
		<view class="container">
			<view class="title u-flex-row">
				<text>申请售后</text>
			</view>
			<view class="list">
				<view class="item">
					<view class="item-title">
						<text>类型</text>
					</view>
					<view class="item-content">
						<view class="u-flex-row u-flex-between" @click="picker.show = !picker.show">
							<text>{{text}}</text>
							<!-- <u--input
									:value="text"
									disabled
									disabledColor="#ffffff"
									placeholder="请选择售后类型"
									border="none"
							></u--input> -->
							<u-icon name="arrow-right"></u-icon>
						</view>
						<u-picker @close="picker.show = false" @cancel="picker.show = false" @confirm="pickerConfirm" :show="picker.show" :columns="picker.columns" key-name="text"></u-picker>
						<!-- <uni-data-picker v-model="form.type" @change="handleChange" :localdata="[{value: 1, text: '仅退款'}, {value: 2, text: '退款退货'}, {value: 3, text: '换货'}]"></uni-data-picker> -->
					</view>
				</view>
				<view class="item" v-if="[2, 3].indexOf(form.type) != -1">
					<view class="item-title">
						<text>快递单号</text>
					</view>
					<view class="item-content">
						<input class="input" placeholder-class="input-placeholder" type="text" v-model="form.no" placeholder="快递单号" />
					</view>
				</view>
				<view class="item">
					<view class="item-title">
						<text>备注</text>
					</view>
					<view class="item-content">
						<input class="input" type="text" v-model="form.remark" placeholder-class="input-placeholder" placeholder="备注" />
					</view>
				</view>
			</view>
			<view class="btns u-flex-row">
				<view class="btn btn-cancel" @click="close">
					取消
				</view>
				<view class="btn btn-primary" @click="submit">
					确定
				</view>
			</view>
		</view>
	</u-popup>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					type: 1,
					no: '',
					remark: ''
				},
				show: false,
				picker: {
					show: false,
					columns: [
						[{value: 1, text: '仅退款'}, {value: 2, text: '退款退货'}, {value: 3, text: '换货'}]
					] 
				}
			}
		},
		props: {
			value: {
				type: null
			}
		},
		computed: {
			text() {
				let text = ''
				this.picker.columns[0].forEach(item => {
					if (item.value == this.form.type) text = item.text;
				});
				return text;
			}
		},
		watch: {
		},
		methods: {
			submit() {
				uni.showLoading()
				let form = Object.assign({order_sn: this.value}, this.form)
				this.$http.post('shop/api/order/applyRefund', form).then(data => {
					uni.hideLoading()
					this.initForm()
					this.close()
					this.$emit('callback')
				}).catch(e => {
					uni.hideLoading()
				})
			},
			handleChange(e) {
				this.form.type = e.detail.value[0].value;
			},
			pickerConfirm(e, b) {
				this.form.type = e.value[0].value;
				this.picker.show = false;
			},
			initForm() {
				this.form = {
					type: 1,
					no: '',
					remark: ''
				}
			},
			open() {
				this.show = true;
			},
			close() {
				this.show = false;
			}
		}
	}
</script>

<style lang="scss">
	.after-sale-box {
		font-size: $font-base;
		.container {
			// background-color: #f5f5f5;
			border-radius: 10rpx;
			padding: 20rpx;
			width: calc(80vw - 60rpx);
			.title {
				display: flex;
				justify-content: center;
				font-size: $font-base + 2upx;
				margin: 10upx 0;
			}
			.list {
				margin-top: 20upx;
				.item {
					display: flex;
					flex-direction: row!important;
					justify-content: space-between;
					align-items: center!important;
					margin: 20upx 0;
					border: none!important;
					.radio-group {
						display: flex;
						margin: 20upx 0;
						.radio {
							display: flex;
							align-items: center;
						}
					}
					.item-title {
						display: flex;
						width: 120upx;
						justify-content: center;
					}
					.item-content {
						flex: 1;
						.input {
							padding: 0 10upx;
							border: 1px #ddd solid;
							border-radius: 10upx;
							height: 64upx;
						}
						.input-placeholder {
							font-size: $font-base;
						}
					}
				}
			}
	
			.btns {
				display: flex;
				justify-content: space-between;
				margin-top: 30upx;
				.btn {
					display: flex;
					justify-content: center;
					align-items: center;
					height: 70upx;
					flex: 1;
					font-size: $font-base + 2upx;
				}
				.btn-cancel {
					color: $font-color-light;
				}
				.btn-primary {
					color: $base-color;
				}
			}
		}
	}

</style>
