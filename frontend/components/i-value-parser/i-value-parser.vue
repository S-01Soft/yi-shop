<template>
	<text>{{text}}</text>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		name: "i-value-parser",
		data() {
			return {
				text: ''
			};
		},
		props: {
			value: null
		},
		watch: {
			value() {
				this.init()
			}
		},
		mounted() {
			this.text = this.parseLabel(this.value);
			uni.$on('AppInfoChange', data => {
				this.init()
			})
		},
		computed: {
			...mapState({
				appInfo: state => state.appInfo
			})
		},
		methods: {
			init() {
				this.text = this.parseLabel(this.value)
			},
			parseLabel(text) {
				if (typeof text == 'undefined') return '';
				let matches = text.match(/{.+?}/g);
				if (!matches) return text;
				matches.forEach(item => {
					let fieldStr = item.replace("{", "");
					fieldStr = fieldStr.replace("}", "");
					let value = this.parseValue(fieldStr);
					text = text.replace(item, value);
				})
				return text;
			},
			parseValue(field) {
				field = field.trim().split('.');
				var data = this.appInfo;
				for (let i = 0; i < field.length; i++) {
					let item = field[i];
					if (typeof data[item] == 'undefined') return '--';
					data = data[item];
				}
				return typeof data == 'undefined' ? '--' : data;
			}

		}
	}
</script>

<style>

</style>
