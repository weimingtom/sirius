<?php use_helper('I18N') ?>
<?php use_stylesheet('base.css') ?>
<?php use_stylesheet('login.css') ?>
<script>
	$(function(){
<?php if ($errorMsg): ?>
		var errorMsg = "<?php echo $errorMsg?>";
<?php endif?>
		$('#memberLoginForm input').keypress(function(event){
			if (event.which == 13) {
				$('#memberLoginForm').submit();
			}
		});
	});
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
							<label for="email" class="title"><?php echo __('电子邮箱:')?></label>
							<input id="email" type="text" name="email" maxLength="100" />
							<label for="password" class="title"><?php echo __('密码:')?></label>
							<input id="password" type="password" name="password" maxLength="100" />
							<p class="remember">
								<label for="remember" class="title">
									<input id="remember" class="checkbox" type="checkbox" name="rememberMe" checked />
									<?php echo __('下次自动登录')?>
								</label>
							</p>
							<div class="buttons">
								<a class="btn-cmt" href="#" onclick="$('#memberLoginForm').submit();">
								<?php echo __('登录')?>
								</a>
							</div>
							<div class="info">
			                    <?php echo __("还没有帐号?")?> <a href="/signup"><strong><?php echo __("注册")?></strong></a>
			                </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="footer"></div>
</div>