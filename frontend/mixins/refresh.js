export default {
	props: {
		refresh: false
	},
	watch: {
		refresh(val) {
			if (val) {
				this.refreshHandle && this.refreshHandle().then(res => {
					this.$emit('refresh-ok', res);
				}).catch(e => {
					this.$emit('refresh-fail', e);
				});
			}
		}
	}
}