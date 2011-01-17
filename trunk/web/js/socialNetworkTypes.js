var socialNetworkNames = socialNetworkNames || [];
socialNetworkNames['sina'] = "新浪微博";
socialNetworkNames['qq'] = "腾讯微博";

var socialNetworkTypes = socialNetworkTypes || [];
socialNetworkTypes['sina'] = [
	{
		defaultTitle: '首页微博',
		type: 'home',
		actions: [
			{
				name: 'comment',
				title: '评论'
			},
			{
				name: 'retweet',
				title: '转发'
			}
		]
	},
	
	{
		defaultTitle: '提到我的',
		type: 'mentions'
	},
	
	{
		defaultTitle: '我发的微博',
		type: 'posted'
	},
	
	{
		defaultTitle: '我的私信',
		type: 'direct'
	}
];

socialNetworkTypes['qq'] = [
	{
		defaultTitle: '首页微博',
		type: 'home'
	},
	{
		defaultTitle: '提到我的',
		type: 'mentions'
	}
];