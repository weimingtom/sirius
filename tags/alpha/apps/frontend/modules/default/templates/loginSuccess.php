<?php use_helper('I18N') ?>
<?php use_stylesheet('login.css') ?>
<div id="container" class="vertical-center">
	<div class="pane login">
		<div class="logo">
			<h1>
				<a href="/"><?php echo __('MixMes - Social Media Portal') ?></a>
			</h1>
		</div>
		<p class="summary">MixMes帮助您管理微博帐号，分析微博趋势，策划实施微博营销。</p>
		<?php if ($errorMsg): ?>
		<p class="error"><?php echo $errorMsg?></p>
		<?php endif?>
		<form name="memberLoginForm" id="memberLoginForm" class="login-form clean" method="post" action="/login">
			<p>
				<label for="email"><?php echo __('电子邮箱:')?></label>
				<input id="email" type="text" name="email" maxLength="100" value="<?php echo $sf_params->get('email') ?>"/>
			</p>
			<p>
				<label for="password"><?php echo __('密码:')?></label>
				<input id="password" type="password" name="password" maxLength="100" />
			</p>
			
			<p class="inline">
				<input id="remember" class="checkbox" type="checkbox" name="rememberMe" checked />
				<label for="id_remember" class="clickable"><?php echo __('下次自动登录')?></label>
			</p>
			<input value="<?php echo __('登录')?>" type="submit" class="submit" />			
		</form>
		<p class="note">还没有帐号? <a href="/signup">去注册一个</a></p>
	</div>
</div>