<?php use_stylesheet('console/screen.css')?>
<?php use_stylesheet('console/layout.css')?>
<?php use_javascript('console/console.js')?>
<form id="test_form">
<div class="middle-container clearfix">
	<div class="middle-container clearfix">
		<div class="content_header clearfix"><h2>API 测试控制台</h2></div>
		<div class="content clearfix">
			<div id="tools_content" class="clearfix">
				<div id="warn_user" class="hide_warning"></div>
				<div class="form_bg clearfix">
					<div class="controls">
						<select class="select" id="provider" name="provider">
							<option value="sina">新浪微博</option>
							<option value="qq">腾讯微博</option>
							<option value="sohu">搜狐微博</option>
							<option value="fanfou">饭否</option>
							<option value="douban">豆瓣</option>
						</select>
						<label>用户 ID</label>
						<input type="text" class="inputtext disabled" id="user_id" readonly="readonly" value="尚未登录">
						<div class="control" id="response_format">
							<label>返回格式</label>
							<select class="select" id="format" name="format">
							</select>
						</div>
						<div class="control">
							<label id="doc_url">方法</label>
							<select class="select" id="method" name="method">
								<option value="none">请选择</option>
							</select>
						</div>
						<div id="submit" class="after_parameter">
							<input type="submit" class="inputsubmit" id="method_submit" name="method_submit" value="调用此方法">
						</div>
						
						<div class="notice">
							如果有问题，请<a href="mailto:getcary@gmail.com">联系我</a>。
						</div>
					</div>
					<div id="query_url">请求的地址及参数在这里显示。</div>
					<div id="trace">返回的结果在这里显示。</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>