<?php
class doubanMessageSender {
	protected $consumerKey;
	protected $consumerSecret;
	protected $apiConsumer;
	
	public function __construct() {
		$this->consumerKey = sfConfig::get('app_douban_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_douban_consumer_secret');
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
		
		$this->apiConsumer = new doubanClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		return true;
	}
	
	public function sendMessage($profileId, $message, $image) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		$response = $this->apiConsumer->update($message);
		
		/*if ($image) {
			$imageFilePath = sfConfig::get('sf_upload_dir') . "/" . $this->getFileName($image);
			$response = $this->apiConsumer->upload($message, $imageFilePath);
		} else {
			$response = $this->apiConsumer->update($message);
		}*/

		return true;
	} 

	public function retweetMessage($profileId, $message, $target, $sourceContent = null, $sourceAuthor = null) {
		if ($sourceContent != null) {
			$message .= ' 转: @' . $sourceAuthor . ' ' . $sourceContent;
		}
		return $this->sendMessage($profileId, $message, null);
	}
	
	public function commentMessage($profileId, $message, $target, $sourceContent = null, $sourceAuthor = null) {
		if ($sourceContent != null) {
			$message .= ' @' . $sourceAuthor . ' ' . $sourceContent;
		}
		return $this->sendMessage($profileId, $message, null);
	}
	
	private function getFileName($filePath) {
		return end(explode("/", $filePath));
	}
	
}
