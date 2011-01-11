<?php

class userAction extends QQAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_qq_consumer_key');
	    $consumer_secret = sfConfig::get('app_qq_consumer_secret');
		$profile_id = $request->getParameter('profile_id');
		$this->forward404Unless($profile_id);
		
		// user name
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new QQClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		
		$data  = $weibo->user_timeline($username);
		$messages = $this->formatMessages($data['data']['info']);

		if ($request->hasParameter('format') && $request->getParameter('format') == 'html') {
			return $this->renderPartial('thread/messages', array('messages'=>$messages));
		}
		return $this->renderText(json_encode($messages));
	}
}
