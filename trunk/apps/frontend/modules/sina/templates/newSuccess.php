<?php use_stylesheet('base.css') ?>
<script>
	$(function(){
		$('._add-profile').click(function(event){
			url = '/sina/auth';
			if ($('#addTab:checked').size() > 0) {
				url += "?addTab=1"
			}
			open(url, '_blank', 'height=430, width=470, location=no, status=no');
		});
	});
</script>
<div class="profile-add-window" style="margin-top: 2px">
	<h1>添加新浪微博帐号</h1>
	<p>为了在MixMes中管理您的新浪微博帐号，您需要允许MixMes访问您的新浪帐号。</p>
	<a class="_add-profile" href="#">
	    <img alt="链接新浪微博" src="/images/connect_32/sina.png">
	</a>
	<p>
		<input name="addTab" id="addTab" type="checkbox" checked="checked" />
		<label for="addTab">同时为该帐号添加一个面板</label>
	</p>
</div>