<?php use_stylesheet("base.css") ?>
<?php use_stylesheet("popup.css") ?>
<script>
$(function(){
	$("#snList li").click(function(){
		$("#snList li").removeClass('active');
		$(this).addClass('active');
		$("#snPage > iframe").attr('src', '/' + $(this).attr('type') + '/new');
	});
});
</script>
<div id="snList" class="sidebarTabs">
	<ul>
		<?php foreach ($supportList as $type=>$name) :?>
		<li type="<?php echo $type?>">
			<a href="#">
				<span class="icon-16 profile-<?php echo $type?>"></span>
				<?php echo $name?>
			</a>
		</li>
		<?php endforeach ?>
	</ul>
</div>
<div id="snPage" class="tabContent">
	<iframe src=""></iframe>
</div>