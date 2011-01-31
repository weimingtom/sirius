<?php

class userAction extends fanfouAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));

		// user name
		$username = $request->getParameter('name');
		$this->forward404Unless($username);

		$since_id = $request->getParameter('since_id');
		$before_id = $request->getParameter('before_id');
		$count = $request->getParameter('count', 20);
		
		$data  = fanfouCacheManager::getInstance()->user_timeline($this->profileId, $this->apiConsumer, $since_id, $before_id, $count, $username);

		$messages = $this->formatMessages($data);
		
		if ($request->hasParameter('format') && $request->getParameter('format') == 'html') {
			return $this->renderPartial('global/messages', 
				array(
					'messages'		=> $messages, 
					'profileId'		=> $this->profileId, 
					'profileType' 	=> 'fanfou',
					'threadType'	=> 'user',
					'otherParams'	=> json_encode(array('name'=>$username)),
					'loadMore'		=> true
				)
			);
		}
		return $this->renderText(json_encode($messages));
	}
}
