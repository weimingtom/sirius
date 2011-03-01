<?php

class topicInfoAction extends sohuAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		// user
		$topic = $request->getParameter('topic');
		$this->forward404Unless($topic);
		
		$data = $this->apiConsumer->trend_timeline($topic);var_dump($data);die();
		$this->messages = $this->formatMessages($data);
	}
}