<?php

class directAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		$since_id = $request->getParameter('since_id');
		$data  = $this->apiConsumer->list_dm($since_id);

		$messages = $this->formatMessages($data, true);
		return $this->renderText(json_encode($messages));
	}
}
