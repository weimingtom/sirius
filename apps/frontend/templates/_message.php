<?php $message = $sf_data->getRaw('message'); ?>
<div class="message" messageId="<?php echo $message->id?>">
	<a href="javascript:;" class="message-avatar" title="<?php echo $message->user->name?>">
		<img src="<?php echo $message->user->avatar?>">
	</a>
	<a href="javascript:;" class="message-author" title="<?php echo $message->user->name?>"><?php echo $message->user->screen_name?></a>
	<p class="message-time-via"><?php echo $message->created_at?><?php if ($message->source != ""):?> via <?php echo $message->source?><?php endif ?></p>
	<p class="message-body"><?php echo $message->text?></p>
	<?php if ($message->picture_thumbnail != "") : ?>
	<a class="_message_picture_thumbnail" href="<?php echo $message->picture_original?>">
		<img src="<?php echo $message->picture_thumbnail?>">
	</a>
	<?php endif?>
	<div style="clear:both"></div>
	<?php if ($message->retweetCount > 0): ?>
		<a href='#' class='message-count-status _retweet-count'><span class="icon-19 action-retweet"></span><span><?php echo $message->retweetCount?></span> 条转发</a>
	<?php endif?> 
	<?php if ($message->commentCount > 0): ?>
		<a href='#' class='message-count-status _comment-count'><span class="icon-19 action-comment"></span><span><?php echo $message->commentCount?></span> 条评论</a>
	<?php endif?>
	<?php if ($message->retweet_origin != null): ?>
	<div class="message submessage" messageId="<?php echo $message->retweet_origin->id?>">
		<a href="javascript:;" class="message-avatar" title="<?php echo $message->retweet_origin->user->name?>">
			<img src="<?php echo $message->retweet_origin->user->avatar?>">
		</a>
		<a href="javascript:;" class="message-author" title="<?php echo $message->retweet_origin->user->name?>"><?php echo $message->retweet_origin->user->screen_name?></a>
		<p class="message-time-via"><?php echo $message->retweet_origin->created_at?><?php if ($message->retweet_origin->source != ""):?> via <?php echo $message->retweet_origin->source?><?php endif ?></p>
		<p class="message-body"><?php echo $message->retweet_origin->text?></p>
		<?php if ($message->retweet_origin->picture_thumbnail != "") : ?>
		<a class="_message_picture_thumbnail" href="<?php echo $message->retweet_origin->picture_original?>">
			<img src="<?php echo $message->retweet_origin->picture_thumbnail?>">
		</a>
		<?php endif?>
		<div style="clear:both"></div>
		<?php if ($message->retweet_origin->retweetCount > 0): ?>
			<a href='#' class='message-count-status _retweet-count'><span class="icon-19 action-retweet"></span><span><?php echo $message->retweet_origin->retweetCount?></span> 条转发</a>
		<?php endif?> 
		<?php if ($message->retweet_origin->commentCount > 0): ?>
			<a href='#' class='message-count-status _comment-count'><span class="icon-19 action-comment"></span><span><?php echo $message->retweet_origin->commentCount?></span> 条评论</a>
		<?php endif?>
	</div>
	<?php endif ?>
	
</div>