// 引入配置
import Config from '@/common/config'
// 初始化请求配置
uni.$u.http.setConfig(config => {
    /* defaultConfig 为默认全局配置 */
    config.baseURL = Config.baseUrl /* 根域名 */
    return config
})

uni.$u.gql.setConfig(config => {
	config.url = Config.graphqlUrl;
	return config;
})

module.exports = (vm) => {
    require('./requestInterceptors')(vm)
    require('./responseInterceptors')(vm)
}
