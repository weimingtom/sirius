<?php include_partial('global/message', array('message' => $sf_data->getRaw('message'))) ?>
<div class='_message-info-tabs'>
	<ul>
		<li>
			<a href="#转发" expectedWidth="400" title="转发">
				<span class="icon-19 action-retweet"></span>转发
			</a>
		</li>
		<li>
			<a href="/sohu/comments/?profile_id=<?php echo $profileId?>&id=<?php echo $message->id?>&format=html" expectedWidth="400" title="评论">
				<span class="icon-19 action-comment"></span>评论
			</a>
		</li>
	</ul>
	<div id="转发" class="_thread-tab">
		<div class="dialog-error">目前搜狐微博尚不支持获得转发列表。</div>
	</div>
	<div id="评论" class="_thread-tab">
		<div class="loading"><img src="/images/loading.gif" /></div>
	</div>
</div>