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
  	},
  }).hover(
  	function(){$(this).animate({width: 56});}, 
  	function(){$(this).animate({width: 0});}
  );
});
</script>
<div id="header">
	<div class="logo">
		<h1>Sirius - Manage your relations</h1>
	</div>
	<div class="tweet-panel">
		<textarea id="tweetbox"></textarea>
		<ul class="tweet-destination hideMe">
			<li class="tweet-twitter ">
				<img class="tweet-user-icon" src="http://a3.twimg.com/profile_images/64812095/MM_normal.png" />
			</li>
			<li class="tweet-sina tweet-destination-selected">
				<img class="tweet-user-icon" src="http://a3.twimg.com/profile_images/64812095/MM_normal.png" />
			</li>
		</ul>
	</div>
</div>
<div id="container">
	<div id="sidebar" class="">
		<a href="/profile/add" title="添加账户" class="icon-16 add-profile-button">添加账户</a>
		<ul>
		</ul>
	</div>
	<div id="dashboard">
		<div class="dashboard-inner-wrapper">
		</div>
	</div>
</div>
