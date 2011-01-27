<?php use_helper('I18N') ?>
<?php use_stylesheet('login.css') ?>
<?php use_stylesheet('colorbox.css') ?>
<?php use_javascript('jquery.colorbox-min.js')?>
<script>
	$(function(){
		$("._apply-invite-code").colorbox({inline:true});
	});
</script>
<div id="container" class="vertical-center">
	<div class="pane signup">
		<div class="logo">
			<h1>
				<a href="/"><?php echo __('MixMes - Social Media Portal') ?></a>
			</h1>
		</div>
		<p class="summary">注册帐号</p>
		<form name="signupForm" id="signupForm" method="post" class="signup-form clean" action="<?php echo url_for('/signup')?>">
			<?php if ($signUpErrors) echo __($signUpErrors) ?>
			
			<p>
				<label for="email"><?php echo __('电子邮箱:')?></label>
				<input id="email" type="text" name="email" maxLength="100" value="<?php echo $sf_params->get('email') ?>" />
				<?php if ($signUpErrors_email) :?><span htmlfor="email" generated="true" class="error"><?php echo __($sf_data->getRaw(signUpErrors_email)) ?></span><?php endif ?>
			</p>
			<p>
				<label for="name"><?php echo __('姓名:')?></label>
				<input id="name" type="text" name="name" maxLength="100"  value="<?php echo $sf_params->get('name') ?>"/>
				<?php if ($signUpErrors_name) :?><span htmlfor="name" generated="true" class="error"><?php echo __($sf_data->getRaw(signUpErrors_name)) ?></span><?php endif ?>
			</p>
			<p>
				<label for="password"><?php echo __('密码:')?></label>
				<input id="password" type="password" name="password" maxLength="100" value="<?php echo $sf_params->get('password') ?>"/>
				<?php if ($signUpErrors_password) :?><span htmlfor="password" generated="true" class="error"><?php echo __($sf_data->getRaw(signUpErrors_password)) ?></span><?php endif ?>
			</p>
			<p>
				<label for="password_again"><?php echo __('确认密码:')?></label>
				<input id="password_again" type="password" name="password_again" maxLength="100" />
				<?php if ($signUpErrors_password_again) :?><span htmlfor="password_again" generated="true" class="error"><?php echo __($sf_data->getRaw(signUpErrors_password_again)) ?></span><?php endif ?>
			</p>			
			<?php if (sfConfig::get('app_invite_signup')): ?>
			<p>
				<label for="invite_code"><?php echo __("邀请码:") ?></label>
				<input id="invite_code" type="text" name="invite_code" maxLength="100" value="<?php echo $sf_params->get('invite_code') ?>"/>
				<?php if ($signUpErrors_invite_code) :?><span htmlfor="invite_code" generated="true" class="error"><?php echo __($sf_data->getRaw(signUpErrors_invite_code)) ?></span><?php endif ?>
			</p>
			<?php endif?>
			<input value="<?php echo __('注册')?>" type="submit" class="submit" />
		</form>
		<p class="note">已经有帐号了? <a href="/login">登录</a></p>
		<?php if (sfConfig::get('app_invite_signup')): ?>
		<p class="note">没有邀请码? 去<a href="http://t.sina.com.cn" target="_blank"/>微博</a>问问朋友有没有</p>
		<?php endif?>
	</div>
</div>
