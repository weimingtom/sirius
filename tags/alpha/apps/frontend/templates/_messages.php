<?php $messages = $sf_data->getRaw('messages'); ?>

<div class="popup-thread" profileId="<?php echo $profileId?>" profileType="<?php echo $profileType?>" threadType="<?php echo $threadType?>" otherParams="<?php echo $otherParams?>">
	<div class="thread-scroll">
		<div class="thread-message-container" expectWidth="350">
		<?php if (count($messages) == 0) :?>
			<div class="dialog-error">尚无微博</div>
		<?php else: ?>
			<?php foreach($messages as $message): ?>
				<?php include_partial('global/message', array('message' => $message)) ?>
			<?php endforeach?>
		<?php endif ?>
		</div>
		<?php if (count($messages) > 0 && $loadMore): ?>
		<div class="message-more">
			<a href="#">加载更多</a>
		</div>
		<?php endif?>
	</div>
</div>