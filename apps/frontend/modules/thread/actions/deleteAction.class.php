<?php

class deleteAction extends sfAction {
	public function execute($request) {
		$this->forward404Unless($request->hasParameter("threadId"));

		$threadId = $request->getParameter("threadId");
		$thread = Doctrine_Core::getTable("Thread")->find($threadId);
		
		$this->forward404Unless($thread);
		$this->forward404Unless($thread->getOwnerId() == $this->getUser()->getId());
				
		$thread->delete();
				
		return $this->renderText(json_encode(array('result'=>'succeed')));
	}
}
