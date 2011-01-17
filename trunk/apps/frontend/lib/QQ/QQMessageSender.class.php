<?php
class QQMessageSender {
	protected $consumerKey;
	protected $consumerSecret;
	protected $apiConsumer;
	
	public function __construct() {
		$this->consumerKey = sfConfig::get('app_qq_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_qq_consumer_secret');
	}
	
	public function beforeSend($profileId) {
		// get profile
		$profile = Doctrine::getTable('profile')->find($profileId);
		if (!$profile) return false;

		// check user
		$context = sfContext::getInstance();
		if($profile->getOwnerId() != $context->getUser()->getId()){
			return false;
		}
		
		$connectData = json_decode($profile->getConnectData(), true);
		
		$this->apiConsumer = new QQClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		return true;
	}
	
	public function sendMessage($profileId, $message) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		
		$response = $this->apiConsumer->update($message);
		
		return true;
	} 

	public function retweetMessage($profileId, $message, $target) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		
		$response = $this->apiConsumer->repost($target, $message);
		
		return true;
	}
	
	public function commentMessage($profileId, $message, $target) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		
		$response = $this->apiConsumer->send_comment($target, $message);
		
		return true;
	} 
}
