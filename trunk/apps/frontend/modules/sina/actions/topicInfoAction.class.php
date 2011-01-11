<?php

class topicInfoAction extends sinaAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_sina_consumer_key');
	    $consumer_secret = sfConfig::get('app_sina_consumer_secret');
		
		$profile_id = $request->getParameter('profileId');
		$this->profileId = $profile_id;
		$this->forward404Unless($profile_id);
		
		// user
		$topic = $request->getParameter('topic');
		$this->forward404Unless($topic);
		
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new WeiboClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		
		$data = $weibo->trend_timeline($topic);
		$this->messages = $this->formatMessages($data);
	}
}