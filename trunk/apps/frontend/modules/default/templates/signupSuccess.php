<?php use_helper('I18N') ?>
<?php use_stylesheet('base.css') ?>
<?php use_stylesheet('login.css') ?>
<script>
	$(function(){
<?php if ($errorMsg): ?>
		var errorMsg = "<?php echo $errorMsg?>";
<?php endif?>
		$('#signupForm input').keypress(function(event){
			if (event.which == 13) {
				$('#signupForm').submit();
			}
		});
	});
</script>
<div id="container">
	<div id="content">
		<div class="signup">
			<div class="title">
				<h1>
					<a href="/"><?php echo __('MixMes - Social Media Portal') ?></a>
				</h1>
				<h2><?php echo __('Signup')?></h2>
			</div>
			<div class="section">
				<div id="secureId">
					<form name="signupForm" id="signupForm" method="post" action="<?php echo url_for('/signup')?>">
						<?php if ($signUpErrors) echo __($signUpErrors) ?>
					
						<label for="email" class="title"><?php echo __('电子邮箱:')?><span class="required">*</span></label>
						<input id="email" type="text" name="email" maxLength="100" />
						<?php if ($signUpErrors_email) echo __($sf_data->getRaw(signUpErrors_email)) ?>
						
						<label for="name" class="title"><?php echo __('姓名:')?><span class="required">*</span></label>
						<input id="name" type="text" name="name" maxLength="100" />
						<?php if ($signUpErrors_name) echo __($signUpErrors_name) ?>
						
						<label for="password" class="title"><?php echo __('密码:')?><span class="required">*</span></label>
						<input id="password" type="password" name="password" maxLength="100" />
						<?php if ($signUpErrors_password) echo __($signUpErrors_password) ?>
						
						<label for="password_again" class="title"><?php echo __('确认密码:')?><span class="required">*</span></label>
						<input id="password_again" type="password" name="password_again" maxLength="100" />
						<?php if ($signUpErrors_password_again) echo __($signUpErrors_password_again) ?>
						
						<?php if (sfConfig::get('app_invite_signup')): ?>
						<label for="invite_code" class="title"><?php echo __("邀请码:") ?><span class="required">*</span></label>
						<input id="invite_code" type="text" name="invite_code" maxLength="100" />
						<?php if ($signUpErrors_invite_code) echo __($signUpErrors_invite_code) ?>
						<?php endif?>
						
						<div class="buttons">
							<a class="btn-cmt" href="#" onclick="$('#signupForm').submit();">
							<?php echo __('注册')?>
							</a>
						</div>
					</form>
				</div>				
			</div>
		</div>
	</div>
	<div id="footer"></div>
</div>