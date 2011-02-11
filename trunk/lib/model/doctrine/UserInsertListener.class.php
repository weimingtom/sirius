<?php

class UserInsertListener extends Doctrine_Record_Listener {
	public function postInsert(Doctrine_Event $event) {
		$invoker = $event->getInvoker();
		$userId = $invoker->getId();
		
		// add default tab
		$tab = new Tab();
		$tab->setOwnerId($userId);
		$tab->setTitle('默认');
		$tab->save();	
		
		// add tabs setting
		$tabsSettings = new DashboardSettings();
		$tabsSettings->setOwnerId($userId);
		$tabsSettings->setTabIds(array($tab->getId()));
		$tabsSettings->setActiveTabId($tab->getId());
		$tabsSettings->save();	
	}
}