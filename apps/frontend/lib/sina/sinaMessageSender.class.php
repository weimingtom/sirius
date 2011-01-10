<?php

class sinaMessageSender {
	public function sendMessage($profileId, $message) {
		$consumer_key = sfConfig::get('app_sina_consumer_key');
	    $consumer_secret = sfConfig::get('app_sina_consumer_secret');
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profileId);
		if (!$profile) return false;

		// check user
		$context = sfContext::getInstance();
		if($profile->getOwnerId() != $context->getUser()->getId()){
			return false;
		}
		
		$connectData = json_decode($profile->getConnectData(), true);
		
		$weibo = new WeiboClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$response = $weibo->update($message);
		
		return true;
	} 
}
