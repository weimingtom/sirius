<?php

class messageInfoAction extends QQAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// message id
		$messageId = $request->getParameter('id');
		$this->forward404Unless($messageId);
				
		$data = $this->apiConsumer->show_status($messageId);
		$this->message = $this->formatMessage($data['data']);
		
		return sfView::SUCCESS;
	}
}