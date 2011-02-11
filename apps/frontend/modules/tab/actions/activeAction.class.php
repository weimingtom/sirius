<?php

class activeAction extends sfAction {
  public function execute($request) {
    $this->forward404Unless($request->hasParameter("tabId"));

    $tabId = $request->getParameter("tabId");
    $tab = Doctrine_Core::getTable("Tab")->find($tabId);
    
    $this->forward404Unless($tab);
    $this->forward404Unless($tab->getOwnerId() == $this->getUser()->getId());
    
    // make it active!
    $user = Doctrine_Core::getTable("User")->find($this->getUser()->getId());
    $tabsSettings = $user->getDashboardSettings();
    $tabsSettings->setActiveTabId($tabId);
    $tabsSettings->save();
    
    $threads = Doctrine::getTable('Thread')
      ->createQuery()
      ->select("id, title, profile_id, profile_name, profile_type, type, parameters")
      ->where("tab_id = ?", $tabId)
	  ->orderBy('updated_at')
      ->fetchArray();
	  
	$threadsOrder = json_decode($tab->getThreadIds(), true);

	$threadsResult = array();
	for ($i = 0; $i < count($threadsOrder); ++$i) {
		$threadId = $threadsOrder[$i];
		foreach ($threads as $j => $thread) {
			if ($thread['id'] == $threadId) {
				$threadsResult[] = $thread;
				unset($threads[$j]);
				break;
			}
		}
	}

	if (count($threads)) {
		$threadsResult = array_merge($threadsResult, array_values($threads));
		$threadsOrder = array();
		for ($i = 0; $i < count($threadsResult); ++$i) {
			$threadsOrder[] = $threadsResult[$i]['id'];
		}
		$tab->setThreadIds(json_encode($threadsOrder));
		$tab->save();
	}
	
    return $this->renderText(json_encode($threadsResult));
  }
}
