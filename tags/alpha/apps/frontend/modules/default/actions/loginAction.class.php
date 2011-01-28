<?php
class loginAction extends sfAction {
	const REGEX_EMAIL = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';
	
	public function execute($request) {
		if ($this->getUser()->isAuthenticated())
	    {
	    	return $this->redirect("/dashboard");
	    }
		if ($request->hasParameter('email') && $request->hasParameter('password')) {
			$email = $request->getParameter('email');
			$password = $request->getParameter('password');
			if (!preg_match(self::REGEX_EMAIL, $email)) {
				$this->errorMsg = $this->getContext()->getI18N()->__('电子邮箱格式不正确');
				return sfVIEW::SUCCESS;
			}
			if (strlen($password) < 6) {
				$this->errorMsg = $this->getContext()->getI18N()->__('您输入的密码过短');
				return sfVIEW::SUCCESS;				
			}
			
			// check db
			$user = Doctrine::getTable('User')
				->createQuery()
				->where('email = ?', $email)
				->fetchOne();
				
			if (!$user) {
				$this->errorMsg = $this->getContext()->getI18N()->__('电子邮箱或者密码输入有误');
				return sfVIEW::SUCCESS;				
			}
			if ($user->getPassword() != md5($password)) {
				$this->errorMsg = $this->getContext()->getI18N()->__('电子邮箱或者密码输入有误');
				return sfVIEW::SUCCESS;								
			}

			$this->getUser()->setId($user->getId());
			$this->getUser()->setName($user->getFullName());
			$this->getUser()->setEmail($user->getEmail());
			$this->getUser()->setAuthenticated(true);

			//return $this->renderText("Login Succeed!");
			return $this->redirect("/dashboard");
		}
		return sfVIEW::SUCCESS;
	}
}
