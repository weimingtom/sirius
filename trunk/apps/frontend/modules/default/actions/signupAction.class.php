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
					$this->signUpErrors_invite_code = "Invite code is required!";
					$hasError = true;
				} else {
					$invite = Doctrine_Core::getTable('Invite')
						->createQuery()
						->where('invite.purpose = "register" AND invite.is_used = false AND code = ?', $request->getParameter('invite_code'))
						->fetchOne();
					if ($invite == NULL) {
						$this->signUpErrors_invite_code = "Invite code is invalid!";
						$hasError = true;
					} else {
						$invite_expire_date = $invite->getExpireDate();
						if ($invite_expire_date != null && date_create($invite_expire_date) < date_create('now')) {
							$this->signUpErrors_invite_code = "Invite code is expired!";
							$hasError = true;
						}		
					}
				}
			}			
			// check email && username
			$email = $request->getParameter('email');
			$name = $request->getParameter('name');
			if($email == "") {
				$this->signUpErrors_email = "Email is required!";
				$hasError = true;
			} else {
				if (!preg_match(sfValidatorEmail::REGEX_EMAIL, $email))	{
					$this->signUpErrors_email = "Email is invalid!";
					$hasError = true;
				} else {
					$checkUser = Doctrine_Core::getTable('User')
						->createQuery()
						->where('user.email = ?', $email)
						->fetchOne();
					if ($checkUser != null) {
						$this->signUpErrors_email = "This email has been used for another user!";
						$hasError = true;
					}
				}
			}
			
			if($name == "") {
				$this->signUpErrors_name = "Name is required!";
				$hasError = true;
			}
			
			// check password
			$password = $request->getParameter('password');
			$password_again = $request->getParameter('password_again');
			if ($password == "") {
				$this->signUpErrors_password = "Password is required!";
				$hasError = true;
			}
			if ($password_again == "") {
				$this->signUpErrors_password_again = "You need re-enter your password!";
				$hasError = true;
			}
			if ($password != "" && $password_again != "" && $password != $password_again) {
				$this->signUpErrors_password_again = "Oops, it's different from the previous one!";
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
				$this->signUpErrors = "Signup Fail, Please try again.";
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
			
			$this->redirect('default', 'login');
		} else {
			return sfVIEW::SUCCESS;
		}		
	}
}