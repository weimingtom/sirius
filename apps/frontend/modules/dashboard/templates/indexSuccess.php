<?php use_stylesheet('base.css') ?>
<?php use_stylesheet('dashboard.css') ?>
<?php use_stylesheet('colorbox.css') ?>
<?php use_stylesheet('tip-yellowsimple.css') ?>
<?php use_stylesheet('jquery.ui.selectmenu.css') ?>
<?php use_javascript('socialNetworkTypes.js') ?>
<?php use_javascript('sirius.js') ?>
<?php use_javascript('jquery.colorbox-min.js')?>
<?php use_javascript('swfupload.js') ?>
<?php use_javascript('upload.js') ?>
<?php use_javascript('jquery.poshytip.min.js') ?>
<?php use_javascript('jquery.ui.selectmenu.js') ?>
<?php use_javascript('jquery.timers-1.2.js') ?>
<script>
$(function(){
	var options = options || {};
	options.profiles = <?php echo json_encode($sf_data->getRaw('profiles')); ?>;
	options.tabs = <?php echo json_encode($sf_data->getRaw('tabs')); ?>;
	options.activeTabId = <?php echo $activeTabId ?>;
	options.threadWidth = <?php echo $threadWidth ?>;
	options.refreshFrequency = <?php echo $refreshFrequency ?>;
	$.sirius = $.sirius || new Sirius;
	$.sirius.init(options);
	$('._add-profile-button').click(function() {
		$('#popup-dialog').dialog('destroy').html("").dialog({
				modal: true,
				position: ['center', 100],
				resizable: false,
				width: 500,
				height: 370,
				title: "添加社交网络帐号",
				open: function(event, ui) {
					$.get('/profile/add', {}, function(data) {
						$(data).appendTo('#popup-dialog');
					}, 'html');
					$('.ui-widget-overlay').html('<img src="/images/ui-overlay-gradient.png" style="width:100%; height: 100%;">');
				}
		});
	});
	if (options.profiles.length==0)
		$('.sidebar-add-profile').click();
	
	$('.selectProfiles').hover(
		function(){$(this).addClass('activeExpanded').css('height', 'auto');},
		function(){$(this).removeClass('activeExpanded').css('height', '');}
	);
	
	$('.profileContainer ._controls ._selectAll').click(function(){
		$.sirius.selectProfile($('.profileSelector .profileAvatar'));
	});
	$('.profileContainer ._controls ._selectNone').click(function(){
		$.sirius.deselectProfile($('.profileSelector .profileAvatar'));
	});
	
	$('._messageArea .ac_input').focusin(function(){
		$.sirius.focusSendPanel();
	}).keyup(function(){
		$('._charCounter ._counter').text(140 - $(this).val().length);
	});

	$(document).click(function(event){
		if ($('.messageComposeBox').hasClass('expanded') && !$.contains($('.tweet-panel')[0], event.target)) {
			$.sirius.unfocusSendPanel();
		}
	});
	
	$('._submitAddMessage').click(function(){$.sirius.sendMessage();});
	
	$('._uploads').poshytip({
		className: 'tip-yellowsimple',
		alignTo: 'target',
		alignX: 'center'
	});

	$("._refresh-all").click(function(event){
		$.sirius.refreshAllThread();
	});

	$('._refresh-frequency').val(options.refreshFrequency).selectmenu({
		width: "14px",
		menuWidth: "10.3em",
		style: 'dropdown',
		change: function(event) {
			$.sirius.changeRefreshFrequency(event.target.value);
		}
	});

	$('._thread-width-adjust').slider({
		min: 300,
		max: 600,
		value: options.threadWidth,
		slide: function(event, ui) {
			$.sirius.setThreadWidth(ui.value);
		},
		change: function(event, ui) {
			$.sirius.saveThreadWidth(ui.value);
		}
	}).poshytip({
		className: 'tip-yellowsimple',
		alignTo: 'target',
		alignX: 'center',
		offsetY: 5
	});;
});
</script>
<div id="statusContainer">
	<div class="statusMessage rb-a-4">
		<span class="_statusMsgContent">Your message cannot be empty</span>
	</div>
