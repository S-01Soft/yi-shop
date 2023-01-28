<template>
	<view>
		<view class="u-flex-row contactor" v-for="(item,index) in list" :key="index" @click="goTalk(item, index)">
			<view class="avatar">
				<image class="image" :src="$tools.fixurl(item.touser.avatar)" mode=""></image>
			</view>
			<view class="contactor-right u-flex-column">
				<view class="u-flex-row contactor-right-top">
					<view class="nickname">{{item.touser.nickname}}</view>
					<view class="msgtime">{{item.last_content && formatTime(item.last_time)}}</view>
				</view>
				<view v-if="item.last_content" class="contactor-content u-line-2">
					<text v-if="item.last_content.type == 'text'">{{item.last_content.content}}</text>
					<text v-if="item.last_content.type == 'html'">{{trimHtml(item.last_content.content)}}</text>
					<text v-if="item.last_content.type == 'image'">[图片]</text>
					<text v-if="item.last_content.type == 'local_template'">{{item.last_content.content.data && item.last_content.content.data._title.value}}</text>
				</view>
				<view class="unread_count" v-if="item.unread_count">{{item.unread_count}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex';
	import checkAuth from '@/mixins/checkAuth';
	export default {
		mixins: [checkAuth],
		data() {
			return {
				CHECK_AUTH: true,
				list: []
			}
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo
			}),
		},
		async onLoad(options) {
			options = await this.onLoadStart(options);
			this.subscribe()
			this.getContators();
		},
		methods: {
			subscribe() {
				uni.$on('message', data => {
					this.setContactorTop(data.from, data.message);
				})
				uni.$on('new-message', data => {
					this.setContactorTop(data.from, data.message);
				});
			},
			async getContators() {
				const data = await this.$http.post('message/api/user/getContactors');
				let list = [];
				data.data.forEach(item => {
					item.last_content = this.parseMessage(item.last_content);
					list.push(item);
				})
				this.list = list;
			},
			async getContactor(uid) {
				return await this.$http.post('message/api/user/getContactor', { uid });
			},
			async setContactorTop(uid, message) {
				let item = this.hasContactor(uid);
				let user;
				if (item) {
					this.list.splice(item.index, 1);
					item.user.unread_count ++;
					item.user.last_content = this.parseMessage({
						type: message.msgtype,
						content: message.content
					})
					user = item.user;
				} else {
					user = await this.getContactor(uid)
				}
				this.list = [user].concat(this.list);
			},
			hasContactor(uid) {
				for (var i = 0; i < this.list.length; i ++) {
					var item = this.list[i];
					if (item.to_uid == uid) return { index: i, user: item };
				}
				return false;
			},
			goTalk(item, index) {
				this.$set(this.list[index], 'unread_count', 0);
				this.$u.route('/pages/message/show', {uid: item.touser.uid})
			},
			formatTime(time) {
				let now = Date.now() / 1000;
				if (now - time < 24 * 60 * 60) return this.$u.timeFormat(time, 'hh:MM');
				if (now - time < 365 * 24 * 60 * 60) return this.$u.timeFormat(time, 'mm月dd日 hh:MM');
				return this.$u.timeFormat(time, 'yyyy-mm-dd hh:MM')
			},
			trimHtml(content) {
				return this.$u.trim(content.replace(/<[^>]+>/g, ''), 'all');
			},
			parseMessage(msg) {
				if (msg.type == 'local_template' && typeof msg.content == 'string') msg.content = JSON.parse(msg.content);
				return msg;
			}
		}
	}
</script>

<style scoped lang="scss">
	page {
		background-color: #ffffff;
	}
	.contactor {
		padding: 0 20rpx;
		align-items: center;
		.avatar {
			width: 90rpx;
			height: 90rpx;
			.image {
				width: 90rpx;height: 100%;
				border-radius: 10rpx;
			}
		}
		
		.contactor-right {
			border-bottom: 1px solid #ddd;
			margin: 10rpx 20rpx;
			padding: 20rpx 0;
			color: #666;
			position: relative;
			flex: 1;
			.contactor-right-top {
				justify-content: space-between;
				.nickname {
					font-size: 36rpx;
				}
				.msgtime {
					font-size: 28rpx;
					color: #999;
				}
			}
			.contactor-content {
				margin: 10rpx 0;
				color: #999;
			}
			.unread_count {
				position: absolute;
				top: 0;
				right: -40rpx;
				background-color: red;
				color: #fff;
				border-radius: 50%;
				font-size: 24rpx;
				width: 40rpx;
				height: 40rpx;
				line-height: 40rpx;
				text-align: center;
			}
		}
	}
</style>
