import {
	mapState,
	mapActions
} from 'vuex';
export default {
	computed: {
		...mapState({
			share_config_params: state => state.share.config,
			appInfo: state => state.appInfo,
			user: state => state.user.userinfo
		})
	},
	methods: {
		...mapActions(['getShareSignParams']),
		getShareParam() {
			let title = null,
				img = null,
				desc = null,
				link = null;
			return {
				title,
				img,
				desc,
				link
			}
		},
		async wechatShare() {
			if (this.$tools.getPlatform() == 'WX-H5') {
				if (!this.share_config_params) await this.getShareSignParams();
				let {
					title,
					img,
					desc,
					link
				} = this.getShareParam();
				link = link || window.location.href;
				title = title || this.appInfo.config.shop.share.title;
				img = img || this.appInfo.config.shop.share.img;
				desc = desc || this.appInfo.config.shop.share.desc;
				if (this.user && !this.$tools.isEmpty(this.user.id)) {
					let id = this.user.uid;
					let tag = '__TAG__';
					let id_name = this.appInfo.config.shop.share.id_name;
					let r1 = `(\\?${id_name}=)([0-9a-zA-Z]+)`
					let r2 = `(\\&${id_name}=)([0-9a-zA-Z]+)`
					let reg1 = new RegExp(r1);
					let reg2 = new RegExp(r2);
					link = link.replace(reg1, '?' + tag);
					link = link.replace(reg2, '&' + tag);
					let _arr = link.split('#');
					if (_arr.length > 1) {
						_link = _arr[1]
					} else _link = link;
					if (_link.indexOf(tag) == -1) {
						if (_link.indexOf('?') > -1) link += '&' + tag;
						else link += '?' + tag;
					}

					link = link.replace(tag, `${id_name}=${id}`);
				}
				this.$wxApi.wxRegister(this.share_config_params, title, img, desc, link)
			}
		}
	}
}
