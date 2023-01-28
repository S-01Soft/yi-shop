<template>
	<yi-modal title="申请售后" v-model="show" @ok="submit" @cancel="close" class="after-sale-box">
		<view class="container">
			<u-alert v-if="appInfo.config.shop && appInfo.config.shop.order.post_sale_tips" type = "error" :description = "appInfo.config.shop.order.post_sale_tips"></u-alert>

			<view class="list">
				<view class="item">
					<view class="item-title">
						<text>类型</text>
					</view>
					<view class="item-content">
						<view class="u-flex-row u-flex-between" @click="picker.show = !picker.show">
							<text>{{text}}</text>
							<u-icon name="arrow-right"></u-icon>
						</view>
						<u-picker @close="picker.show = false" @cancel="picker.show = false" @confirm="pickerConfirm" :show="picker.show" :columns="picker.columns" key-name="text"></u-picker>
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
						<input class="input" v-model="form.remark" placeholder="备注" />
					</view>
				</view>
			</view>

		</view>
			
	</yi-modal>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				form: {
					type: 3,
					no: '',
					remark: ''
				},
				show: false,
				picker: {
					show: false,
					columns: [
						[{value: 3, text: '换货'}, {value: 2, text: '退款退货'}, {value: 1, text: '仅退款'}]
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
			...mapState({
				appInfo: state => state.appInfo
			}),
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
			async submit() {
				uni.showLoading()
				let form = Object.assign({order_sn: this.value}, this.form)
				const query = `
					mutation ($order_sn: String!, $no: String, $remark: String, $type: Int!) {
						applyPostSale(order_sn: $order_sn, no: $no, remark: $remark, type: $type)
					}
				`
				const result = await this.$gql.fetch(query, form);
				uni.hideLoading()
				const err = result.getError('applyPostSale');
				if (err) result.show(err);
				else {
					this.initForm()
					this.close()
					this.$emit('callback')
				}
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
