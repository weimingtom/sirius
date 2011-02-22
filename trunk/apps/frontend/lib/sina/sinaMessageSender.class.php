<?php

class sinaMessageSender {
	protected $consumerKey;
	protected $consumerSecret;
	protected $apiConsumer;
	
	public function __construct() {
		$this->consumerKey = sfConfig::get('app_sina_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_sina_consumer_secret');
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
		
		$this->apiConsumer = new WeiboClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		return true;
	}
	
	public function sendMessage($profileId, $message, $image) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		
		if ($image) {
			$imageFilePath = sfConfig::get('sf_upload_dir') . "/" . $this->getFileName($image);
			$response = $this->apiConsumer->upload($message, $imageFilePath);
		} else {
			$response = $this->apiConsumer->update($message);
		}

		return true;
	} 

	public function retweetMessage($profileId, $message, $target, $sourceContent = null, $sourceAuthor = null) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		
		if ($sourceContent != null) {
			$message .= ' 转: @' . $sourceAuthor . ' ' . $sourceContent;
			$response = $this->apiConsumer->update($message);
		} else {
			$response = $this->apiConsumer->repost($target, $message);
		}

		return true;
	}
	
	public function commentMessage($profileId, $message, $target, $sourceContent = null, $sourceAuthor = null) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		
		if ($sourceContent != null) {
			$message .= ' @' . $sourceAuthor . ' ' . $sourceContent;
			$response = $this->apiConsumer->update($message);
		} else {
			$response = $this->apiConsumer->send_comment($target, $message);
		}
		
		return true;
	}
	
	private function getFileName($filePath) {
		return end(explode("/", $filePath));
	}
}
