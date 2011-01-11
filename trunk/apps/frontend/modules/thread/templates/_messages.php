<?php $messages = $sf_data->getRaw('messages')?>
<div class="messages">
<?php foreach($messages as $message): ?>
<div class="message">
	<a href="javascript:;" class="message-avatar" title="<?php echo $message->user->name?>">
		<img src="<?php echo $message->user->avatar?>">
	</a>
	<a href="javascript:;" class="message-author" title="<?php echo $message->user->name?>"><?php echo $message->user->screen_name?></a>
	<p class="message-time-via"><?php echo $message->creaeted_at?><?php if ($message->source != ""):?> via <?php echo $message->source?><?php endif ?></p>
	<p class="message-body"><?php echo $message->text?></p>
	<?php if ($this->picture_thumbnail != "") : ?>
	<a href="<?php echo $message->picture_original?>">
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
		<p class="message-body"><?php echo $message->retweet_origin->text?></p>
		<?php if ($this->retweet_origin->picture_thumbnail != "") : ?>
		<a href="<?php echo $message->retweet_origin->picture_original?>">
			<img src="<?php echo $message->retweet_origin->picture_thumbnail?>">
		</a>
		<?php endif?>
	</div>
	<?php endif ?>
	
</div>

<?php endforeach?>
</div>