<div>
	<ul>
		<li><a href="#_<?php echo $userData['id']?>_bio">简介</a></li>
		<li><a href="/sina/user/' . profileId . '/timeline">微博</a></li>
	</ul>
	<div id="_<?php echo $userData['id']?>_bio" class="_bio userInfo">
		<span class="_screen_name" style="display:none"><?php echo $userData['screen_name']?></span>
		<span class="socialAvatar">
        <img class="networkAvatar" src="<?php echo $userData['profile_image_url_180']?>">
        </span>
		
	    <div class="userstats">
			<p><span><?php echo $userData['followers_count']?></span> 位粉丝</p>
	        <p><span><?php echo $userData['friends_count']?></span> 位关注</p>
	        <p><span><?php echo $userData['statuses_count']?></span> 条微博</p>
	    </div>
        
		<!--<p class="report"><a class="_reportSpammer" href="#"><span class="icon-19 spam"></span>Report Spammer</a></p>-->
		
        <p class="location"><strong>所在城市: </strong><?php echo $userData['location']?></p>
	
        <p class="bio"><strong>个人资料: </strong> <?php echo $userData['description']?></p>
	        		
	           
		<p><strong>帐号创建时间: </strong><?php echo strftime('%Y-%m-%d', strtotime($userData['created_at']))?></p>
	    
        
		<div class="btns">
			<?php if ($userData['url'] != ""): ?>
	     	<a class="btn-spl" href="<?php echo $userData['url']?>" title="<?php echo $userData['url']?>" target="_blank"><?php echo $userData['url']?></a>
	     	<?php endif?>
            <a class="btn-spl" href="http://t.sina.com.cn/<?php echo $userData['domain']?>" title="http://t.sina.com.cn/<?php echo $userData['domain']?>" target="_blank">http://t.sina.com.cn/<?php echo $userData['domain']?></a>
	    </div>
        
		
<!--		<div class="_userInfoActions userInfoActions btns">
	        <a class="_follow btn-cmt" href="#">Follow</a>
	        <a class="_unfollow btn-cmt" href="#">Unfollow</a>
	        <a class="_dm btn-cmt" href="#">DM</a>
	        <a class="_reply btn-cmt" href="#">Reply</a>
	        <a class="_addToList btn-cmt" href="#">Add To List</a>
	    </div>
-->
    </div>
</div>