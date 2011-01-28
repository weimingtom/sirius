<?php
class logoutAction extends sfAction {
	public function execute($request) {
		if ($this->getUser()->isAuthenticated())
	    {
	      $this->getUser()->setAuthenticated(false);
	    }
		return $this->redirect("/");
	}
}
