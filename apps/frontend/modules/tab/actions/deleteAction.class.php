<?php

class deleteAction extends sfAction {
	public function execute($request) {
		$this->forward404Unless($request->hasParameter("tabId"));

		$tabId = $request->getParameter("tabId");
		$tab = Doctrine_Core::getTable("Tab")->find($tabId);
		
		$this->forward404Unless($tab);
		$this->forward404Unless($tab->getOwnerId() == $this->getUser()->getId());

		$tab->delete();
				
		return $this->renderText(json_encode(array('result'=>'succeed')));
	}
}
