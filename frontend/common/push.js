import config from '@/common/config';
import Push from '@/common/lib/push';

export default {
	init(options) {
		return new Push(Object.assign({
			url: config.push.url,
			app_key: config.push.app_key,
			auth: config.baseUrl + '/push/auth/' + config.push.app_key,
			callback: function(channel_name, push) {
				const cb = function() {
					uni.$u.http.post(push.config.auth, {
						channel_name, socket_id: push.connection.socket_id
					}, {custom: {interceptor: false}}).then(data => {
						data.channel = channel_name;
						push.connection.send(JSON.stringify({event:"pusher:subscribe", data:data}));
					});
				}
				return cb;
			}
		}, options));
	}
};