<div>
	<ul>
		<li><a title="" href="#_thread_tab" class="_thread">微博</a></li>
	</ul>
	<div id="_thread_tab">
		<?php include_partial('thread/messages', array('messages' => $sf_data->getRaw('messages'))) ?>
    </div>
</div>