<template>
	<u-picker :show="show" :columns="[columns]" keyName="name" @confirm="confirm" @cancel="cancel"></u-picker>
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
			columns: {
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
		methods: {
			confirm(v) {
				let item = v.value[0];
				this.show = false;
				this.$emit('ok', item);
			},
			cancel() {
				this.show = false;
			}
		}
	}
</script>

<style>
</style>