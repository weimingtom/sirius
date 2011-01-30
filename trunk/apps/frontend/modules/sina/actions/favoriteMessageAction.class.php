<?php
class favoriteMessageAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		$messageId = $request->getParameter('id');
		$action = $request->getParameter('do');
		
		if ($messageId == null) {
			return $this->renderText("{error:'no id'}");
		}

		switch ($action) {
			case 'favorite':
				$message = $this->apiConsumer->add_to_favorites($messageId);
				break;
			case 'unfavorite':
				$message = $this->apiConsumer->remove_from_favorites($messageId);
				break;
			default:
				return $this->renderText("{error:'wrong action'}");
		}
		
		if ($message === false || $message === null ||
			(isset($message['error_code']) && isset($message['error']))) {
			return $this->renderText("{error:'failed'}");	
		}
				
		sinaCacheManager::getInstance()->cacheMessages($message);
		
		return $this->renderText('{}');
	}
}