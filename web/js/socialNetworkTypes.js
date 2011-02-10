var socialNetworkNames = socialNetworkNames || [];
socialNetworkNames['sina'] = "新浪微博";
socialNetworkNames['qq'] = "腾讯微博";
socialNetworkNames['fanfou'] = "饭否";

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
			},
			{
				name: 'favorite',
				title: '收藏',
				unless: 'favorited'
			},
			{
				name: 'unfavorite',
				title: '取消收藏',
				ifCondition: 'favorited'
			}
		]
	},
	
	{
		defaultTitle: '提到我的',
		type: 'mentions',
		actions: [
			{
				name: 'comment',
				title: '评论'
			},
			{
				name: 'retweet',
				title: '转发'
			},
			{
				name: 'favorite',
				title: '收藏',
				unless: 'favorited'
			},
			{
				name: 'unfavorite',
				title: '取消收藏',
				ifCondition: 'favorited'
			}
		]
	},	
	{
		defaultTitle: '我发的微博',
		type: 'posted',
		actions: [
			{
				name: 'comment',
				title: '评论'
			},
			{
				name: 'retweet',
				title: '转发'
			},
			{
				name: 'favorite',
				title: '收藏',
				unless: 'favorited'
			},
			{
				name: 'unfavorite',
				title: '取消收藏',
				ifCondition: 'favorited'
			},
			{
				name: 'delete',
				title: '删除',
				submessage: false
			}
		]
	},
	
	{
		defaultTitle: '我的私信',
		type: 'direct'
	},
	{
		defaultTitle: '我的收藏',
		type: 'favorite',
		actions: [
			{
				name: 'comment',
				title: '评论'
			},
			{
				name: 'retweet',
				title: '转发'
			},
			{
				name: 'unfavorite',
				title: '取消收藏',
				submessage: false
			}
		]
	}
];

socialNetworkTypes['qq'] = [
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
			},
			{
				name: 'favorite',
				title: '收藏',
				unless: 'favorited'
			},
			{
				name: 'unfavorite',
				title: '取消收藏',
				ifCondition: 'favorited'
			}
		]
	},
	{
		defaultTitle: '提到我的',
		type: 'mentions',
		actions: [
			{
				name: 'comment',
				title: '评论'
			},
			{
				name: 'retweet',
				title: '转发'
			},
			{
				name: 'favorite',
				title: '收藏',
				unless: 'favorited'
			},
			{
				name: 'unfavorite',
				title: '取消收藏',
				ifCondition: 'favorited'
			}
		]
	},
	{
		defaultTitle: '我发的微博',
		type: 'posted',
		actions: [
			{
				name: 'comment',
				title: '评论'
			},
			{
				name: 'retweet',
				title: '转发'
			},
			{
				name: 'delete',
				title: '删除',
				submessage: false
			}
		]
	},
	{
		defaultTitle: '我的私信',
		type: 'direct'
	},
	{
		defaultTitle: '我的收藏',
		type: 'favorite',
		actions: [
			{
				name: 'comment',
				title: '评论'
			},
			{
				name: 'retweet',
				title: '转发'
			},
			{
				name: 'unfavorite',
				title: '取消收藏',
				submessage: false
			}
		]
	}
];

socialNetworkTypes['fanfou'] = [
  	{
  		defaultTitle: '首页微博',
  		type: 'home'
  	},
  	{
  		defaultTitle: '提到我的',
  		type: 'mentions'
  	},
	{
		defaultTitle: '我发的微博',
		type: 'posted',
	},
	{
		defaultTitle: '我的私信',
		type: 'direct'
	}
];