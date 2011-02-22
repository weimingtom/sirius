<?php

class fanfouMessageSender {
	protected $consumerKey;
	protected $consumerSecret;
	protected $apiConsumer;
	
	public function __construct() {
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
		$this->apiConsumer = new fanfouClient($connectData['username'], base64_decode($connectData['password']));
		
		return true;
	}
	
	public function sendMessage($profileId, $message, $image) {
		if (!$this->beforeSend($profileId)) {
			return false;
		}
		
		if ($image) {
			$imageFilePath = sfConfig::get('sf_upload_dir') . DIRECTORY_SEPARATOR . $this->getFileName($image);
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
			$_response = $this->apiConsumer->repost($target, $message);
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