</div>
<div id="header">
	<div class="logo">
		<h1>Sirius - Manage your relations</h1>
	</div>
	<div class="tweet-panel" id="messageBoxContainer">
	<div style="clear:both;"></div>
		<div class="messageComposeBox collapsed">
			<div class="messageInfoBox">
				<div class="messageInfo _messageArea">
					<div class="messageBoxMessageWrapper">
						<div class="messageBoxMessageContainer _messageContainer ui-droppable">
							<textarea class="messageBoxMessage ac_input" id="messageBoxMessage" name="message[message]" autocomplete="off"></textarea>
							<span class="_pretext pretext" style="display: block; ">撰写新消息...</span>
						</div>
						<div class="_charCounter charCountBox rb-a-3">
							<span class="_counter btn-display">140</span>
						</div>
					</div>
					
					<div id="reactionContent" class="reactionContent">
						<div class="contentWrapper">
							<div class="reactionInfo">
								<span>您正在</span>
								<a class="remove-reaction icon-28" title="取消">取消</a>
							</div>
							<div class="reaction-source _reaction-source">
							</div>							
						</div>
					</div>
					
					<div id="imageContent" class="imageContent">
						<div class="contentWrapper">
							<div class="imageInfo">
								<a class="remove-image icon-28" title="删除图片">删除图片</a>
							</div>
							<div class="image-preview _image-preview">
							</div>							
						</div>
					</div>
					
					<div id="messageTools" class="_addLinkBlock _messageTools messageTools trim">
						<div class="messageMedia">
							<span class="section _uploads" title="上传图片"><span id="uploadPlaceholder"></span></span>
						</div>
						<div id="saveMessageButtons" class="_saveMessageButtons saveMessageButtons">
							<span class="section _submit"><a class="btn-cmt _submitAddMessage" href="#" title="发布新消息">发布</a></span>
						</div>
					</div>
				</div>
			</div>
			<div class="selectProfiles">
				<div class="profileContainer">
					<div class="profileSelector" style="height: auto;" ></div>				
					<div class="_controls controls">
						<div class="btns-right">							
								<a href="#" title="添加社交网络帐号" class="btn-spl add _add-profile-button"><span class="icon-13 icon-add"></span>添加社交网络帐号</a>							
						</div>
						<a href="#" class="_selectAll btn-spl">选择全部</a>
						<a href="#" class="_selectNone btn-spl">取消选择</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="container">
	<div id="sidebar" class="">
		<ul>
		</ul>
		<a href="#" title="添加社交网络帐号" class="btn-spl sidebar-add-profile _add-profile-button"><span class="icon-13 icon-add"></span>添加社交网络帐号</a>	
	</div>
	<div id="dashboard">
		<div class="dashboard-controller _dashboard-cotroller">
			<a href="#" class="_refresh-all refresh-all btn-spl" title="全部刷新" style="display: inline-block; ">
				<span class="icon-19 refresh-button"></span>
			</a>
			<select class="refresh-frequency _refresh-frequency" name="refreshFrequency">
				<option value="5">每5分钟刷新全部</option>
				<option value="10">每10分钟刷新全部</option>
				<option value="20">每20分钟刷新全部</option>
				<option value="0">手动刷新</option>
			</select>
			<div class="thread-width-adjust-holder rb-a-3">
				<div class="thread-width-adjust _thread-width-adjust" title="设置栏目宽度"></div>
			</div>
		</div>
	</div>
</div>
<div id="footer">
	<span class="username"><?php echo $username ?></span>
	<a class="logout" href="/logout">退出登陆 »</a>
</div>
<div id="popup-dialog" style="display:none;">abc</div>