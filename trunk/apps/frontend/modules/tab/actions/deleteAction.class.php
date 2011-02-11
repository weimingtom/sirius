<?php

class deleteAction extends sfAction {
	public function execute($request) {
		$this->forward404Unless($request->hasParameter("tabId"));

		$tabId = $request->getParameter("tabId");
		$tab = Doctrine_Core::getTable("Tab")->find($tabId);
		
		$this->forward404Unless($tab);
		$this->forward404Unless($tab->getOwnerId() == $this->getUser()->getId());

		$tab->delete();
		
		$user = Doctrine_Core::getTable("User")->find($this->getUser()->getId());
	    $tabsSettings = $user->getDashboardSettings();
	    $tabIds = $tabsSettings->getTabIds();
	    
	    $key = array_search($tabId, $tabIds);
	    if ($key !== FALSE) {
	    	unset($tabIds[$key]);
	    	$tabIds = array_values($tabIds);
	    }
	    
	    $tabsSettings->setTabIds($tabIds);
	    $tabsSettings->save();
		
				
		return $this->renderText(json_encode(array('result'=>'succeed')));
	}
}
