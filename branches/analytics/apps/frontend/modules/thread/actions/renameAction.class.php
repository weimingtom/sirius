<?php

class renameAction extends sfAction {
	public function execute($request) {
		$this->forward404Unless($request->hasParameter("threadId"));

		$threadId = $request->getParameter("threadId");
		$thread = Doctrine_Core::getTable("Thread")->find($threadId);
		
		$this->forward404Unless($thread);
		$this->forward404Unless($thread->getOwnerId() == $this->getUser()->getId());
				
		$threadName = $request->getParameter("title", "untitled");
		$thread->setTitle($threadName);
		$thread->save();
		
		return $this->renderText(json_encode(array('threadId'=>$threadId, 'threadName'=>$threadName)));
	}
}
