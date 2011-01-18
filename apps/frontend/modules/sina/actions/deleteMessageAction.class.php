<?php

class deleteMessageAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// message id
		$messageId = $request->getParameter('id');
		if ($messageId == null) {
			return $this->renderText('{error: "miss parameter: id"}');
		}
		
		$res = $this->apiConsumer->delete($messageId);

		if ($res === false || $res === null || isset($res['error_code'])) {
			return $this->renderText('{error: "delete failure"}');
		}
		return $this->renderText('{}');
	}
}