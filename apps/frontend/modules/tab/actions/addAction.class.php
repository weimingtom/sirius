<?php

class addAction extends sfAction {
	public function execute($request) {
		$tabName = $request->getParameter("title", "untitled");
		
		$tab = new Tab();
		$tab->setOwnerId($this->getUser()->getId());
		$tab->setTitle($tabName);
		$tab->save();
		
		$tabId = $tab->getId();

		return $this->renderText(json_encode(array('tabId'=>$tabId)));
	}
}
