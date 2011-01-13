<?php

class mentionsAction extends QQAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		$data  = $this->apiConsumer->home_timeline();
		
		$data = $data['data']['info'];
		if ($request->hasParameter('since_id')) {
			$sinceId = $request->getParameter('since_id');
			foreach ($data as $key => $value) {
				if ($value['id'] == $sinceId) {
					$data = array_slice($data, 0, $key);
				}
			}
		}		
		
		$messages = $this->formatMessages($data);
		return $this->renderText(json_encode($messages));
	}
}
