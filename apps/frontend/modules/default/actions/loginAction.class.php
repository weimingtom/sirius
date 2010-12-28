<?php
class loginAction extends sfAction {
	const REGEX_EMAIL = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';
	
	public function execute($request) {
		if ($request->hasParameter('email') && $request->hasParameter('password')) {
			$email = $request->getParameter('email');
			$password = $request->getParameter('password');
			if (!preg_match(self::REGEX_EMAIL, $email)) {
				$this->errorMsg = $this->getContext()->getI18N()->__('Email is illege!');
				return sfVIEW::SUCCESS;
			}
			if (strlen($password) < 6) {
				$this->errorMsg = $this->getContext()->getI18N()->__('Password is too short!');
				return sfVIEW::SUCCESS;				
			}
			
			// check db
			$user = Doctrine::getTable('User')
				->createQuery()
				->where('user.email = ?', $email)
				->fetchOne();
				
			if (!$user) {
				$this->errorMsg = $this->getContext()->getI18N()->__('Email doesn\'t exists!');
				return sfVIEW::SUCCESS;				
			}
			if ($user->getPassword() != md5($password)) {
				$this->errorMsg = $this->getContext()->getI18N()->__('Password doesn\'t match!');
				return sfVIEW::SUCCESS;								
			}

			$this->getUser()->setId($user->getId());
			$this->getUser()->setEmail($user->getEmail());
			$this->getUser()->setAuthenticated(true);

			//return $this->renderText("Login Succeed!");
			return $this->redirect("/dashboard");
		}
		return sfVIEW::SUCCESS;
	}
}
