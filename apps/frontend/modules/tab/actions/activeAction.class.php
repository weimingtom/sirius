<?php

class activeAction extends sfAction {
  public function execute($request) {
    $this->forward404Unless($request->hasParameter("tabId"));

    $tabId = $request->getParameter("tabId");
    $tab = Doctrine_Core::getTable("Tab")->find($tabId);
    
    $this->forward404Unless($tab);
    $this->forward404Unless($tab->getOwnerId() == $this->getUser()->getId());
    
    //TODO: make it active!
    
    $threads = Doctrine::getTable('Thread')
      ->createQuery()
      ->select("id, title, profile_id, profile_name, profile_type, type, parameters")
      ->where("tab_id = ?", $tabId)
      ->fetchArray();

    return $this->renderText(json_encode($threads));
  }
}
