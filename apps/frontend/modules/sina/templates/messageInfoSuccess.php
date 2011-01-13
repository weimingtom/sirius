<?php include_partial('global/message', array('message' => $sf_data->getRaw('message'))) ?>
<div class='_message-info-tabs'>
	<ul>
		<li>
			<?php if ($message->retweet_origin == null):?>
			<a href="/sina/retweets/?profile_id=<?php echo $profileId?>&id=<?php echo $message->id?>&format=html" expectedWidth="400" title="转发">
				<span class="icon-19 action-retweet"></span>转发
			</a>
			<?php else: ?>
			<a href="#转发" expectedWidth="400" title="转发">
				<span class="icon-19 action-retweet"></span>转发
			</a>
			<?php endif ?>
		</li>
		<li>
			<a href="/sina/comments/?profile_id=<?php echo $profileId?>&id=<?php echo $message->id?>&format=html" expectedWidth="400" title="评论">
				<span class="icon-19 action-comment"></span>评论
			</a>
		</li>
	</ul>
	<div id="转发" class="_thread-tab">
		<?php if ($message->retweet_origin == null):?>
		<div class="loading"><img src="/images/loading.gif" /></div>
		<?php else: ?>
		<div class="dialog-error">转发微博无法显示再次被转发详细记录。</div>
		<?php endif ?>
	</div>
	<div id="评论" class="_thread-tab">
		<div class="loading"><img src="/images/loading.gif" /></div>
	</div>
</div>