<?php

class postedAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		$since_id = $request->getParameter('since_id');
		$data  = $this->apiConsumer->user_timeline($since_id);

		$messages = $this->formatMessages($data);
		return $this->renderText(json_encode($messages));
	}
}
