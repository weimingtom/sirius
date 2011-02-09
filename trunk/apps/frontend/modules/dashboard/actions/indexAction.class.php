<?php

class indexAction extends sfAction {
	public function execute($request) {
		$userId = $this->getUser()->getId();
		
		$this->username = $this->getUser()->getName();
		
		$this->profiles = Doctrine::getTable('Profile')
			->createQuery('')
			->select("id, type, screen_name, avatar_url, profile_name")
			->where("owner_id = ?", $userId)
			->fetchArray();
		
		// tabs
		$user = Doctrine_Core::getTable("User")->find($this->getUser()->getId());
	    $tabsSettings = $user->getTabsSettings();
	    $tabsOrder = $tabsSettings->getTabIds();
	    $tabs = Doctrine::getTable('Tab')
	      ->createQuery()
	      ->select("id, title")
	      ->where("owner_id = ?", $userId)
	      ->fetchArray();	    
	    $tabRes = array();
	    foreach ($tabsOrder as $tabId) {
	    	foreach ($tabs as $key => $tab) {
	    		if ($tab['id'] == $tabId) {
	    			$tabRes[] = $tab;
	    			unset($tabs[$key]);
	    			break;
	    		}
	    	}
	    }
	    if (!empty($tabs)) {
			$tabRes = array_values(array_merge($tabRes, $tabs));
			foreach ($tabs as $tab) {
				$tabsOrder[] = $tab['id'];
			}
			$tabSettings->setTabIds($tabsOrder);
			$tabSettings->save();
	    }
		$this->tabs = $tabRes;
		
		//active tab
		$activeTabId = $tabsSettings->getActiveTabId();
		if (!$activeTabId || array_search($activeTabId, $tabsOrder) === FALSE) {
			$activeTabId = $tabRes[0]['id'];
			$tabSettings->setActiveTabId($activeTabId);
			$tabSettings->save();
		}
		$this->activeTabId = $activeTabId;
	}      
}
