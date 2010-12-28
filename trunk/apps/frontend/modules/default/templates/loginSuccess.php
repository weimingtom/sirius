<?php use_helper('I18N') ?>
<script>
<?php if ($errorMsg): ?>
	$(function(){
		var errorMsg = "<?php echo $errorMsg?>";
	});
<?php endif?>
</script>
<div id="container">
	<div id="content">
		<div class="login">
			<div class="title">
				<h1>
					<a href="/"><?php echo __('Sirius - Social Media Portal') ?></a>
				</h1>
				<h2><?php echo __('Login')?></h2>
			</div>
			<div class="section">
				<div id="secureId">
					<form name="memberLoginForm" id="memberLoginForm" method="post" action="/login">
						<label for="email" class="title"><?php echo __('Email:')?></label>
						<input id="email" type="text" name="email" maxLength="100" />
						<label for="password"><?php echo __('Password:')?></label>
						<input id="password" type="password" name="password" maxLength="100" />
						<p class="remember">
							<label for="remember" class="title">
								<input id="remember" class="checkbox" type="checkbox" name="rememberMe" checked />
								<?php echo __('Remember me')?>
							</label>
						</p>
						<div class="buttons">
							<a class="button" href="#" onclick="$('#memberLoginForm').submit();">
							<?php echo __('Login')?>
							</a>
						</div>
					</form>
				</div>				
			</div>
		</div>
	</div>
	<div id="footer"></div>
</div>