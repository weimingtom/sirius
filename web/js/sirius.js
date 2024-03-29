$(function() {
	var i = document.createElement('p');
	i.style.width = '100%';
	
	i.style.height = '200px';
	
	var o = document.createElement('div');
	o.style.position = 'absolute';
	o.style.top = '0px';
	o.style.left = '0px';
	o.style.visibility = 'hidden';
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
	var Sirius = Sirius || function() {};

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
				threadWidth: 400,
				refreshFrequency: 0
			}, options || {});

			if (!this._initDashboard()
			 && !this._initSiderbarAndProfileSelector()
			 && !this._initTabs())
				return;
			this.resetRefreshPlan();
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
				
			$($.sirius.settings.containerElement).height($(window).height() - $($.sirius.settings.headerElement).height() - $('#footer').outerHeight());
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
			
			// sortable
			$("#sidebar ul").sortable({
				axis: 'y',
				opacity: 0.6,
				handle: '.list-title'
			});
			
			for (var i = 0; i < profiles.length; ++i) {
				this.addProfileToSidebar(profiles[i]);
				this.addProfileToProfileSelector(profiles[i]);
			}
		},
		
		_initTabs: function() {
			var sirius = this;
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
					.click(function(event) {
						sirius.activeTab($(event.target).parents(".tab"));
					})
					.appendTo(tabElement);
				$("<input type='text' class='tab-rename' />")
					.val(tabs[i].title)
					.appendTo(tabElement);
				$("<a href='javascript:;' />")
					.addClass("tab-delete-button icon-19")
					.text("X")
					.click(function(event){
						sirius.deleteTab($(event.target).parents(".tab"));
					})
					.appendTo(tabElement);
				$("#dashboardTabs").append(tabElement);
			}
			
			tabAddElement = $("<div/>").addClass("tab _add-tab");
			$("<a href='javascript:;'/>")
				.addClass("tab-name tab-add")
				.append("<span class='icon-13 icon-add'></span>")
				.click(function(event){sirius.addTab();})
				.appendTo(tabAddElement);
			
			$("#dashboardTabs").append(tabAddElement);
			
			$("#dashboardTabs").sortable({
				items: "div:not(._add-tab)",
				axis: 'x',
				opacity: 0.6,
				update: function(event, ui) {
					sirius.statusMessage('正在保存数据...', 'info');
					var tabs = $('#dashboardTabs .tab:not(._add-tab)');
					var tabIds = [];
					$(tabs).each(function(index, item) {
						tabIds.push($(item).attr('tabId'))
					});
					$.get('/tab/tabOrder',
						{tab_ids: tabIds},
						function(data){
							thisObject.statusMessage('数据保存成功', 'success');
						});
				}
			});
			
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
		
		refreshProfiles: function() {
			$.ajax({
				type: 'GET',
				url: '/profile/list',
				dataType: 'json',
				context: this,
				success: function(data) {
					this.setProfiles(data);
				},
				error: function() {
					this.statusMessage('帐号刷新失败', 'error');
				}
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
			var sirius = this;
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
				.attr('profileType', profileType)
				.attr('title', screenName)
				.click(function(){
					if ($(this).hasClass("selected")) {
						sirius.deselectProfile(this);
					} else {
						sirius.selectProfile(this);
					}
				});
			if (isSelected) {
				node.addClass('selected');
			}
			
			node.appendTo('.profileSelector');
		},
		
		addProfileToSidebar: function(profile, isFold) {
			isFold = isFold | false;
			
			var sirius = this;
			var sn = socialNetworkTypes;
			var profileId = profile.id;
			var profileType = profile.type;
			var screenName = profile.screen_name;
			
			var profileEle = $("<li/>").addClass('list-profile').attr('profileId', profileId);
			title = $('<div/>').addClass('list-title').appendTo(profileEle);
			$('<a class="list-toggle-button" href="#"></a>').text(screenName).append('<span class="button-fold">Toggle</span>').addClass('profile-'+profileType).appendTo(title);
			
			var actionList = $("<ul/>").appendTo(profileEle);
			for (var i=0; i < sn[profileType].length; ++i) {
				var type = sn[profileType][i].type;
				var title = sn[profileType][i].defaultTitle
				var actionItem = $('<li/>').appendTo(actionList);
				$("<a/>").attr('href', 'javascript:;').addClass('list-item')
					.addClass('list-icon-'+sn[profileType][i].type)
					.attr('type', type)
					.attr('title', title)
					.text(title)
					.appendTo(actionItem)
					.click(function(){
						exist = $("div.thread[profileId="+profileId + "][threadType="+$(this).attr('type')+"]")
						if (exist.size() == 0) {
							sirius.serverAddThread($(".active-tab").attr("tabId"), profileId, $(this).attr('type'), $(this).attr('title'));
						} else {
							$('#threadsContainer').animate({scrollLeft: $(exist).position().left}, "slow");
							bgColor = $('.message', exist).css('backgroundColor');
							$('.message', exist).animate({backgroundColor: 'red'}, 1000).animate({backgroundColor: bgColor}, 1000);
						}
					});
				if ($("div.thread[profileId="+profileId + "][threadType="+type+"]").size() > 0) {
					$('a', actionItem).addClass('highlight');
				}
			}
			
			if (isFold) {
				$('.list-toggle-button span', profileEle).removeClass('button-fold');
				$('.list-toggle-button span', profileEle).addClass('button-unfold');
				$('ul', profileEle).hide();
			}
			
			$("#sidebar>ul").append(profileEle);
			
			var toggleList = function(){
				$(this).unbind('click', toggleList);
				var toggleButton = this;
				if ($('span', this).hasClass('button-fold')) {
					$(this).parent().siblings('ul').slideUp('slow', function() {
						$('span', toggleButton).removeClass('button-fold');
						$('span', toggleButton).addClass('button-unfold');
						$(toggleButton).click(toggleList);
					})
				} else {
					$(this).parent().siblings('ul').slideDown('slow', function() {
						$('span', toggleButton).removeClass('button-unfold');
						$('span', toggleButton).addClass('button-fold');
						$(toggleButton).click(toggleList);
					})
					
				}
			};
			$(".list-toggle-button", profileEle).click(toggleList);
		},
		
		addTab: function(title) {
			this.statusMessage("正在添加新面板, 请耐心等待", "info");
			params = title ? {title: title} : {};
			$.ajax({
				type: 'GET',
				url: '/tab/add',
				data: params,
				dataType: 'json',
				context: this,
				success: function(data) {
					var sirius = this;
					if (data.error) {
						this.statusMessage("新面板添加失败, 请稍候重试", "error");
						return;
					}
					tabId = data.tabId;
					tabElement = $("<div/>").attr("id", "tab" + data.tabId)
						.addClass("tab")
						.attr("tabId", data.tabId)
						.attr("tabTitle", data.title);
					$("<a href='javascript:;'/>")
						.addClass("tab-name")
						.html(data.title)
						.click(function(event) {
							sirius.activeTab($(event.target).parents(".tab"));
						}).appendTo(tabElement);
					$("<input type='text' class='tab-rename' />")
						.val(data.title)
						.appendTo(tabElement);
					$("<a href='javascript:;' />")
						.addClass("tab-delete-button icon-19")
						.text("X")
						.click(function(event){
							sirius.deleteTab($(event.target).parents(".tab"));
						}).appendTo(tabElement);
					$("#dashboardTabs ._add-tab").before(tabElement);
					this.activeTab(tabElement);
					this.statusMessage("面板添加成功");
				},
				error: function(data) {
					this.statusMessage("新面板添加失败, 请稍候重试", "error");
					return;
				}
			});
		},
		
		deleteTab: function(tab) {
			if (!confirm("您确认要删除该面板吗?")) {
				return;
			}
			$.ajax({
				type: 'GET',
				url: '/tab/delete',
				data: {tabId: $(tab).attr('tabId')},
				dataType: 'json',
				context: {sirius: this, tab: tab},
				success: function(data) {
					if (data.error) {
						this.statusMessage("面板删除失败, 请稍候重试", "error");
						return;
					}
					var isActived = $(this.tab).hasClass('active-tab');
					$(this.tab).remove();
					if (isActived) {
						this.sirius.activeTab($('.tab:first'));
					}
				}
			});
		},
		
		renameTab: function(tab) {
			$.ajax({
				type: 'GET',
				url: '/tab/rename',
				data: {tabId: $(tab).attr('tabId'), title: $('.tab-rename', tab).val()},
				dataType: 'json',
				context: {sirius: this, tab: tab},
				success: function(data) {
					if (data.error) {
						this.statusMessage("重命名失败, 请稍候重试", "error");
						return;
					}
					$('.tab-name', tab).text(data.title);
				}
			});
		},

		activeTab: function(tab) {
			if ($(tab).hasClass('active-tab')) {
				return;
			}
			
			var sirius = this;
			
			// set tab style
			$(".tab.active-tab")
				.removeClass('active-tab')
				.children('.tab-name')
				.unbind('dblclick')
				.attr('title', '');
			$(tab).addClass('active-tab');
			$('.tab-name', tab)
				.attr('title', '双击重命名')
				.dblclick(function(event){
					$('.tab-rename', tab)
						.val($('.tab-name',tab).text())
						.keydown(function(event){
							if(event.keyCode == 13) {
								$(this).blur();
								return;
							}
							$('.tab-name',tab).text($('.tab-rename', tab).val());
							$('.tab-rename', tab).width($('.tab-name',tab).innerWidth() - $('.tab-name',tab).css('padding-right'));
						})
						.blur(function(){
							$('.tab-name',tab).text($('.tab-rename', tab).val());
							$('.tab-rename', tab).hide();
							sirius.renameTab(tab);
						})
						.show()
						.focus();
				});
			
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
					$("#threadsScroll").sortable({
						axis: 'x',
						opacity: 0.6,
						handle: '.thread-header',
						update: function(event, ui) {
							thisObject.statusMessage('正在保存数据...', 'info');
							var tabId = $('.active-tab').attr('tabId');
							var threads = $('#threadsContainer .thread');
							var threadIds = [];
							$(threads).each(function(index, item) {
								threadIds.push($(item).attr('threadId'))
							});
							$.get('/tab/setOrder',
								{tab_id:tabId, thread_ids: threadIds},
								function(data){
									thisObject.statusMessage('数据保存成功', 'success');
								});
						}
					});
				},
				error: function() {
					//alert("ERROR");
				}
			});
			
			// reset refreshAll
			this.resetRefreshPlan();
			var currentTime = new Date()
			var hours = currentTime.getHours()
			var minutes = currentTime.getMinutes()
			if (minutes < 10)
				minutes = "0" + minutes
			$('._refresh-all').attr('title', '全部刷新 | 上次刷新' + hours + ":" + minutes);
		},
		
		setThreadWidth: function(width) {
			this.settings.threadWidth = width;
			$('.thread').css('width', width);
			this._onResize();
		},
		
		saveThreadWidth: function(width) {
			$.ajax({
				type: 'GET',
				url: '/dashboard/savePreference',
				data: { name: 'threadWidth', value: width　},
				dataType: 'json',
				context: this,
				success: function (data) {
					if (data && data.error) {
						this.statusMessage(data.error, "error");
					} else {
						this.statusMessage("数据保存成功", "success");
					}
				}
			});
		},
		
		resetRefreshPlan: function() {
			$("body").stopTime("refreshAll");
			if (this.settings.refreshFrequency != 0) {
				var sirius = this;
				$("body").oneTime(1000 * 60 * this.settings.refreshFrequency, "refreshAll", function() {
					sirius.refreshAllThread();
				});
			}
		},
		
		refreshAllThread: function() {
			var sirius = this;
			$('.thread').each(function(index, value){
				sirius.refreshThread(value);
			});
			this.resetRefreshPlan();

			var currentTime = new Date()
			var hours = currentTime.getHours()
			var minutes = currentTime.getMinutes()
			if (minutes < 10)
				minutes = "0" + minutes
			$('._refresh-all').attr('title', '全部刷新 | 上次刷新' + hours + ":" + minutes);
		},
		
		changeRefreshFrequency: function(frequency) {
			if (this.settings.refreshFrequency == frequency) return;
			this.settings.refreshFrequency = frequency;
			this.resetRefreshPlan();
			$.ajax({
				type: 'GET',
				url: '/dashboard/savePreference',
				data: { name: 'refreshFrequency', value: frequency　},
				dataType: 'json',
				context: this,
				success: function (data) {
					if (data && data.error) {
						this.statusMessage(data.error, "error");
					} else {
						this.statusMessage("数据保存成功", "success");
					}
				}
			});
		},
		
		serverAddThread: function(tabId, profileId, type, title) {
			this.statusMessage("正在添加...", "info");
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
					$('#threadsContainer').animate({scrollLeft: $('.thread[threadId=' + data.id + ']').position().left}, "slow");
					this.statusMessage("添加成功", "success");
				}
			});
		},
		
		serverRemoveThread: function(threadId) {
			this.statusMessage("正在删除...", "info");
			this.removeThread(threadId);
			$.ajax({
				type: 'GET',
				url: '/thread/delete',
				data: {
					threadId: threadId
				},
				dataType: 'json',
				context: this,
				success: function (data) {
					this.statusMessage("删除成功", "success");
				}
			});
		},
		
		addThread: function(threadInfo) {
			var dashboard = this.dashboard;
			if (!dashboard)
				return false;

			thread = $("<div class='thread'><div class='thread-header' /><div class='thread-scroll'><div class='thread-message-container'/></div></div>")
				.attr('profileId', threadInfo.profile_id)
				.attr('profileType', threadInfo.profile_type)
				.attr('threadType', threadInfo.type)
				.attr('threadId', threadInfo.id)
				.attr('threadParameters', threadInfo.parameters)
				.css('width', this.settings.threadWidth);

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

			// add actions
			var profileTypeDefine = socialNetworkTypes[threadInfo.profile_type];
			for (var i = 0; i < profileTypeDefine.length; ++i) {
				if (profileTypeDefine[i].type == threadInfo.type) {
					if (profileTypeDefine[i].actions != undefined) {
						var actions = profileTypeDefine[i].actions;
						var threadActions = $('<span class="_actions messageActions" />');
						for (var i = 0; i < actions.length; ++i) {
							$('<a />').addClass('action icon-19').addClass('action-' + actions[i].name)
								.attr('title', actions[i].title)
								.html(actions[i].title)
								.appendTo(threadActions);
						}
						$(thread).append(threadActions);
					}
					break;
				}
			}			
			
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
			$('.list-profile[profileId=' + threadInfo.profile_id + '] li a[type=' + threadInfo.type + ']').addClass('highlight');
			
			// add timer to refresh
			this.refreshThread(thread);
			
			// refresh now
			this._onResize();
		},
		
		removeThread: function(threadId) {
			var thread = $('.thread[threadId=' + threadId +']');
			if (thread.length > 0) {
				var profileId = $(thread).attr('profileId');
				var threadType = $(thread).attr('threadType');
				$('.list-profile[profileId=' + profileId + '] li a[type=' + threadType + ']').removeClass('highlight');
				$('.thread[threadId=' + threadId +']').remove();
			}
			
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
				context: {sirius:this, threadId: threadId, profileId: profileId, profileType: profileType, threadType: threadType},
				success: function(data) {
					tempContainer = $('<div/>');
					lastMessageId = false;
					profileId = this.profileId;
					profileType = this.profileType;
					threadType = this.threadType;
					sirius = this.sirius;
					$(data).each( function(i, message) {
						lastMessageId = lastMessageId || message.id;
						
						messageNode = sirius.packMessage(message, profileId, profileType, threadType);
						$('.message-author', messageNode).after('<span class="icon-13 new-message"></span>');
						if (message.retweet_origin != null) {
							$(messageNode).append( $(sirius.packMessage(message.retweet_origin, profileId, profileType, threadType, true)).addClass('submessage'));
						}
						
						$(messageNode).hover(
							function() {$('.message-actions',this).show(); $('.new-message',this).hide();},
							function() {$('.message-actions',this).hide(); $('.new-message',this).show();}
						);
						$(messageNode).appendTo(tempContainer);
					});
					if (tempContainer.children().size() > 0) {
						$('.new-count', thread).text(tempContainer.children().size()).show();
						$('div[threadId=' + this.threadId + ']').attr('lastMessageId', lastMessageId);
						tempContainer.children().prependTo($('div[threadId=' + this.threadId + '] .thread-message-container'));
					}
					$('div[threadId=' + this.threadId + '] .refreshing').hide();
					$('div[threadId=' + this.threadId + '] .refresh-button').show();
					if (data.length >= 19 && $('div[threadId=' + this.threadId + '] .message-more').size() == 0) {
						$('<div class="message-more"><a href="#">加载更多</a></div>')
							.appendTo($('div[threadId=' + this.threadId + '] .thread-scroll'))
							.children('.message-more a')
							.click(function(event) {
								sirius.threadLoadMore($(event.target).parents('.thread'));
							});
						
						$('div[threadId=' + this.threadId + '] .thread-scroll').scroll(function(event) {
							if (event.target.scrollHeight - $(event.target).height() - $(event.target).scrollTop() < 10) {
								sirius.threadLoadMore($(event.target).parents('.thread'));
							}
						});
					}
				},
				error: function() {
					$('div[threadId=' + this.threadId + '] .refreshing').hide();
					$('div[threadId=' + this.threadId + '] .refresh-button').show();
					this.sirius.statusMessage("加载失败，请稍候重试", 'error');
				}
			});
		},
		
		threadLoadMore: function(thread){
			profileId = $(thread).attr('profileId');
			profileType = $(thread).attr('profileType');
			threadType = $(thread).attr('threadType');
			last_id = $('.thread-message-container>.message:last', thread).attr('messageId');
			other_params = $(thread).attr('otherParams');

			$('.message-more a', thread).unbind('click').html('正在加载更多...');
			$('.thread-scroll', thread).unbind('scroll');
			
			var requestUrl= '/' + profileType + '/' + threadType;
			var requestData =  {profile_id: profileId, before_id: last_id};
			$.extend(requestData, $.parseJSON(other_params));
			$.ajax({
				type: 'GET',
				url: '/' + profileType + '/' + threadType,
				data: requestData,
				dataType: 'json',
				context: {sirius:this, thread: thread, profileId: profileId, profileType: profileType},
				success: function(data) {
					tempContainer = $('<div/>');
					lastMessageId = false;
					profileId = this.profileId;
					profileType = this.profileType;
					thread = this.thread;
					sirius = this.sirius;
					$(data).each( function(i, message) {
						messageNode = sirius.packMessage(message, profileId, profileType, threadType);
						if (message.retweet_origin != null) {
							$(messageNode).append( $(sirius.packMessage(message.retweet_origin, profileId, profileType, threadType, true)).addClass('submessage'));
						}
						$(messageNode).hover(
							function() {$('.message-actions',this).show(); $('.new-message',this).hide();},
							function() {$('.message-actions',this).hide(); $('.new-message',this).show();}
						);

						$(messageNode).appendTo(tempContainer);
					});
					if (tempContainer.children().size() > 0) {
						tempContainer.children().appendTo($('.thread-message-container', this.thread));
					}
					if (data.length < 19) {
						$('.message-more', this.thread).remove();
					}
					$('.message-more a', this.thread).unbind('click').html('加载更多')
						.click(function(event) {
							sirius.threadLoadMore(thread);
						});
					$('.thread-scroll', thread).scroll(function(event) {
						if (event.target.scrollHeight - $(event.target).height() - $(event.target).scrollTop() < 10) {
							sirius.threadLoadMore(thread);
						}
					});
				},
				error: function(data) {
					$('.message-more a', this.thread).unbind('click').html('加载失败,请重试')
						.click(function(event) {
							sirius.threadLoadMore(thread);
						});
				}
			});

		},
		
		packMessage: function(message, profileId, profileType, threadType, isSubMessage) {
			if (isSubMessage == undefined) isSubMessage = false;
			var avatar = $('<a href="javascript:;"></a>').addClass('message-avatar').append("<img src='" + message.user.avatar + "' />")
				.attr('title', message.user.name);
			var author = $('<a href="javascript:;"></a>').addClass('message-author').text(message.user.screen_name).attr('title', message.user.name);
			var time_source = $('<p></p>').addClass('message-time-via').html(message.created_at + (message.source != '' ? ' via ' + message.source : ''));
			$('a', time_source).attr('target', '_blank');
			
			var text = $('<p/>').addClass('message-body').html(message.text);
			
			var node = $('<div class="message"></div>').attr('messageId', message.id).append(avatar).append(author).append(time_source).append(text);
			
			if (message.picture_thumbnail != "") {
				if (message.picture_medium != "") {
					$('<a/>')
						.addClass('_message_picture_thumbnail')
						.attr('href', message.picture_medium)
						.append($('<img/>').attr('src', message.picture_thumbnail))
						.appendTo(node)
						.colorbox({
							maxWidth: '80%',
							maxHeight: '80%',
							photo: true,
							title: function(){
							    return '<a class="show-origin-pic" href="'+message.picture_original+'" target="_blank">查看大图</a>';
							}
						});
				} else {
					$('<img/>').attr('src', message.picture_thumbnail).appendTo(node);
				}
			}
			
			$(node).append("<div style='clear:both;' />");
			if (message.retweetCount > 0) {
				$(node).append("<a href='#' class='message-count-status _retweet-count'><span class='icon-19 action-retweet'></span><span>" + message.retweetCount + '</span> 条转发</a>');
				$('._retweet-count', node).click(function(){
					sirius.showMessage(profileId, profileType,message.id, 'retweet');
				}); 
			}			
			if (message.commentCount > 0) {
				$(node).append("<a href='#' class='message-count-status _comment-count'><span class='icon-19 action-comment'></span><span>" + message.commentCount + '</span> 条评论</a>');
				$('._comment-count', node).click(function(){
					sirius.showMessage(profileId, profileType, message.id, 'comment');
				}); 
			}
			
			//$(node).append("<div class='message-actions'><a href='#' title='转发' class='retweet'><span class='icon-19 action-retweet'>转发</span></a><a href='#' title='评论' class='comment'><span class='icon-19 action-comment'>评论</span></a></div>");
			$("<div class='message-actions'></div>").append(this.packReactions(profileId, profileType, threadType, message, isSubMessage)).appendTo(node);
			
			var sirius = this;
			$.merge(avatar, author).click(function() {
				sirius.showUser(profileId, profileType, message.user.name);
			});
			$('._user_link', text).click(function(){
				var user = $(this).attr('user');
				sirius.showUser(profileId, profileType, user);
			});
			$('._topic_link', text).click(function(){
				var topic = $(this).attr('topic');
				sirius.showTopic(profileId, profileType, topic);
			});
			//$('.retweet', node).click(function(event){sirius.setMessageBoxStatus($(this).parents('.message:first'), 'retweet', profileId); event.stopPropagation();});
			//$('.comment', node).click(function(event){sirius.setMessageBoxStatus($(this).parents('.message:first'), 'comment', profileId); event.stopPropagation();});
			return node;			
		},
		
		packReactions: function(profileId, profileType, threadType, message, isSubMessage) {
			var sirius = this;
			var type = socialNetworkTypes[profileType];
			for (i = 0; i < type.length; ++i) {
				if (type[i].type == threadType) {
					var reactions = $("<div />");
					$(type[i].actions).each(function(index, action){
						if (action.submessage != undefined && action.submessage == false && isSubMessage == true) {
							return;
						}
						if (action.ifCondition && eval("message." + action.ifCondition) === false) {
							return;
						}
						if (action.unless && eval("message." + action.unless)) {
							return;
						}
						
						var reaction = $("<a href='#'></a>").attr('title', action.title).addClass(action.name);
						$("<span/>").addClass("icon-19").addClass("action-" + action.name).appendTo(reaction);
						switch (action.name) {
							case 'retweet':
							case 'comment':
								reaction.click(function(event){sirius.setMessageBoxStatus($(this).parents('.message:first'), action.name, profileId); event.stopPropagation();});
								break;
							case 'delete':
								reaction.click(function(event) {
									$.ajax({
										type: 'GET',
										url: '/' + profileType + '/deleteMessage',
										data: {profile_id: profileId, id: message.id},
										context: this,
										success: function(data) {
											if (data.error != undefined) {
												sirius.statusMessage("微博删除失败", "error");
											} else {
												sirius.statusMessage("微博删除成功", "success");
												$('.message[messageId=' + message.id + ']').remove();
											}
										}
									});
								});
								break;
							case 'favorite':
							case 'unfavorite':
								reaction.attr('actionName', action.name);
								reaction.click(function(event){
									actionName = $(reaction).attr('actionName');
									$.ajax({
										type: 'GET',
										url: '/' + profileType + '/favoriteMessage',
										data: {profile_id: profileId, id: message.id, _do: actionName},
										context: {message: message},
										success: function(data) {
											if (data.error != undefined) {
												sirius.statusMessage("收藏/取消收藏失败", "error");
											} else {
												sirius.statusMessage("收藏/取消收藏成功", "success");
												$('.message[messageId=' + this.message.id + '] > .message-actions')
													.children('.favorite, .unfavorite')
													.toggleClass('unfavorite favorite')
													.children('span')
													.toggleClass('action-unfavorite action-favorite')
													.end()
													.attr('title', function(){
														return (this.title == '收藏') ? '取消收藏' :  '收藏';
													}).attr('actionName', function(){
														return ($(this).attr('actionName') == 'favorite') ? 'unfavorite' :  'favorite';
													});
												
											}
										}
									});
								});
								break;
						}
						reactions.append(reaction);
					});
					return $(reactions).children();
				}
			}
			return "";
		},
		
		showMessage: function(profileId, profileType, messageId, defaultTab) {
			var sirius = this;
			var selectedTab = 0;
			if (defaultTab == 'comment') {
				selectedTab = 1;
			}			
			$('#popup-dialog').dialog('destroy').html("").dialog({
				position: ['center', 100],
				resizable: false,
				minWidth: 330,
				maxWidth: 500,
				maxHeight: 600,
				title: "微博详情",
				open: function(event, ui) {
					$.ajax({
						type: 'GET',
						url: '/' + profileType + '/messageInfo',
						data: {profile_id: profileId, id: messageId, tab: defaultTab},
						context: {profileId: profileId, profileType: profileType},
						success: function(data){
							$(data).appendTo('#popup-dialog')
							       .children('a._message_picture_thumbnail')
							       .colorbox({
							       		maxWidth: '80%',
							       		maxHeight: '80%',
							       		photo: true,
										title: function(){
										    var url = $(this).attr('href');
										    return '<a class="show-origin-pic" href="'+url+'" target="_blank">查看大图</a>';
										}
							       	})
							       .end()
							       .filter('._message-info-tabs')
							       .tabs({
							    cache: true,
							    selected: selectedTab,
								show: function (event, ui) {
									if ($(ui.tab).attr('expectedWidth') != undefined) {
										$('#popup-dialog').dialog('option', {width: $(ui.tab).attr('expectedWidth')});
									}
								},
								ajaxOptions: {
									context: {profileId: this.profileId, profileType: this.profileType},
									success: function( result, status) {
										var profileId = this.context.profileId;
										var profileType = this.context.profileType;
										$('._thread-tab ._message_picture_thumbnail').colorbox({
											maxWidth: '80%',
											maxHeight: '80%',
											photo: true,
											title: function(){
											    var url = $(this).attr('href');
											    return '<a class="show-origin-pic" href="'+url+'" target="_blank">查看大图</a>';
											}
										});
										
										$('._thread-tab .message-avatar,._thread-tab  .message-author').click(function() {
											sirius.showUser(profileId, profileType, $(this).attr('title'));
										});
															
										$('._thread-tab ._user_link').click(function(){
											var user = $(this).attr('user');
											sirius.showUser(profileId, profileType, user);
										});
										$('._thread-tab ._topic_link').click(function(){
											var topic = $(this).attr('topic');
											sirius.showTopic(profileId, profileType, topic);
										});
										$('._thread-tab ._comment-count').click(function(){
											sirius.showMessage(profileId, profileType, $(this).parent('.message').attr('messageId'), 'comment');
										}); 
										$('._thread-tab ._retweet-count').click(function(){
											sirius.showMessage(profileId, profileType, $(this).parent('.message').attr('messageId'), 'retweet');
										});
										$('._thread-tab .message-more a')
											.click(function(event) {
												sirius.threadLoadMore($(event.target).parents('.popup-thread'));
											});
									},
									error: function( xhr, status, index, anchor ) {
										$( anchor.hash ).html("<div class='dialog-error'>加载失败... 请稍候重试！</div>");
									}
								}
							});
						}
					})
				}
			});
		},
		
		showTopic: function(profileId, profileType, topic) {
			var sirius = this;
			$('#popup-dialog').dialog('destroy').html("").dialog({
				position: ['center', 100],
				resizable: false,
				minWidth: 330,
				maxWidth: 500,
				maxHeight: 600,
				title: "正在加载...",
				open: function(event, ui) {
					$.ajax({
						type: 'GET',
						url: '/' + profileType + '/topicInfo',
						data: {profile_id: profileId, topic: topic},
						context: {profileId: profileId, profileType: profileType},
						success: function(data){
							var profileId = this.profileId;
							var profileType = this.profileType;
							$(data).appendTo('#popup-dialog').tabs({
								show: function (event, ui) {
									if ($(ui.tab).attr('expectedWidth') != undefined) {
										$('#popup-dialog').dialog('option', {width: $(ui.tab).attr('expectedWidth')});
									}
								}
							});
								
							$('._thread-tab ._message_picture_thumbnail').colorbox({
								maxWidth: '80%',
								maxHeight: '80%',
								photo: true,
								title: function(){
								    var url = $(this).attr('href');
								    return '<a class="show-origin-pic" href="'+url+'" target="_blank">查看大图</a>';
								}
							});
							
							$('._thread-tab .message-avatar,._thread-tab  .message-author').click(function() {
								sirius.showUser(profileId, profileType, $(this).attr('title'));
							});
												
							$('._thread-tab ._user_link').click(function(){
								var user = $(this).attr('user');
								sirius.showUser(profileId, profileType, user);
							});
							$('._thread-tab ._topic_link').click(function(){
								var topic = $(this).attr('topic');
								sirius.showTopic(profileId, profileType, topic);
							});
							$('._thread-tab ._comment-count').click(function(){
								sirius.showMessage(profileId, profileType, $(this).parent('.message').attr('messageId'), 'comment');
							}); 
							$('._thread-tab ._retweet-count').click(function(){
								sirius.showMessage(profileId, profileType, $(this).parent('.message').attr('messageId'), 'retweet');
							}); 
							$('._thread-tab .message-more a')
								.click(function(event) {
									sirius.threadLoadMore($(event.target).parents('.popup-thread'));
								});

							$('#popup-dialog').dialog('option', {title: '话题: #' + topic + '#'});
					}});
				}
			});
		},
		
		showUser: function(profileId, profileType, username) {
			var sirius = this;
			$('#popup-dialog').dialog('destroy').html("").dialog({
				position: ['center', 100],
				resizable: false,
				minWidth: 300,
				maxWidth: 500,
				maxHeight: 500,
				title: "正在加载...",
				open: function(event, ui) {
					$.ajax({
						type: 'GET',
						url: '/' + profileType + '/userInfo',
						data: {profile_id: profileId, name: username},
						context: {profileId: profileId, profileType: profileType},
						success: function(data){
							$(data).appendTo('#popup-dialog').tabs({
							    cache: true,
								show: function (event, ui) {
									if ($(ui.tab).attr('expectedWidth') != undefined) {
										$('#popup-dialog').dialog('option', {width: $(ui.tab).attr('expectedWidth')});
									}
								},
								ajaxOptions: {
									context: {profileId: this.profileId, profileType: this.profileType},
									success: function( result, status) {
										var profileId = this.context.profileId;
										var profileType = this.context.profileType;
										$('._thread-tab ._message_picture_thumbnail').colorbox({
											maxWidth: '80%',
											maxHeight: '80%',
											photo: true,
											title: function(){
												var url = $(this).attr('href');
												return '<a class="show-origin-pic" href="'+url+'" target="_blank">查看大图</a>';
											}
										});
										
										$('._thread-tab .message-avatar,._thread-tab  .message-author').click(function() {
											sirius.showUser(profileId, profileType, $(this).attr('title'));
										});
															
										$('._thread-tab ._user_link').click(function(){
											var user = $(this).attr('user');
											sirius.showUser(profileId, profileType, user);
										});
										$('._thread-tab ._topic_link').click(function(){
											var topic = $(this).attr('topic');
											sirius.showTopic(profileId, profileType, topic);
										});
										$('._thread-tab ._comment-count').click(function(){
											sirius.showMessage(profileId, profileType, $(this).parent('.message').attr('messageId'), 'comment');
										}); 
										$('._thread-tab ._retweet-count').click(function(){
											sirius.showMessage(profileId, profileType, $(this).parent('.message').attr('messageId'), 'retweet');
										}); 
										$('._thread-tab .message-more a')
											.click(function(event) {
												sirius.threadLoadMore($(event.target).parents('.popup-thread'));
											});
									},
									error: function( xhr, status, index, anchor ) {
										$( anchor.hash ).html("<div class='dialog-error'>加载失败... 请稍候重试！</div>");
									}
								}
							});
							$('#popup-dialog').dialog('option', {title: $('._bio ._screen_name', data).text()});
					}});
				}
			});
		},
		
		removeAllThreads: function() {
			$('.thread', this.settings.dashboardElement).remove();
			$('.list-profile li a.highlight').removeClass('highlight');
		},
		
		setMessageBoxStatus: function(ele, action, profileId) {
			if ($('#imageContent').attr("imageUrl")) {
				this.focusSendPanel();
				this.statusMessage("如需评论或转发微博，请先删除准备发送的图片", "error");
				return;
			}
			switch (action) {
				case 'retweet':
					desc = "您正在转发 :";
					break;
				case 'comment':
					desc = "您正在评论 :";
					break;
			}
			var profileType = $(".selectProfiles .profileAvatar").removeClass("selected")
				.filter("[profileId=" + profileId + "]").addClass("selected").attr("profileType");
			
			$("#reactionContent").attr("actionType", action).attr("profileId", profileId).attr("profileType", profileType);
			
			$('#reactionContent .reactionInfo span').text(desc);
			var cleanEle = $(ele).clone()
				.removeClass("submessage")
				.find(".submessage").remove().end()
				.find(".message-actions").remove().end()
				.find('.message-count-status').remove().end();
			$('#reactionContent ._reaction-source').html('').append(cleanEle);
			$('#reactionContent .contentWrapper').show();
			this.focusSendPanel();
			$('.ac_input').focus();
			
			var sirius = this;
			$('.remove-reaction').click(function() {
				sirius.removeReaction();
			});
		},
		
		addPictureToMessageBox: function (imageUrl, imageThumbUrl) {
			if ($('#reactionContent').attr("actionType")) {
				this.focusSendPanel();
				this.statusMessage("正在评论或转发微博，无法插入图片", "error");
				return;
			}
			
			imageThumbUrl = imageThumbUrl || imageUrl;
			
			$('#reactionContent .contentWrapper').hide();
			$("#imageContent").attr("imageUrl", imageUrl);
			var imageNode = $("<img />").attr('src', imageThumbUrl);
			$('#imageContent ._image-preview').html('').append(imageNode);
			$('#imageContent .contentWrapper').show();
			this.focusSendPanel();
			$('.ac_input').focus();

			var sirius = this;
			$(imageNode).load(function() {
				sirius.focusSendPanel();
			});
			$('.remove-image').click(function() {
				sirius.removeImage();
			});
		},
		
		removeReaction: function() {
			$('#reactionContent ._reaction-source').html('');
			$("#reactionContent").attr("actionType", "").attr("profileId", "").attr("profileType", "");
			$('#reactionContent .contentWrapper').hide();
			this.focusSendPanel();			
		},
		
		removeImage: function() {
			$('#imageContent ._image-preview').html('');
			$("#imageContent").attr("imageUrl", "");
			$('#imageContent .contentWrapper').hide();
			this.focusSendPanel();			
		},
		
		sendMessage: function() {
			var message = $('._messageArea .ac_input').val();
			if ($("#reactionContent").attr("actiontype") != 'retweet' && message == '') {
				this.statusMessage('消息内容不能为空', 'warning');
				return;
			}
			var selectProfiles = $('.profileSelector .profileAvatar.selected');
			if (selectProfiles.length == 0) {
				this.statusMessage('请至少选择一个账号', 'warning');
				return;
			}
			var profiles = [];
			$(selectProfiles).each(function(index, profile){
				profiles.push($(profile).attr('profileType') + "|" +  $(profile).attr('profileId'));
			});
			this.statusMessage('正在发送消息...', 'info');
			
			var data = {message:message, profiles: profiles};
			if ($("#reactionContent").attr("actiontype")) {
				data.type = $("#reactionContent").attr("actiontype");
				data.profile_type = $("#reactionContent").attr('profileType');
				data.target_message_id = $("#reactionContent .reaction-source .message").attr('messageId');
				data.target_message_content = $("#reactionContent .reaction-source .message .message-body").html();
				data.target_message_author = $("#reactionContent .reaction-source .message .message-author").attr('title');
			}
			if ($("#imageContent").attr("imageUrl")) {
				data.image = $("#imageContent").attr("imageUrl");
			}
			
			this.unfocusSendPanel();
			
			$.ajax({
				type: 'POST',
				url: '/dashboard/send',
				data: data,
				dataType: 'json',
				context: this,
				success: function(){
					this.statusMessage('发送成功', 'info');
					$('.messageComposeBox').removeClass('collapsed').addClass('expanded');
					$('._messageArea .ac_input').val('');
					this.removeReaction();
					this.removeImage();
					this.unfocusSendPanel();
				}
			});
		},
		
		focusSendPanel: function() {
			$('.messageComposeBox').removeClass('collapsed').addClass('expanded');
			$('._messageArea ._pretext').hide();
			$('.selectProfiles').outerHeight($('.messageInfoBox').outerHeight());
			$('.profileSelector').outerHeight($('.selectProfiles').height() - $('._controls').outerHeight());	
		},
		
		unfocusSendPanel: function() {
			$('.messageComposeBox').removeClass('expanded').addClass('collapsed');
			if ($('._messageArea .ac_input').val() == "") {
				$('._messageArea ._pretext').show(); 
			}
			$('.selectProfiles').height('');
			$('.profileSelector').height('');
		},
		
		selectProfile: function(profiles) {
			// check reaction
			/*var reactionProfileType = $("#reactionContent").attr("profileType");
			if (reactionProfileType != undefined && reactionProfileType != "") {
				var hasDifferentType = false;
				$(profiles).each(function(index, item) {
					if ($(item).attr('profileType') != reactionProfileType) {
						hasDifferentType = true;
					}
				});
				if (hasDifferentType) {
					var reactionActionType = $("#reactionContent").attr("actionType");
					this.statusMessage("正在" + (reactionActionType == 'retweet' ? "转发" : "评论") + socialNetworkNames[reactionProfileType] + '消息, 无法选择其他网站的帐号', "error");
					return;
				}
			}*/
			$(profiles).addClass("selected");
		},
		
		deselectProfile: function(profiles) {
			$(profiles).removeClass("selected");
		},
		
		statusMessage: function(message, level) {
			var levels = ['error', 'warning', 'success', 'info']
			if (!level || $.inArray(level, levels) < 0) {
				level = 'info';
			}
			$('#statusContainer .statusMessage').removeClass(levels.join(' ')).addClass(level);
			$('#statusContainer ._statusMsgContent').text(message);
			$('#statusContainer').stop(true, true).show().delay(4000).fadeOut();
		}
	});

	$.sirius = $.sirius || new Sirius;

})(jQuery);
