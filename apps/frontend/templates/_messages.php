<?php $messages = $sf_data->getRaw('messages'); ?>
<div class="messages" expectWidth="350">
<?php if (count($messages) == 0) :?>
	<div class="dialog-error">尚无微博</div>
<?php else: ?>
	<?php foreach($messages as $message): ?>
		<?php include_partial('global/message', array('message' => $message)) ?>
	<?php endforeach?>
<?php endif ?>
</div>