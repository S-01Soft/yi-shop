<template>
	<view>
		<view class="content" @touchstart="hideDrawer">
			<scroll-view class="msg-list" scroll-y="true" :scroll-with-animation="scrollAnimation" :scroll-top="scrollTop" :scroll-into-view="scrollToView" @scrolltoupper="loadHistory" upper-threshold="50">
				<u-loadmore :status="loadStatus" />
				<view class="row" v-for="(row,index) in msgList" :key="index" :id="'msg'+row.msgid">
					<view>
						<view class="system u-flex-row">
							<view class="text">
								{{row.create_time}}
							</view>
						</view>
					</view>
					
					<view v-if="row.msgtype=='local_template'" class="local_template u-flex-center" @click="handleLocalTemplateUrl(row)">
						<view class="template-content" v-if="row.content.data">
							<view class="template-body">
								<view class="template-title" v-if="row.content.data._title" :style="{color: row.content.data._title.color}">
									{{row.content.data._title.value}}
								</view>
								<view class="template-description" :style="{color: row.content.data._description.color}" v-if="row.content.data && row.content.data._description && row.content.data._description.value">
									{{row.content.data._description.value}}
								</view>
								<view class="" v-for="(v, k) in row.content.data" :key="k">
									<block v-if="v.text">
										<view class="template-selection">
											<view class="template-selection-title">{{v.text}}：</view>
											<view class="template-selection-content" :style="{color: v.color}">{{v.value}}</view>
										</view>
									</block>
								</view>
								<view class="template-remarks" v-if="row.content && row.content.data && row.content.data._remark && row.content.data._remark.value">
									<view class="template-selection">
										<view class="template-selection-title">备注：</view>
										<view class="template-selection-content">{{row.content.data._remark.value}}</view>
									</view>
								</view>
								<view class="" v-if="typeof ($msg.getUrl(row.content)) !== 'string'">
									<view class="template-more-actions" v-for="(v,j) in $msg.getUrl(row.content)" :key="j" @click="templateJump(v)">
										<view class="template-action-title" :style="{color: v.color || '#555'}">
											{{v.text}}
										</view>
										<view class="">
											<text class="iconfont iconyou1"></text>
										</view>
									</view>
								</view>
							</view>
							
						</view>
					</view>
					<!-- 用户消息 -->
					<block v-else>
						<!-- 自己发出的消息 -->
							<view class="my u-flex-row" v-if="row.from_uid == userinfo.uid">
								<view class="left u-flex-row">
									<view v-if="row.msgtype=='text'" class="bubble">
										<rich-text :nodes="row.content"></rich-text>
									</view>
									<view v-if="row.msgtype=='html'" class="bubble">
										<u-parse :content="row.content"></u-parse>
									</view>
									<view v-if="row.msgtype=='image'" class="bubble img" @tap="showPic(row)">
										<image :src="$tools.fixurl(row.content)"></image>
									</view>
									
								</view>
								<view class="right">
									<image :src="$tools.fixurl(userinfo.avatar)"></image>
								</view>
							</view>
							<!-- 别人发出的消息 -->
							<view class="other u-flex-row" v-if="row.from_uid != userinfo.uid">
								<!-- 左-头像 -->
								<view class="left">
									<image :src="$tools.fixurl(touser.avatar)"></image>
								</view>
								<!-- 右-用户名称-时间-消息 -->
								<view class="right u-flex-row">
									<view class="username u-flex-row">
										<view class="name">{{touser.nickname}}</view>
									</view>
									<!-- 文字消息 -->
									<view v-if="row.msgtype=='text'" class="bubble">
										<rich-text :nodes="row.content"></rich-text>
									</view>
									<view v-if="row.msgtype=='html'" class="bubble">
										<u-parse :content="row.content"></u-parse>
									</view>
									<!-- 图片消息 -->
									<view v-if="row.msgtype=='image'" class="bubble img" @tap="showPic(row)">
										<image :src="$tools.fixurl(row.content)"></image>
									</view>
								</view>
							</view>
					</block>
				</view>
			</scroll-view>
		</view>
		<!-- 抽屉栏 -->
		<view class="popup-layer" :class="popupLayerClass">
			<!-- 表情 --> 
			<swiper class="emoji-swiper" :class="{hidden:hideEmoji}" indicator-dots="true" duration="150">
				<swiper-item v-for="(page,pid) in emojiList" :key="pid">
					<view v-for="(em,eid) in page" :key="eid" @tap="addEmoji(em)">
						<image mode="widthFix" :src="'/static/img/emoji/'+em.url"></image>
					</view>
				</swiper-item>
			</swiper>
			<!-- 更多功能-->
			<view class="more-layer" :class="{hidden:hideMore}">
				<view class="list  u-flex-row">
					<view class="box" @tap="chooseImage"><view class="icon tupian2"></view></view>
					<view class="box" @tap="camera"><view class="icon paizhao"></view></view>
				</view>
			</view>
		</view>
		<!-- 底部输入栏 -->
		<view class="input-box u-flex-row" :class="popupLayerClass">
			<!-- H5下不能录音，输入栏布局改动一下 -->
			<!-- #ifndef H5 -->
			<view class="voice">
				<view class="icon" :class="isVoice?'jianpan':'yuyin'" @tap="switchVoice"></view>
			</view>
			<!-- #endif -->
			<!-- #ifdef H5 -->
			<view class="more" @tap="showMore">
				<view class="icon add"></view>
			</view>
			<!-- #endif -->
			<view class="textbox u-flex-row">
				<view class="text-mode u-flex-row">
					<view class="box u-flex-row">
						<textarea auto-height="true" v-model="textMsg" @focus="textareaFocus"/>
					</view>
