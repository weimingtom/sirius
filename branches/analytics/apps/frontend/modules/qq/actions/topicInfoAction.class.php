<?php

class topicInfoAction extends QQAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// message id
		$topic = $request->getParameter('topic');
		$since_id = $request->getParameter('since_id');
		$before_id = $request->getParameter('before_id');
		$count = $request->getParameter('count', 20);
		
		$data  = QQCacheManager::getInstance()->trend_timeline($this->profileId, $this->apiConsumer, $since_id, $before_id, $count, $topic);

		$this->messages = $this->formatMessages($data);
	}
}
