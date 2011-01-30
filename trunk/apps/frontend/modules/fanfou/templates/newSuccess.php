<?php use_stylesheet('base.css') ?>
<style>
	.fanfou_form {
		margin-top: 4px;
	}
	.fanfou_form label {
		width: 70px;
		text-align: right;
		display: inline-block;
	}
	.fanfou_form input {
		width: 200px;		
	}
	
	.fanfou_form .btn-cmt {
		margin-right: 18px;
		float: right;
	}
</style>
<script>
	$(function(){
		$('._addFanfouAccount').click(function() {
			var sirius = window.parent.jQuery.sirius;
			if (!sirius) return;

			if ($('#fanfou_username').val() == "" ||
				$('#fanfou_password').val() == "") {
				sirius.statusMessage('饭否用户名和密码都要填哦!');
				return;
			}

			$.ajax({
				type: "POST",
				url: "/fanfou/auth",
				dataType: "json",
				data: $(".fanfou_form").serialize(),
				success: function(data) {
					if (data.error) {
						sirius.statusMessage(data.error, "error");
						return;
					}
				
					$('#fanfou_username').val("");
					$('#fanfou_password').val("");
					sirius.statusMessage('饭否添加帐号成功', 'success');
					sirius.refreshProfiles();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					sirius.statusMessage("添加失败，是不是用户名密码记错了?再试试", "error");
				}
			});
		});
	});
</script>
<div class="profile-add-window" style="margin-top: 2px">
	<h1>添加饭否帐号</h1>
	<p>为了在MixMes中管理您的饭否帐号，您需要允许MixMes访问您的微博帐号。</p>
	<form class="fanfou_form" action="/fanfou/auth">
		<p>
			<label for="fanfou_username">饭否用户名</label>
			<input type="text" id="fanfou_username" name="fanfou_username" />
		</p>
		<p>
			<label for="fanfou_password">密 码</label>
			<input type="password" id="fanfou_password" name="fanfou_password" />
		</p>
		<a class="btn-cmt _addFanfouAccount" href="#" title="添加饭否帐号">添加饭否帐号</a>
	</form>
</div>