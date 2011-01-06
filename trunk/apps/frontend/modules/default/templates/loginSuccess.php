<?php use_helper('I18N') ?>
<?php use_stylesheet('base.css') ?>
<?php use_stylesheet('login.css') ?>
<script>
<?php if ($errorMsg): ?>
	$(function(){
		var errorMsg = "<?php echo $errorMsg?>";
	});
<?php endif?>
</script>
<div id="container">
	<div id="content">
		<div class="login rb-a-5">
			<div class="title">
				<h1>
					<a href="/"><?php echo __('MixMes - Social Media Portal') ?></a>
				</h1>
				<h2><?php echo __('Login')?></h2>
			</div>
			<div class="section">
				<div id="loginBox" class="rb-a-4">
					<div id="secureId">
						<form name="memberLoginForm" id="memberLoginForm" method="post" action="/login">
							<label for="email" class="title"><?php echo __('Email:')?></label>
							<input id="email" type="text" name="email" maxLength="100" />
							<label for="password" class="title"><?php echo __('Password:')?></label>
							<input id="password" type="password" name="password" maxLength="100" />
							<p class="remember">
								<label for="remember" class="title">
									<input id="remember" class="checkbox" type="checkbox" name="rememberMe" checked />
									<?php echo __('Remember me')?>
								</label>
							</p>
							<div class="buttons">
								<a class="btn-cmt" href="#" onclick="$('#memberLoginForm').submit();">
								<?php echo __('Login')?>
								</a>
							</div>
							<div class="info">
			                    <?php echo __("Don't have an account?")?> <a href="/signup"><strong><?php echo __("Sign Up")?></strong></a>
			                </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="footer"></div>
</div>