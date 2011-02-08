<?php

class renameAction extends sfAction {
	public function execute($request) {
		$this->forward404Unless($request->hasParameter("tabId"));

		$tabId = $request->getParameter("tabId");
		$tab = Doctrine_Core::getTable("Tab")->find($tabId);
		
		$this->forward404Unless($tab);
		$this->forward404Unless($tab->getOwnerId() == $this->getUser()->getId());
				
		$tabName = $request->getParameter("title", "未命名");
		$tab->setTitle(trim($tabName));
		$tab->save();

		return $this->renderText(json_encode(array('tabId'=>$tabId, 'title'=>$tabName)));
	}
}
