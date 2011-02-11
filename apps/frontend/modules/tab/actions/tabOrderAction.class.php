<?php

class tabOrderAction extends sfAction {
	public function execute($request) {
		$user = Doctrine_Core::getTable("User")->find($this->getUser()->getId());
	    $tabsSettings = $user->getDashboardSettings();
  	    $tabIds = $request->getParameter("tab_ids");
		$tabsSettings->setTabIds($tabIds);
		$tabsSettings->save();

    	return $this->renderText("{}");
	}
}
