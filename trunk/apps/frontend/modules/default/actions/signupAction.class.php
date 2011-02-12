<?php
class signupAction extends sfAction {
	public function execute($request) {
		$inviteCodeEnabled = sfConfig::get('app_invite_signup');
		
		if ($request->hasParameter('email') &&
			$request->hasParameter('name') &&
			$request->hasParameter('password') &&
			$request->hasParameter('password_again') &&
			(!$inviteCodeEnabled || $request->hasParameter('invite_code')) ) {
			
			$hasError = false;
			
			if ($inviteCodeEnabled) {
				// check invite code
				if ($request->getParameter('invite_code') == "") {
					$this->signUpErrors_invite_code = "您还未输入邀请码";
					$hasError = true;
				} else {
					$invite = Doctrine_Core::getTable('Invite')
						->createQuery()
						->where('purpose = "register" AND is_used = false AND code = ?', $request->getParameter('invite_code'))
						->fetchOne();
					if ($invite == NULL) {
						$this->signUpErrors_invite_code = "此邀请码不存在";
						$hasError = true;
					} else {
						$invite_expire_date = $invite->getExpireDate();
						if ($invite_expire_date != null && date_create($invite_expire_date) < date_create('now')) {
							$this->signUpErrors_invite_code = "此邀请码已过期";
							$hasError = true;
						}		
					}
				}
			}			
			// check email && username
			$email = $request->getParameter('email');
			$name = $request->getParameter('name');
			if($email == "") {
				$this->signUpErrors_email = "您还未输入电子邮箱";
				$hasError = true;
			} else {
				if (!preg_match(sfValidatorEmail::REGEX_EMAIL, $email))	{
					$this->signUpErrors_email = "电子邮箱格式不正确";
					$hasError = true;
				} else {
					$checkUser = Doctrine_Core::getTable('User')
						->createQuery()
						->where('user.email = ?', $email)
						->fetchOne();
					if ($checkUser != null) {
						$this->signUpErrors_email = "该邮箱地址已注册，请直接<a href='/login?email=" . $email . "'>登录</a>";
						$hasError = true;
					}
				}
			}
			
			if($name == "") {
				$this->signUpErrors_name = "您还未输入姓名";
				$hasError = true;
			}
			
			// check password
			$password = $request->getParameter('password');
			$password_again = $request->getParameter('password_again');
			if ($password == "") {
				$this->signUpErrors_password = "您还未输入密码";
				$hasError = true;
			} elseif (strlen($password) < 6) {
				$this->signUpErrors_password = '您输入的密码过短';
				$hasError = true;			
			}
			
			if ($password_again == "") {
				$this->signUpErrors_password_again = "您还未输入确认密码";
				$hasError = true;
			} elseif ($password != "" && $password_again != "" && $password != $password_again) {
				$this->signUpErrors_password_again = "密码和确认密码输入不一致";
				$hasError = true;
			}
			
			if ($hasError) {
				return sfView::SUCCESS;
			}
			
			// insert user
			$user = new User();
			$user->setEmail($email);
			$user->setFullName($name);
			$user->setPassword(md5($password));
			
			if (!$user->trySave()) {
				$this->signUpErrors = "注册失败，请检查所有输入项，或稍候重试";
				return sfView::SUCCESS;
			}
			
			// set invite code used
			if ($inviteCodeEnabled) {
				$invite->is_used = true;
				$invite->save();
			}
			
			// send validate email
			//TODO:_______
			
			// goto waiting for validate page
			//TODO:_______
			$this->redirect('default/login?email=' . $email);
		} else {
			return sfVIEW::SUCCESS;
		}		
	}
}