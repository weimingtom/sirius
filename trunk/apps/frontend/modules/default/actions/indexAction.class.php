<?php
class indexAction extends sfAction {
	public function execute($request) {
		return $this->redirect('/login');
	}
}
