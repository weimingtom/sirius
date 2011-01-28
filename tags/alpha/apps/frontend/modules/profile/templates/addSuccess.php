<script>
$(function(){
	$("#snList li").click(function(){
		$("#snList li").removeClass('active');
		$(this).addClass('active');
		$("#snPage iframe").attr('src', '/' + $(this).attr('profileType') + '/new');
	});
	
	$("#snList li:first").click();
});
</script>
<div id="snList" class="sidebarTabs">
	<ul>
		<?php foreach ($supportList as $type=>$name) :?>
		<li profileType="<?php echo $type?>">
			<a href="#">
				<span class="icon-16 profile-<?php echo $type?>"></span>
				<?php echo $name?>
			</a>
		</li>
		<?php endforeach ?>
	</ul>
</div>
<div id="snPage" class="sidebarTabContent">
	<div class="tabContentWrapper">
		<iframe src="" allowtransparency="true" frameborder="0" border="0" cellspacing="0"></iframe>
	</div>
</div>