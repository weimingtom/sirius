<div>
	<ul>
		<li><a href="#_<?php echo $userData['id']?>_bio">简介</a></li>
		<li><a href="/sina/user/' . profileId . '/timeline">微博</a></li>
	</ul>
	<div id="_<?php echo $userData['id']?>_bio" class="_bio userInfo">
		<span class="_screen_name" style="display:none"><?php echo $userData['nick']?></span>
		<span class="socialAvatar">
        	<img class="networkAvatar" src="<?php echo $userData['head']?>">
        	<span class="icon-28 thread-icon-qq networklogo">qq</span>
        </span>
		
	    <div class="userstats">
			<p><span><?php echo $userData['fansnum']?></span> 位粉丝</p>
	        <p><span><?php echo $userData['idolnum']?></span> 位关注</p>
	        <p><span><?php echo $userData['tweetnum']?></span> 条微博</p>
	    </div>
        
		<!--<p class="report"><a class="_reportSpammer" href="#"><span class="icon-19 spam"></span>Report Spammer</a></p>-->
		
        <p class="location"><strong>所在城市: </strong><?php echo $userData['location']?></p>
	
        <p class="bio"><strong>个人资料: </strong> <?php echo $userData['introduction']?></p>
        
		<div class="btns">
            <a class="btn-spl" href="http://t.qq.com/<?php echo $userData['name']?>" title="http://t.qq.com/<?php echo $userData['name']?>" target="_blank">http://t.qq.com/<?php echo $userData['name']?></a>
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