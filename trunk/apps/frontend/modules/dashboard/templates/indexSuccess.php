<?php use_stylesheet('base.css') ?>
<?php use_stylesheet('dashboard.css') ?>
<?php use_stylesheet('colorbox.css') ?>
<?php use_javascript('socialNetworkTypes.js') ?>
<?php use_javascript('sirius.js') ?>
<?php use_javascript('jquery.colorbox-min.js')?>
<script>
$(function(){
	var options = options || {};
	options.profiles = <?php echo json_encode($sf_data->getRaw('profiles')); ?>;
	options.tabs = <?php echo json_encode($sf_data->getRaw('tabs')); ?>;
	$.sirius.init(options);
	
	$('.add-profile-button').colorbox({
		href:"<?php echo url_for('/profile/add')?>",
		innerWidth: 510,
		innerHeight: 320,
		iframe: true,
		escKey: false,
		overlayClose: false,
		onClosed: function(){
			$.ajax({
				type: 'GET',
				url: '/profile/list',
				dataType: 'json',
				context: this,
				success: function(data) {
					$.sirius.setProfiles(data);
				},
				error: function() {
					//alert("ERROR");
				}
			});
		}
	})
	if (options.profiles.length==0)
		$('.sidebar-add-profile').click();
	
	$('.sidebar-add-profile').hover(
		function(){$(this).animate({width: 80});}, 
		function(){$(this).animate({width: 0});}
	);
	
	$('.selectProfiles').hover(
		function(){$(this).addClass('activeExpanded').css('height', 'auto');},
		function(){$(this).removeClass('activeExpanded').css('height', '');}
	);
	
	$('.profileContainer ._controls ._selectAll').click(function(){
		$('.profileSelector .profileAvatar').addClass('selected');
	});
	$('.profileContainer ._controls ._selectNone').click(function(){
		$('.profileSelector .profileAvatar.selected').removeClass('selected');
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
					<div id="messageTools" class="_addLinkBlock _messageTools messageTools trim">
						<div class="messageMedia">
						</div>
						<div id="saveMessageButtons" class="_saveMessageButtons saveMessageButtons">
							<span class="section _submit"><a class="btn-cmt _submitAddMessage" href="#" title="Send Now (Shift+Enter)">发布</a></span>
						</div>
					</div>
				</div>
			</div>
			<div class="selectProfiles">
				<div class="profileContainer">
					<div class="profileSelector" style="height: auto;" ></div>				
					<div class="_controls controls">
						<div class="btns-right">
							<span class="btn-spl add"><a href="#" title="添加微博帐号" class="icon-13 add-profile-button"></a></span>
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
		<a href="/profile/add" title="添加微博帐号" class="icon-13 add-profile-button sidebar-add-profile">添加微博帐号</a>
		<ul>
		</ul>
	</div>
	<div id="dashboard">
		<div class="dashboard-inner-wrapper">
		</div>
	</div>
</div>
<div id="popup-dialog" style="display:none;">abc</div>