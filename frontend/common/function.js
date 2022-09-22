function page()
{
	var pages = getCurrentPages();
	return pages[pages.length - 1];
}

function route()
{
	return page().route;
}

function scene()
{
	const appInfo  = uni.$store.state.appInfo;
	return appInfo.config.shop && appInfo.config.shop.third.scene;
}

function getAppInfo(key)
{
	let list = key.split('.');
	let appInfo  = uni.$store.state.appInfo;
	for (var i = 0; i < list.length; i ++) {
		var k = list[i];
		if (appInfo[k] == undefined) return null;
		appInfo = appInfo[k];
	}
	return appInfo;
}

export default {
	page, route, getAppInfo, scene
}