<div>
	<ul>
		<li><a href="#_<?php echo $userData['db:uid']['$t']?>_bio" expectedWidth="320">简介</a></li>
		<li><a title="广播" href="/douban/user/?profile_id=<?php echo $profileId?>&name=<?php echo $userData['db:uid']['$t']?>&format=html" class="_thread" expectedWidth="400">广播</a></li>
	</ul>
	<div id="_<?php echo $userData['db:uid']['$t']?>_bio" class="_bio userInfo">
		<span class="_screen_name" style="display:none"><?php echo $userData['title']['$t']?></span>
		<span class="socialAvatar">
        	<img class="networkAvatar" src="<?php echo $userData['head']?>">
        	<span class="icon-28 thread-icon-douban networklogo">douban</span>
        </span>

        <p class="location"><strong>所在城市: </strong><?php echo $userData['db:location']['$t']?></p>
	
        <p class="bio"><strong>个人资料: </strong> <?php echo $userData['content']['$t']?></p>
        
		<div class="btns">
		<?php foreach ($userData['links'] as $alink):?>
            <a class="btn-spl" target="_blank" href="<?php echo $alink?>" title="<?php echo $alink?>"><?php echo $alink?></a>
	    <?php endforeach?>
	    </div>
        

    </div>
    <div class="_thread-tab" id="广播">
		<div class="loading"><img src="/images/loading.gif" /></div>
	</div>
</div>