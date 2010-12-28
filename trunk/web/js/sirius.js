(function($) {
	var Sirius = Sirius ||
	function() {
	};

	$.extend(Sirius.prototype, {
		initialized: false,
		dashboard: null,
		threads: [],
		init: function(options) {
			this.settings = $.extend({
				containerElement: '#container',
				headerElement: '#header',
				dashboardElement: '#dashboard',
				threadHeadHeight: 31,
				profiles: {},
				tabs: [],
				activeTabId: 0,
				threadWidth: 400,	
			}, options || {});

			if (!this._initDashboard()
			 && !this._initSiderbar()
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
		},
		
		_initDashboard: function() {
			if (!this.settings.dashboardElement || this.settings.dashboardElement == '')
				return false;
			this.dashboard = dashboard = $($(this.settings.dashboardElement)[0]);
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
		
		_initSiderbar: function() {
			if (!$.isArray(this.settings.profiles))
				return false;
			var profiles = this.settings.profiles;
			for (var i = 0; i < profiles.length; ++i) {
				this.addProfileToSidebar(profiles[i]);
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
					.addClass("tab-delete-button")
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
		
		addProfileToSidebar: function(profile) {
			var sirius = this;
			var sn = socialNetworkTypes;
			var profileId = profile.id;
			var profileType = profile.type;
			var screenName = profile.screen_name;
			
			var profileEle = $("<li/>").addClass('list-profile');
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
							bgColor = $('.thread-header', exist).css('backgroundColor');
							$('.thread-header', exist).animate({backgroundColor: 'red'}, 1000).animate({backgroundColor: bgColor}, 1000);
						}
					});
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
		
		addThread: function(threadInfo) {
			if (!(dashboard = this.dashboard))
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
			$('.thread-name', threadHeader).html(threadInfo.title + "<span>(" + threadInfo.profile_name + ")</span>");
			$('.thread-buttons', threadHeader)
			.append('<a href="#" class="refresh-button" title="刷新">刷新</a>')
			.append('<span class="refreshing" title="正在刷新...">正在刷新...</span>');

			$('#threadsScroll',this.dashboard).append(thread);
			this.threads.push(thread[0]);

			// bind event
			thisObject = this;
			$('.refresh-button', thread).click( function() {
				thisObject.refreshThread($(this).parents('.thread'));
			});
			// set style
			$('.thread-height', thread).height(this.settings.threadHeadHeight);
			$('.refreshing', thread).hide();
			
			// add timer to refresh
			this.refreshThread(thread);
			
			// refresh now
			this._onResize();
		},
		refreshThread: function(thread) {
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
				context: {threadId: threadId},
				success: function(data) {
					tempContainer = $('<div/>');
					lastMessageId = false;
					profileId = this.profileId;
					$(data).each( function(i, message) {
						lastMessageId = lastMessageId || message.id;
						var avatar = $('<a href="javascript:;"></a>').addClass('message-avatar').append("<img src='" + message.user.avatar + "' />")
							.attr('title', message.user.screen_name);
						var author = $('<a href="javascript:;"></a>').addClass('message-author').text(message.user.screen_name);
						var time_source = $('<p></p>').addClass('message-time-via').html(message.created_at + ' via ' + message.source);
						var text = $('<p/>').addClass('message-body').html(message.text);
						$('<div class="message"></div>')
						.append(avatar)
						.append(author)
						.append(time_source)
						.append(text)
						.appendTo(tempContainer);
					});
					if (tempContainer.children().size() > 0) {
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
		removeAllThreads: function() {
			$(this.threads).remove();
			this.threads = [];
		},
	});

	$.sirius = new Sirius;

})(jQuery);
