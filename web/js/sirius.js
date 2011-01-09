$(function() {
	var i = document.createElement('p');
	i.style.width = '100%';
	
	i.style.height = '200px';
	
	var o = document.createElement('div');
	o.style.position = 'absolute';
	o.style.top = '0px';
	o.style.left = '0px';
	o.style.visibility =
	'hidden';
	o.style.width = '200px';
	o.style.height = '150px';
	o.style.overflow = 'hidden';
	o.appendChild(i);
	
	document.body.appendChild(o);
	var w1 = i.offsetWidth;
	var
	h1 = i.offsetHeight;
	o.style.overflow = 'scroll';
	var w2 = i.offsetWidth;
	var h2 = i.offsetHeight;
	if (w1 == w2) w2 = o.clientWidth;
	if (h1 == h2) h2 = o.clientWidth;
	
	document.body.removeChild(o);
	
	window.scrollbarWidth = w1-w2;
	window.scrollbarHeight = h1-h2;
});

(function($) {
	var Sirius = Sirius ||
	function() {
	};

	$.extend(Sirius.prototype, {
		initialized: false,
		dashboard: null,
		threads: [],
		dashboard: null,
		init: function(options) {
			this.settings = $.extend({
				containerElement: '#container',
				headerElement: '#header',
				dashboardElement: '#dashboard',
				threadHeadHeight: 31,
				profiles: {},
				tabs: [],
				activeTabId: 0,
				threadWidth: 400
			}, options || {});

			if (!this._initDashboard()
			 && !this._initSiderbarAndProfileSelector()
			 && !this._initTabs())
				return;
			this.initialized = true;
		},
				
		_onResize: function() {
			if ($('#sidebar').is(":visible")) {
				$($.sirius.settings.dashboardElement).width($($.sirius.settings.containerElement).width() - $('#sidebar').outerWidth(true));
			} else {
				$($.sirius.settings.dashboardElement).width($($.sirius.settings.containerElement).width());	
			}
			
			threadsWidth = $('#threadsScroll .thread').size() * $('.thread').outerWidth(true);
			if (threadsWidth > $($.sirius.settings.dashboardElement).width())
				$('#threadsScroll').width(threadsWidth);
			else
				$('#threadsScroll').width("100%");
				
			$($.sirius.settings.containerElement).height($(window).height() - $($.sirius.settings.headerElement).height());
			$($.sirius.settings.dashboardElement).height($($.sirius.settings.containerElement).height());
			$("#threadsContainer").height($($.sirius.settings.dashboardElement).height() - $("#dashboardTabs").height());
			$('#threadsScroll').height($("#threadsContainer").height() - $.sirius.settings.threadHeadHeight );
			if ($('#threadsScroll').width() > $("#threadsContainer").width()) {
				$('#threadsScroll').height($('#threadsScroll').height() - window.scrollbarHeight);
			} 
		},
		
		_initDashboard: function() {
			if (!this.settings.dashboardElement || this.settings.dashboardElement == '')
				return false;
			var dashboard = $($(this.settings.dashboardElement)[0]);
			this.dashboard = dashboard;
			if (dashboard.size == 0)
				return false;

			// style
			dashboard.addClass('dashboard');

			// children
			dashboard.children().remove();
			dashboard.append($('<div/>').attr('id', 'dashboardTabs'));
			dashboard.append(
			$('<div/>').attr('id', 'threadsContainer')
				.append($('<div/>').attr('id', 'threadsScroll'))
			);

			// resize
			$(window).resize({'thisObject': this},this._onResize);
			this._onResize();

			this.dashboard = dashboard;
		},
		
		_initSiderbarAndProfileSelector: function() {
			if (!$.isArray(this.settings.profiles))
				return false;
			var profiles = this.settings.profiles;
			for (var i = 0; i < profiles.length; ++i) {
				this.addProfileToSidebar(profiles[i]);
				this.addProfileToProfileSelector(profiles[i]);
			}
		},
		
		_initTabs: function() {
			if (!$.isArray(this.settings.tabs))
				return false;
			tabs = this.settings.tabs;
			for (var i = 0; i < this.settings.tabs.length; ++i) {
				tabElement = $("<div/>").attr("id", "tab" + tabs[i].id)
					.addClass("tab")
					.attr("tabId", tabs[i].id)
					.attr("tabTitle", tabs[i].title);
				$("<a href='javascript:;'/>")
					.addClass("tab-name")
					.html(tabs[i].title)
					.appendTo(tabElement);
				$("<a href='javascript:;' />")
					.addClass("tab-delete-button icon-19")
					.text("X")
					.appendTo(tabElement);
				$("#dashboardTabs").append(tabElement);
			}
			
			this.settings.activeTabId = this.settings.activeTabId || this.settings.tabs[0].id;
			
			this.activeTab($("#tab" + this.settings.activeTabId));
		},
		
		_initProfiles: function() {
			if (!$.isArray(this.settings.profiles))
				return false;
			thisObj = this;
			$(this.settings.profiles).each( function(index, item) {
				thisObj.addThread(item.type, item.id, 'home', 'Home Feed');
			});
		},
		
		setProfiles: function(data) {
			if (!$.isArray(data))
				return false;
			this.settings.profiles = data;
			
			var foldStatus = [], selectedStatus = [];
			// clear all profiles in sidebar and profileSelector
			$("#sidebar>ul li").each(function(index, item){
				foldStatus[$(item).attr('profileId')] = $('.list-toggle-button', item).hasClass('button-unfold');
			}).remove();
			$(".profileSelector a.profileAvatar").each(function(index, item){
				selectedStatus[$(item).attr('profileId')] = $(item).hasClass('selected');
			}).remove();
			
			for (var i = 0; i < data.length; ++i) {
				isFold = foldStatus[data[i].id] | false;
				isSelected = selectedStatus[data[i].id] | false;
				this.addProfileToSidebar(data[i], isFold);
				this.addProfileToProfileSelector(data[i], isSelected);
			}
		},
		
		addProfileToProfileSelector: function(profile, isSelected) {
			var profileId = profile.id;
			var profileType = profile.type;
			var screenName = profile.screen_name;
			var profileName = profile.profile_name;
			var avatar = profile.avatar_url;

			var avatarNode = $('<span class="networkAvatarWrapper"/>').append($('<img class="networkAvatar"/>').attr('src', avatar));
			var typeNode = $('<span class="icon-13" />').addClass(profileType);
			var node = $('<a href="#" class="profileAvatar" />')
				.append(avatarNode)
				.append('<span class="checkmark icon-19"></span>')
				.append(typeNode)
				.attr('profileId', profileId)
				.attr('title', screenName)
				.click(function(){
					$(this).toggleClass('selected');
				});
			if (isSelected) {
				node.addClass('selected');
			}
			
			node.appendTo('.profileSelector')
		},
		
		addProfileToSidebar: function(profile, isFold) {
			isFold = isFold | false;
			
			var sirius = this;
			var sn = socialNetworkTypes;
			var profileId = profile.id;
			var profileType = profile.type;
			var screenName = profile.screen_name;
			
			var profileEle = $("<li/>").addClass('list-profile').attr('profileId', profileId);
			$('<div/>').addClass('list-title').addClass('profile-'+profileType).text(screenName).appendTo(profileEle);
			$('<a class="list-toggle-button button-fold" href="javascript:;">Toggle</a>').appendTo(profileEle);
			
			var actionList = $("<ul/>").appendTo(profileEle);
			for (var i=0; i < sn[profileType].length; ++i) {
				var type = sn[profileType][i].type;
				var title = sn[profileType][i].defaultTitle
				var actionItem = $('<li/>').addClass('list-item')
					.addClass('list-icon-'+sn[profileType][i].type)
					.appendTo(actionList);
				$("<a/>").attr('href', 'javascript:;')
					.attr('type', type)
					.attr('title', title)
					.text(title)
					.appendTo(actionItem)
					.click(function(){
						exist = $("div[profileId="+profileId + "][threadType="+$(this).attr('type')+"]")
						if (exist.size() == 0) {
							sirius.serverAddThread(sirius.settings.activeTabId, profileId, $(this).attr('type'), $(this).attr('title'));
						} else {
							$('#threadsContainer').animate({scrollLeft: $(exist).position().left}, "slow");
							bgColor = $('.message', exist).css('backgroundColor');
							$('.message', exist).animate({backgroundColor: 'red'}, 1000).animate({backgroundColor: bgColor}, 1000);
						}
					});
			}
			
			if (isFold) {
				$('.list-toggle-button', profileEle).removeClass('button-fold');
				$('.list-toggle-button', profileEle).addClass('button-unfold');
				$('ul', profileEle).hide();
			}
			
			$("#sidebar>ul").append(profileEle);
			
			var toggleList = function(){
				$(this).unbind('click', toggleList);
				var toggleButton = this;
				if ($(this).hasClass('button-fold')) {
					$(this).siblings('ul').slideUp('slow', function() {
						$(toggleButton).removeClass('button-fold');
						$(toggleButton).addClass('button-unfold');
						$(toggleButton).click(toggleList);
					})
				} else {
					$(this).siblings('ul').slideDown('slow', function() {
						$(toggleButton).removeClass('button-unfold');
						$(toggleButton).addClass('button-fold');
						$(toggleButton).click(toggleList);
					})
					
				}
			};
			$(".list-toggle-button", profileEle).click(toggleList);
		},

		activeTab: function(tab) {
			if (!tab || this.activeTab && this.activeTab == $(tab)[0]) return;
			
			// set tab style
			$(".tab-name").removeClass('active-tab');
			$(tab).addClass('active-tab');
			
			// remove threads
			this.removeAllThreads();
			
			// load threads
			$.ajax({
				type: 'GET',
				url: '/tab/active',
				data: {tabId: $(tab).attr('tabId')},
				dataType: 'json',
				context: this,
				success: function(data) {
					thisObject = this;
					$(data).each(function(index, item){
						thisObject.addThread(item);
					});
				},
				error: function() {
					//alert("ERROR");
				}
			});
			this.activeTab = $(tab)[0];
		},
		
		serverAddThread: function(tabId, profileId, type, title) {
			$.ajax({
				type: 'GET',
				url: '/thread/add',
				data: {
					tabId: tabId,
					profileId: profileId,
					type: type,
					title: title
				},
				dataType: 'json',
				context: this,
				success: function (data) {
					this.addThread(data);
				}
			});
		},
		
		serverRemoveThread: function(threadId) {
			$.ajax({
				type: 'GET',
				url: '/thread/delete',
				data: {
					threadId: threadId
				},
				dataType: 'json',
				context: this,
				success: function (data) {
					this.removeThread(threadId);
				}
			});
		},
		
		addThread: function(threadInfo) {
			var dashboard = this.dashboard;
			if (!dashboard)
				return false;

			thread = $("<div class='thread'><div class='thread-header' /><div class='thread-message-container'/></div>")
				.attr('profileId', threadInfo.profile_id)
				.attr('profileType', threadInfo.profile_type)
				.attr('threadType', threadInfo.type)
				.attr('threadId', threadInfo.id)
				.attr('threadParameters', threadInfo.parameters);

			threadHeader = $('.thread-header', thread)
				.append('<span class="thread-icon" />')
				.append('<div class="thread-name" />')
				.append('<div class="thread-buttons"/>');

			$('.thread-icon', threadHeader).addClass('thread-icon-' + threadInfo.profile_type);
			$('.thread-name', threadHeader).html('<span class="new-count"></span>' + threadInfo.title + "<span>(" + threadInfo.profile_name + ")</span>");
			$('.thread-buttons', threadHeader)
				.append('<a href="#" class="refresh-button icon-19" title="刷新">刷新</a>')
				.append('<span class="refreshing icon-19" title="正在刷新...">正在刷新...</span>')
				.append('<a href="#" class="close-button icon-19" title="删除此栏">删除此栏</a>');

			$('#threadsScroll',this.dashboard).append(thread);

			// bind event
			thisObject = this;
			$('.refresh-button', thread).click( function() {
				thisObject.refreshThread($(this).parents('.thread'));
			});
			
			$('.close-button', thread).click(function(){
				thisObject.serverRemoveThread(threadInfo.id);
			});
			
			// set style
			$('.thread-height', thread).height(this.settings.threadHeadHeight);
			$('.refreshing', thread).hide();
			
			// add timer to refresh
			this.refreshThread(thread);
			
			// refresh now
			this._onResize();
		},
		
		removeThread: function(threadId) {
			$('.thread[threadId=' + threadId +']').remove();
			
			// refresh now
			this._onResize();
		},
		
		refreshThread: function(thread) {
			$('.new-count', thread).text('').hide();
			$('.new-message', thread).remove();
			
			profileId = $(thread).attr('profileId');
			profileType = $(thread).attr('profileType');
			threadType = $(thread).attr('threadType');
			threadId = $(thread).attr('threadId');
			since_id = $(thread).attr('lastMessageId');

			// set UI status
			$('.refreshing', thread).show();
			$('.refresh-button', thread).hide();

			// prepare request data
			requestData = {'profile_id': profileId};
			if (since_id != undefined)
				requestData.since_id = since_id;

			var requestUrl= '/' + profileType + '/' + threadType;
			$.ajax({
				type: 'GET',
				url: requestUrl,
				data: requestData,
				dataType: 'json',
				context: {sirius:this, threadId: threadId, profileId: profileId},
				success: function(data) {
					tempContainer = $('<div/>');
					lastMessageId = false;
					profileId = this.profileId;
					sirius = this.sirius;
					$(data).each( function(i, message) {
						lastMessageId = lastMessageId || message.id;
						
						messageNode = sirius.packMessage(message);
						$('.message-author', messageNode).after('<span class="icon-13 new-message"></span>');
						if (message.retweet_origin != null) {
							$(messageNode).append( $(sirius.packMessage(message.retweet_origin)).addClass('submessage'));
						}
						$(messageNode).appendTo(tempContainer);
					});
					if (tempContainer.children().size() > 0) {
						$('.new-count', thread).text(tempContainer.children().size()).show();
						$('div[threadId=' + this.threadId + ']').attr('lastMessageId', lastMessageId);
						tempContainer.children().prependTo($('div[threadId=' + this.threadId + '] .thread-message-container'));
					}
					$('div[threadId=' + this.threadId + '] .refreshing').hide();
					$('div[threadId=' + this.threadId + '] .refresh-button').show();
				},
				error: function() {
					$('div[threadId=' + this.threadId + '] .refreshing').hide();
					$('div[threadId=' + this.threadId + '] .refresh-button').show();
					//alert("ERROR");
				}
			});
		},
		
		packMessage: function(message) {
			var avatar = $('<a href="javascript:;"></a>').addClass('message-avatar').append("<img src='" + message.user.avatar + "' />")
				.attr('title', message.user.screen_name);
			var author = $('<a href="javascript:;"></a>').addClass('message-author').text(message.user.screen_name);
			var time_source = $('<p></p>').addClass('message-time-via').html(message.created_at + (message.source != '' ? ' via ' + message.source : ''));
			$('a', time_source).attr('target', '_blank');
			
			var text = $('<p/>').addClass('message-body').html(message.text);
			
			var node = $('<div class="message"></div>').append(avatar).append(author).append(time_source).append(text);
			
			if (message.picture_thumbnail != "") {
				$('<a/>')
					.attr('href', message.picture_original)
					.append($('<img/>').attr('src', message.picture_thumbnail))
					.appendTo(node)
					.colorbox({
						maxWidth: '80%',
						maxHeight: '80%',
						photo: true
					});
			}
			return node;			
		},
		
		removeAllThreads: function() {
			$('.thread', this.settings.dashboardElement).remove();
		}
	});

	$.sirius = new Sirius;

})(jQuery);
