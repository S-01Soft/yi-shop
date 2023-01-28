const Message = {
	key: '',
	init: function(key) {
		this.key = key;
	},
	getUrl: function(data, key) {
		key = key || this.key;
		let url = null;
		if (data && data.url && data.url[key]) url = data.url[key];
		if (data && data.url && data.url['default']) url = data.url['default'];
		return url;
	}
};

export default Message;