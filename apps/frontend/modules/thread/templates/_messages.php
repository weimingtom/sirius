<?php $messages = $sf_data->getRaw('messages'); ?>
<div class="messages" expectWidth="350">
<?php foreach($messages as $message): ?>
<div class="message">
	<a href="javascript:;" class="message-avatar" title="<?php echo $message->user->name?>">
		<img src="<?php echo $message->user->avatar?>">
	</a>
	<a href="javascript:;" class="message-author" title="<?php echo $message->user->name?>"><?php echo $message->user->screen_name?></a>
	<p class="message-time-via"><?php echo $message->creaeted_at?><?php if ($message->source != ""):?> via <?php echo $message->source?><?php endif ?></p>
	<span class="message-count-status">
	<?php if ($message->retweetCount >= 0): ?>
		<span><?php echo $message->retweetCount?></span> 条转发
	<?php endif?> 
	<?php if ($message->commentCount >= 0): ?>
		, <span><?php echo $message->commentCount?></span> 条评论
	<?php endif?>		
	</span>
	<p class="message-body"><?php echo $message->text?></p>
	<?php if ($message->picture_thumbnail != "") : ?>
	<a class="_message_picture_thumbnail" href="<?php echo $message->picture_original?>">
		<img src="<?php echo $message->picture_thumbnail?>">
	</a>
	<?php endif?>
	<?php if ($message->retweet_origin != null): ?>
	<div class="message submessage">
		<a href="javascript:;" class="message-avatar" title="<?php echo $message->retweet_origin->user->name?>">
			<img src="<?php echo $message->retweet_origin->user->avatar?>">
		</a>
		<a href="javascript:;" class="message-author" title="<?php echo $message->retweet_origin->user->name?>"><?php echo $message->retweet_origin->user->screen_name?></a>
		<p class="message-time-via"><?php echo $message->retweet_origin->creaeted_at?><?php if ($message->retweet_origin->source != ""):?> via <?php echo $message->retweet_origin->source?><?php endif ?></p>
		<span class="message-count-status">
		<?php if ($message->retweet_origin->retweetCount >= 0): ?>
			<span><?php echo $message->retweet_origin->retweetCount?></span> 条转发
		<?php endif?> 
		<?php if ($message->retweet_origin->commentCount >= 0): ?>
			, <span><?php echo $message->retweet_origin->commentCount?></span> 条评论
		<?php endif?>		
		</span>
		<p class="message-body"><?php echo $message->retweet_origin->text?></p>
		<?php if ($message->retweet_origin->picture_thumbnail != "") : ?>
		<a class="_message_picture_thumbnail" href="<?php echo $message->retweet_origin->picture_original?>">
			<img src="<?php echo $message->retweet_origin->picture_thumbnail?>">
		</a>
		<?php endif?>
	</div>
	<?php endif ?>
	
</div>

<?php endforeach?>
</div>