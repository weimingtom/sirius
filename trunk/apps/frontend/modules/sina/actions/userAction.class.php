<?php

class userAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		// user name
		$username = $request->getParameter('name');
		$this->forward404Unless($username);

		$since_id = $request->getParameter('since_id');
		$data  = $this->apiConsumer->user_timeline($username, $since_id);

		$messages = $this->formatMessages($data);
		
		if ($request->hasParameter('format') && $request->getParameter('format') == 'html') {
			return $this->renderPartial('thread/messages', array('messages'=>$messages));
		}
		return $this->renderText(json_encode($messages));
	}
}
