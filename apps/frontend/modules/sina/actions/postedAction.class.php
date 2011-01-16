<?php

class postedAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		$since_id = $request->getParameter('since_id');
		$before_id = $request->getParameter('before_id');
		$count = $request->getParameter('count', 20);
		$data  = sinaCacheManager::getInstance()->user_timeline($this->profileId, $this->apiConsumer, $since_id, $before_id, $count);
		
		$messages = $this->formatMessages($data);
		return $this->renderText(json_encode($messages));
	}
}
