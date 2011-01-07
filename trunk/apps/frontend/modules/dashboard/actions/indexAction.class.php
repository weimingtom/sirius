<?php

class indexAction extends sfAction {
	public function execute($request) {
		$userId = $this->getUser()->getId();
		
		$this->profiles = Doctrine::getTable('Profile')
			->createQuery('')
			->select("id, type, screen_name")
			->where("owner_id = ?", $userId)
			->fetchArray();
      
	    $this->tabs = Doctrine::getTable('Tab')
	      ->createQuery()
	      ->select("id, title")
	      ->where("owner_id = ?", $userId)
	      ->fetchArray();
  }      
}
