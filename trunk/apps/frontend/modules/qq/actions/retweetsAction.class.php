<?php

class retweetsAction extends QQAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// message id
		$messageId = $request->getParameter('id');
		$since_id = $request->getParameter('since_id');
		$before_id = $request->getParameter('before_id');
		$count = $request->getParameter('count', 20);
		
		$data  = QQCacheManager::getInstance()->get_reposts_by_sid($this->profileId, $this->apiConsumer, $since_id, $before_id, $count, $messageId);
		
		$messages = $this->formatMessages($data);
		for ($i = 0; $i < count($messages); ++$i) {
			$messages[$i]->retweet_origin = null;
		}
		
		if ($request->hasParameter('format') && $request->getParameter('format') == 'html') {
			return $this->renderPartial('global/messages', 
				array(
					'messages'		=> $messages, 
					'profileId'		=> $this->profileId, 
					'profileType' 	=> 'qq',
					'threadType'	=> 'retweets',
					'otherParams'	=> json_encode(array('id'=>$messageId)),
					'loadMore'		=> true
				)
			);
		}
		return $this->renderText(json_encode($messages));
	}
}