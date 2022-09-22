<template>
	<yi-modal v-model="show" title="选择地址" :showOk="false" @cancel="show = false">
		<view class="c-item" v-for="(item,i) in list" :key="i" @click="confirm(item)">
			<view class="">
				{{item.address}} {{item.street}}
			</view>
			<view class="" style="color: #888;">
				{{item.contactor_name}} {{item.phone}}
			</view>
		</view>	
	</yi-modal>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				show: false
			}
		},
		props: {
			value: {
				default: false
			},
			list: {
				default: []
			}
		},
		watch: {
			value(v) {
				this.show = v;
			},
			show(v) {
				this.$emit('input', v);
			},
		},
		mounted() {
			this.show = this.value;
		},
		methods: {
			confirm(v) {
				this.show = false;
				this.$emit('ok', v);
			},
			cancel() {
				this.show = false;
			}
		}
	}
</script>

<style scoped lang="scss">
	.c-item {
		padding: 10rpx;
		margin: 10rpx;
	}
</style>