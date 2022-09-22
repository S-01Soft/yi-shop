export default {
	data() {
		return {
			_eventList: []
		}
	},
	methods: {
		onLoadStart(options) {
			return new Promise(async (resolve, reject) => {
				let list = this.$data._eventList;
				for (let i = 0; i < list.length; i ++) {
					let item = list[i]
					options = await this[item](options)
				}
				resolve(options)
			})
		},
		registerLoadStartEvent(eventName) {
			let list = this.$data._eventList;
			if (list.indexOf(eventName) == -1) this.$data._eventList.push(eventName);
		}
	}
	
}