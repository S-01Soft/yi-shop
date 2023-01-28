function parseResult(data, errors) {
	return {
		get: function(key) {
			if (key) return data[key];
			return data;
		},
		getError: function(path) {
			for (var i = 0 ; i < errors.length; i ++) {
				let error = errors[i];
				let _path = error.path.join('.');
				if (path == _path) {
					return {
						code: error.extensions.code || 10000,
						message: error.message,
						category: error.extensions.category
					}
				} 
			}
			return false;
		},
		error: function(paths) {
			paths = typeof paths == 'string' ? [paths] : paths;
			for (var i = 0; i < paths.length; i ++) {
				let path = paths[i];
				let err = this.getError(path);
				if (err) uni.showToast({
					icon: 'none',
					title: err.message
				})
			}
		},
		show: function(error) {
			uni.showToast({
				icon: 'none',
				title: error.message
			})
		}
	}
}

 const gql = {
	option: {
		url: ''
	},
	setConfig: function(callback) {
		this.option = callback(this.option);
		return this;
	},
	fetch: async function(query, variables = null, operationName = null, headers = {}) {
		const data = await uni.$u.http.post(this.option.url, {
			variables, query, operationName
		});
		return Promise.resolve(parseResult(data.data, data.errors || []));
	},
	setUrl: function(url) {
		this.option.url = url;
		return this;
	}
}
uni.$u.gql = gql;
export default gql;