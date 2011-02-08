<?php

class addAction extends sfAction {
	public function execute($request) {
		$tabName = $request->getParameter("title", "未命名");
		
		$tab = new Tab();
		$tab->setOwnerId($this->getUser()->getId());
		$tab->setTitle($tabName);
		$tab->save();
		
		$tabId = $tab->getId();
		$tab = array(
			"tabId" => $tab->getId(),
			"title" => $tab->getTitle()
		);

		return $this->renderText(json_encode($tab));
	}
}
