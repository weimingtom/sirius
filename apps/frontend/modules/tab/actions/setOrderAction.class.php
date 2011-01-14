<?php

class setOrderAction extends sfAction {
  public function execute($request) {
    $this->forward404Unless($request->hasParameter("tab_id"));

    $tabId = $request->getParameter("tab_id");
    $tab = Doctrine_Core::getTable("Tab")->find($tabId);
    
    $this->forward404Unless($tab);
    $this->forward404Unless($tab->getOwnerId() == $this->getUser()->getId());
    
    $threadIds = $request->getParameter("thread_ids");
	$tab->setThreadIds(json_encode($threadIds));
	$tab->save();
	
    return $this->renderText("{}");
  }
}
