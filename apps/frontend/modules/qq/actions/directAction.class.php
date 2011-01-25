<?php

class directAction extends QQAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		$since_id = $request->getParameter('since_id');		
		$before_id = $request->getParameter('before_id');
		$count = $request->getParameter('count', 20);
		$data  = QQCacheManager::getInstance()->list_dm($this->profileId, $this->apiConsumer, $since_id, $before_id, $count);

		$messages = $this->formatMessages($data, true);
		return $this->renderText(json_encode($messages));
	}
}
