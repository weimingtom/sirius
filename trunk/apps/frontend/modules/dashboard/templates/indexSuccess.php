<?php use_stylesheet('dashboard.css') ?>
<?php use_javascript('socialNetworkTypes.js') ?>
<?php use_javascript('sirius.js') ?>
<script>
$(function(){
  var options = options || {};
  options.profiles = <?php echo json_encode($sf_data->getRaw('profiles')); ?>;
  options.tabs = <?php echo json_encode($sf_data->getRaw('tabs')); ?>;
  $.sirius.init(options);
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
		<ul>
		</ul>
	</div>
	<div id="dashboard">
		<div class="dashboard-inner-wrapper">
		</div>
	</div>
</div>
