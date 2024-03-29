<?php

class retweetsAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// message id
		$messageId = $request->getParameter('id');
		$this->forward404Unless($messageId);
		
		$data = $this->apiConsumer->get_reposts_by_sid($messageId);
		
		$messages = $this->formatMessages($data);
		for ($i = 0; $i < count($messages); ++$i) {
			$messages[$i]->retweet_origin = null;
		}
		
		if ($request->hasParameter('format') && $request->getParameter('format') == 'html') {
			return $this->renderPartial('global/messages', 
				array(
					'messages'		=> $messages, 
					'profileId'		=> $this->profileId, 
					'profileType' 	=> 'sina',
					'threadType'	=> 'retweets',
					'otherParams'	=> json_encode(array('id'=>$messageId))
				)
			);
		}
		return $this->renderText(json_encode($messages));
	}
}