<!-- 					<view class="em" @tap="chooseEmoji">
						<view class="icon biaoqing"></view>
					</view> -->
				</view>
			</view>
			<!-- #ifndef H5 -->
			<view class="more" @tap="showMore">
				<view class="icon add"></view>
			</view>
			<!-- #endif -->
			<view class="send u-flex-row" @tap="sendText">
				<view class="btn">发送</view>
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
				touser: {},
				//文字消息
				textMsg:'',
				//消息列表
				loadStatus: 'loadmore',
				scrollTop:0,
				scrollToView:'',
				msgList:[],
				myuid:0,
				scrollAnimation: false,
				
				// 抽屉参数
				popupLayerClass:'',
				// more参数
				hideMore:true,
				//表情定义
				hideEmoji:true,
				emojiList:[
					[{"url":"100.gif",alt:"[微笑]"},{"url":"101.gif",alt:"[伤心]"},{"url":"102.gif",alt:"[美女]"},{"url":"103.gif",alt:"[发呆]"},{"url":"104.gif",alt:"[墨镜]"},{"url":"105.gif",alt:"[哭]"},{"url":"106.gif",alt:"[羞]"},{"url":"107.gif",alt:"[哑]"},{"url":"108.gif",alt:"[睡]"},{"url":"109.gif",alt:"[哭]"},{"url":"110.gif",alt:"[囧]"},{"url":"111.gif",alt:"[怒]"},{"url":"112.gif",alt:"[调皮]"},{"url":"113.gif",alt:"[笑]"},{"url":"114.gif",alt:"[惊讶]"},{"url":"115.gif",alt:"[难过]"},{"url":"116.gif",alt:"[酷]"},{"url":"117.gif",alt:"[汗]"},{"url":"118.gif",alt:"[抓狂]"},{"url":"119.gif",alt:"[吐]"},{"url":"120.gif",alt:"[笑]"},{"url":"121.gif",alt:"[快乐]"},{"url":"122.gif",alt:"[奇]"},{"url":"123.gif",alt:"[傲]"}],
					[{"url":"124.gif",alt:"[饿]"},{"url":"125.gif",alt:"[累]"},{"url":"126.gif",alt:"[吓]"},{"url":"127.gif",alt:"[汗]"},{"url":"128.gif",alt:"[高兴]"},{"url":"129.gif",alt:"[闲]"},{"url":"130.gif",alt:"[努力]"},{"url":"131.gif",alt:"[骂]"},{"url":"132.gif",alt:"[疑问]"},{"url":"133.gif",alt:"[秘密]"},{"url":"134.gif",alt:"[乱]"},{"url":"135.gif",alt:"[疯]"},{"url":"136.gif",alt:"[哀]"},{"url":"137.gif",alt:"[鬼]"},{"url":"138.gif",alt:"[打击]"},{"url":"139.gif",alt:"[bye]"},{"url":"140.gif",alt:"[汗]"},{"url":"141.gif",alt:"[抠]"},{"url":"142.gif",alt:"[鼓掌]"},{"url":"143.gif",alt:"[糟糕]"},{"url":"144.gif",alt:"[恶搞]"},{"url":"145.gif",alt:"[什么]"},{"url":"146.gif",alt:"[什么]"},{"url":"147.gif",alt:"[累]"}],
					[{"url":"148.gif",alt:"[看]"},{"url":"149.gif",alt:"[难过]"},{"url":"150.gif",alt:"[难过]"},{"url":"151.gif",alt:"[坏]"},{"url":"152.gif",alt:"[亲]"},{"url":"153.gif",alt:"[吓]"},{"url":"154.gif",alt:"[可怜]"},{"url":"155.gif",alt:"[刀]"},{"url":"156.gif",alt:"[水果]"},{"url":"157.gif",alt:"[酒]"},{"url":"158.gif",alt:"[篮球]"},{"url":"159.gif",alt:"[乒乓]"},{"url":"160.gif",alt:"[咖啡]"},{"url":"161.gif",alt:"[美食]"},{"url":"162.gif",alt:"[动物]"},{"url":"163.gif",alt:"[鲜花]"},{"url":"164.gif",alt:"[枯]"},{"url":"165.gif",alt:"[唇]"},{"url":"166.gif",alt:"[爱]"},{"url":"167.gif",alt:"[分手]"},{"url":"168.gif",alt:"[生日]"},{"url":"169.gif",alt:"[电]"},{"url":"170.gif",alt:"[炸弹]"},{"url":"171.gif",alt:"[刀子]"}],
					[{"url":"172.gif",alt:"[足球]"},{"url":"173.gif",alt:"[瓢虫]"},{"url":"174.gif",alt:"[翔]"},{"url":"175.gif",alt:"[月亮]"},{"url":"176.gif",alt:"[太阳]"},{"url":"177.gif",alt:"[礼物]"},{"url":"178.gif",alt:"[抱抱]"},{"url":"179.gif",alt:"[拇指]"},{"url":"180.gif",alt:"[贬低]"},{"url":"181.gif",alt:"[握手]"},{"url":"182.gif",alt:"[剪刀手]"},{"url":"183.gif",alt:"[抱拳]"},{"url":"184.gif",alt:"[勾引]"},{"url":"185.gif",alt:"[拳头]"},{"url":"186.gif",alt:"[小拇指]"},{"url":"187.gif",alt:"[拇指八]"},{"url":"188.gif",alt:"[食指]"},{"url":"189.gif",alt:"[ok]"},{"url":"190.gif",alt:"[情侣]"},{"url":"191.gif",alt:"[爱心]"},{"url":"192.gif",alt:"[蹦哒]"},{"url":"193.gif",alt:"[颤抖]"},{"url":"194.gif",alt:"[怄气]"},{"url":"195.gif",alt:"[跳舞]"}],
					[{"url":"196.gif",alt:"[发呆]"},{"url":"197.gif",alt:"[背着]"},{"url":"198.gif",alt:"[伸手]"},{"url":"199.gif",alt:"[耍帅]"},{"url":"200.png",alt:"[微笑]"},{"url":"201.png",alt:"[生病]"},{"url":"202.png",alt:"[哭泣]"},{"url":"203.png",alt:"[吐舌]"},{"url":"204.png",alt:"[迷糊]"},{"url":"205.png",alt:"[瞪眼]"},{"url":"206.png",alt:"[恐怖]"},{"url":"207.png",alt:"[忧愁]"},{"url":"208.png",alt:"[眨眉]"},{"url":"209.png",alt:"[闭眼]"},{"url":"210.png",alt:"[鄙视]"},{"url":"211.png",alt:"[阴暗]"},{"url":"212.png",alt:"[小鬼]"},{"url":"213.png",alt:"[礼物]"},{"url":"214.png",alt:"[拜佛]"},{"url":"215.png",alt:"[力量]"},{"url":"216.png",alt:"[金钱]"},{"url":"217.png",alt:"[蛋糕]"},{"url":"218.png",alt:"[彩带]"},{"url":"219.png",alt:"[礼物]"},]				
				],

				onlineEmoji:{"100.gif":"AbNQgA.gif","101.gif":"AbN3ut.gif","102.gif":"AbNM3d.gif","103.gif":"AbN8DP.gif","104.gif":"AbNljI.gif","105.gif":"AbNtUS.gif","106.gif":"AbNGHf.gif","107.gif":"AbNYE8.gif","108.gif":"AbNaCQ.gif","109.gif":"AbNN4g.gif","110.gif":"AbN0vn.gif","111.gif":"AbNd3j.gif","112.gif":"AbNsbV.gif","113.gif":"AbNwgs.gif","114.gif":"AbNrD0.gif","115.gif":"AbNDuq.gif","116.gif":"AbNg5F.gif","117.gif":"AbN6ET.gif","118.gif":"AbNcUU.gif","119.gif":"AbNRC4.gif","120.gif":"AbNhvR.gif","121.gif":"AbNf29.gif","122.gif":"AbNW8J.gif","123.gif":"AbNob6.gif","124.gif":"AbN5K1.gif","125.gif":"AbNHUO.gif","126.gif":"AbNIDx.gif","127.gif":"AbN7VK.gif","128.gif":"AbNb5D.gif","129.gif":"AbNX2d.gif","130.gif":"AbNLPe.gif","131.gif":"AbNjxA.gif","132.gif":"AbNO8H.gif","133.gif":"AbNxKI.gif","134.gif":"AbNzrt.gif","135.gif":"AbU9Vf.gif","136.gif":"AbUSqP.gif","137.gif":"AbUCa8.gif","138.gif":"AbUkGQ.gif","139.gif":"AbUFPg.gif","140.gif":"AbUPIS.gif","141.gif":"AbUZMn.gif","142.gif":"AbUExs.gif","143.gif":"AbUA2j.gif","144.gif":"AbUMIU.gif","145.gif":"AbUerq.gif","146.gif":"AbUKaT.gif","147.gif":"AbUmq0.gif","148.gif":"AbUuZV.gif","149.gif":"AbUliF.gif","150.gif":"AbU1G4.gif","151.gif":"AbU8z9.gif","152.gif":"AbU3RJ.gif","153.gif":"AbUYs1.gif","154.gif":"AbUJMR.gif","155.gif":"AbUadK.gif","156.gif":"AbUtqx.gif","157.gif":"AbUUZ6.gif","158.gif":"AbUBJe.gif","159.gif":"AbUdIO.gif","160.gif":"AbU0iD.gif","161.gif":"AbUrzd.gif","162.gif":"AbUDRH.gif","163.gif":"AbUyQA.gif","164.gif":"AbUWo8.gif","165.gif":"AbU6sI.gif","166.gif":"AbU2eP.gif","167.gif":"AbUcLt.gif","168.gif":"AbU4Jg.gif","169.gif":"AbURdf.gif","170.gif":"AbUhFS.gif","171.gif":"AbU5WQ.gif","172.gif":"AbULwV.gif","173.gif":"AbUIzj.gif","174.gif":"AbUTQs.gif","175.gif":"AbU7yn.gif","176.gif":"AbUqe0.gif","177.gif":"AbUHLq.gif","178.gif":"AbUOoT.gif","179.gif":"AbUvYF.gif","180.gif":"AbUjFU.gif","181.gif":"AbaSSJ.gif","182.gif":"AbUxW4.gif","183.gif":"AbaCO1.gif","184.gif":"Abapl9.gif","185.gif":"Aba9yR.gif","186.gif":"AbaFw6.gif","187.gif":"Abaiex.gif","188.gif":"AbakTK.gif","189.gif":"AbaZfe.png","190.gif":"AbaEFO.gif","191.gif":"AbaVYD.gif","192.gif":"AbamSH.gif","193.gif":"AbaKOI.gif","194.gif":"Abanld.gif","195.gif":"Abau6A.gif","196.gif":"AbaQmt.gif","197.gif":"Abal0P.gif","198.gif":"AbatpQ.gif","199.gif":"Aba1Tf.gif","200.png":"Aba8k8.png","201.png":"AbaGtS.png","202.png":"AbaJfg.png","203.png":"AbaNlj.png","204.png":"Abawmq.png","205.png":"AbaU6s.png","206.png":"AbaaXn.png","207.png":"Aba000.png","208.png":"AbarkT.png","209.png":"AbastU.png","210.png":"AbaB7V.png","211.png":"Abafn1.png","212.png":"Abacp4.png","213.png":"AbayhF.png","214.png":"Abag1J.png","215.png":"Aba2c9.png","216.png":"AbaRXR.png","217.png":"Aba476.png","218.png":"Abah0x.png","219.png":"Abdg58.png"},
				//红包相关参数
				windowsState:'',
				page: 0,
				nomore: false
			};
		},
		async onLoad(option) {
			option = await this.onLoadStart(option);
			let data = await this.getUser(option.uid);
			this.touser = data.touser;
			uni.setNavigationBarTitle({
				title: this.touser.nickname
			})
			this.getMsgList().then(() => {
				this.$nextTick(function() {
					this.scrollTop = 9999;
					this.$nextTick(function() {
						this.scrollAnimation = true;
					});
					
				});
			})
			this.subscribe()
		},
		computed: {
			...mapState({
				userinfo: state => state.user.userinfo
			}),
			msgImgList() {
				let list = [];
				this.msgList.map(item => {
					if (item.msgtype == 'image') list.push(this.$tools.fixurl(item.content))
				});
				return list;
			}
		},
		onShow(){
		},
		methods:{
			getUser(uid) {
				return new Promise((resolve, reject) => {
					let data = this.$http.post('message/api/user/getContactor', { uid });
					resolve(data);
				});
			},
			subscribe() {
				uni.$on('message', data => {
					if (data.from == this.touser.uid) {
						this.addMessage(data.message);
						this.$nextTick(vm => {
							this.scrollToView = 'msg' + data.message.msgid;
						})
					}
				})
			},
			addMessage(msg, pre = false) {
				if (msg.msgtype == 'local_template') {
					if (typeof msg.content == 'string') msg.content = JSON.parse(msg.content)
				}
				if (pre) this.msgList = [msg].concat(this.msgList)
				else this.msgList.push(msg)
			},
			
			formatTime(time) {
				let now = Date.now() / 1000;
				if (now - time < 24 * 60 * 60) return this.$u.timeFormat(time, 'hh:MM');
				return this.$u.timeFormat(time, 'yyyy-mm-dd hh:MM')
			},
			
			async loadHistory(e) {
				if(this.loadStatus != 'loadmore'){
					return ;
				}
				this.loadStatus = 'loading';//参数作为进入请求标识，防止重复请求
				this.scrollAnimation = false;//关闭滑动动画
				let Viewid = this.msgList[0].msgid;//记住第一个信息ID
				await this.getMsgList();
				this.$nextTick(function() {
					this.scrollToView = 'msg' + Viewid;
					this.$nextTick(function() {
						this.scrollAnimation = true;
					});
				});
			},
			async getMsgList(cb){
				let data = await this.$http.post('message/api/user/getMessages?page=' + (this.page + 1), { to: this.touser.uid });
				this.page = data.has_more ? data.current_page + 1 : data.current_page;
				if (!data.has_more) this.loadStatus = 'nomore';
				else this.loadStatus = 'loadmore';
				for (let i = 0; i < data.data.length; i ++) {
					this.addMessage(data.data[i], true);
				}
				return Promise.resolve();
			},
			//处理图片尺寸，如果不处理宽高，新进入页面加载图片时候会闪
			setPicSize(content){
				// 让图片最长边等于设置的最大长度，短边等比例缩小，图片控件真实改变，区别于aspectFit方式。
				let maxW = uni.upx2px(350);//350是定义消息图片最大宽度
				let maxH = uni.upx2px(350);//350是定义消息图片最大高度
				if(content.w > maxW || content.h > maxH){
					let scale = content.w/content.h;
					content.w = scale>1?maxW:maxH*scale;
					content.h = scale>1?maxW/scale:maxH;
				}
				return content;
			},
			
			showMore(){
				this.isVoice = false;
				this.hideEmoji = true;
				if(this.hideMore){
					this.hideMore = false;
					this.openDrawer();
				}else{
					this.hideDrawer();
				}
			},
			openDrawer(){
				this.popupLayerClass = 'showLayer';
			},
			hideDrawer(){
				this.popupLayerClass = '';
				setTimeout(()=>{
					this.hideMore = true;
					this.hideEmoji = true;
				},150);
			},
			chooseImage(){
				this.getImage('album');
			},
			camera(){
				this.getImage('camera');
			},
			getImage(type){
				this.hideDrawer();
				uni.chooseImage({
					sourceType:[type],
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					success: (res)=>{
						for(let i = 0; i < res.tempFilePaths.length; i++){
							uni.getImageInfo({
								src: res.tempFilePaths[i],
								success: image => {
									this.sendMsg(res.tempFilePaths[i], 'image');
								}
							});
						}
					}
				});
			},
			// 选择表情
			chooseEmoji(){
				this.hideMore = true;
				if(this.hideEmoji){
					this.hideEmoji = false;
					this.openDrawer();
				}else{
					this.hideDrawer();
				}
			},
			//添加表情
			addEmoji(em){
				this.textMsg+=em.alt;
			},
			
			//获取焦点，如果不是选表情ing,则关闭抽屉
			textareaFocus(){
				if(this.popupLayerClass=='showLayer' && this.hideMore == false){
					this.hideDrawer();
				}
			},
			// 发送文字消息
			sendText(){
				this.hideDrawer();//隐藏抽屉
				if(!this.textMsg){
					return;
				}
				let content = this.replaceEmoji(this.textMsg);
				this.sendMsg(content, 'text');
				this.textMsg = '';//清空输入框
			},
			replaceEmoji(str){
				let replacedStr = str.replace(/\[([^(\]|\[)]*)\]/g,(item, index)=>{
					for(let i=0;i<this.emojiList.length;i++){
						let row = this.emojiList[i];
						for(let j=0;j<row.length;j++){
							let EM = row[j];
							if(EM.alt==item){
								let onlinePath = 'https://s2.ax1x.com/2019/04/12/'
								let imgstr = '<img src="'+onlinePath+this.onlineEmoji[EM.url]+'">';
								return imgstr;
							}
						}
					}
				});
				return replacedStr;
			},
			
			// 发送消息
			sendMsg(content, type = 'text'){
				this.$http.post('message/api/user/send', { form: {to: this.touser.uid, type, content} }).then(data => {
					this.msgList.push(data);
					uni.$emit('new-message', {
						from: this.touser.uid, message: data
					});
					this.$nextTick(() => {
						this.scrollToView = 'msg' + data.msgid;
					})
				});
			},
			// 预览图片
			showPic(msg){
				uni.previewImage({
					indicator:"none",
					current:this.$tools.fixurl(msg.content),
					urls: this.msgImgList
				});
			},
			handleLocalTemplateUrl(row) {
				const url = this.$msg.getUrl(row.content);
				if (typeof url == 'string') this.$jump({ type: 0, target: url });
			},
			templateJump(option) {
				const { target, type } = option;
				this.$jump({ target, type })
			}
		}
	}
</script>
<style lang="scss">
	@import "@/static/css/chat.scss"; 
</style>