<?php

class testAction extends QQAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		
		$since_id = $request->getParameter('since_id');
		$before_id = $request->getParameter('before_id');
		$count = $request->getParameter('count', 20);
		
		$data  = QQCacheManager::getInstance()->home_timeline($this->profileId, $this->apiConsumer, $since_id, $before_id, $count);
		foreach ($data as $message) {
			echo $message['id'] . ' ' . $message['timestamp'] . "</br>\n";
		}die();
		
		
		
		$count = $request->getParameter('count');
		$pageflag = $request->getParameter('pageflag');
		$pagetime = $request->getParameter('pagetime');
		
		$data  = $this->apiConsumer->home_timeline($count, $pageflag, $pagetime	);
		
		$data = $data['data']['info'];
		if ($request->hasParameter('since_id')) {
			$sinceId = $request->getParameter('since_id');
			foreach ($data as $key => $value) {
				if ($value['id'] == $sinceId) {
					$data = array_slice($data, 0, $key);
				}
			}
		}		
		foreach ($data as $message) {
			echo $message['id'] . ' ' . $message['timestamp'] . "</br>\n";
		}die();
		$messages = $this->formatMessages($data);
		return $this->renderText(json_encode($messages));
	}
}
