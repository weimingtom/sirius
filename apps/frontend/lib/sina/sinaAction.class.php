<?php

abstract class sinaAction extends myAction {
	public function preExecute() {
		parent::preExecute();
		
		$this->consumerKey = sfConfig::get('app_sina_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_sina_consumer_secret');
		$this->callbackUrl = "http://" . $_SERVER['HTTP_HOST'] . sfConfig::get('app_sina_callback_url');
	}
	
	protected function prepareApiConsumer($request) {
		if (!$request->hasParameter('profile_id')) {
			return false;
		}		
		$this->profileId = $request->getParameter('profile_id');
		
		// get profile
		$this->profile = Doctrine::getTable('profile')->find($this->profileId);
		if (!$this->profile) {
			return false;
		}
		
		// check user
		if ($this->profile->getOwnerId() != $this->getUser()->getId()) {
			return false;
		}
		
		$connectData = json_decode($this->profile->getConnectData(), true);
		$this->apiConsumer = new WeiboClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		
		return $this->apiConsumer;			
	}
		
	protected function formatMessages($originMessages, $isDM = false) {
		$messages = array();
		if (!$isDM) {
			$originMessages = $this->fillMessageCommentsAndRetweetCount($originMessages);
		}
		foreach ($originMessages as $originMessage) {
			if ($isDM) {
				$messages[] = $this->formatDirectMessage($originMessage);
			} else {
				$messages[] = $this->formatMessage($originMessage);
			}
		}
		return $messages;
	}
	
	protected function formatMessage($origin) {
		$message = new Message();
		$message->id = $origin['id'];
		
		$message->created_at = $this->formatTime($origin['created_at']);
		
		$message->text = $this->formatText($origin['text']);
		$message->truncated = $origin['truncated'];
		$message->favorited = $origin['favorited'];
		$message->source = $origin['source'];
		
		if (isset($origin['comments'])) {
			$message->commentCount = $origin['comments'];
		}
		
		if (isset($origin['rt'])) {
			$message->retweetCount = $origin['rt'];
		}
				
		if (isset($origin['thumbnail_pic']) && strlen($origin['thumbnail_pic'])) {
			$message->picture_thumbnail = $origin['thumbnail_pic'];
			$message->picture_medium = $origin['bmiddle_pic'];
			$message->picture_original = $origin['original_pic'];
		}
		
		if (isset($origin['retweeted_status']) && is_array($origin['retweeted_status'])) {
			$message->retweet_origin = $this->formatMessage($origin['retweeted_status']);
		}
		
		$user = $message->user;
		$user->id = $origin['user']['id'];
		$user->name = $origin['user']['name'];
		$user->screen_name = $origin['user']['screen_name'];
		$user->avatar = $origin['user']['profile_image_url'];
		
		return $message;
	}
	
	protected function formatDirectMessage($origin) {
		$message = new Message();		
		$message->id = $origin['id'];
		
		$message->created_at = $this->formatTime($origin['created_at']);
		
		$message->text = $this->formatText($origin['text']);
		$message->truncated = $origin['truncated'];
		$message->source = '';
		
		if (isset($origin['thumbnail_pic']) && strlen($origin['thumbnail_pic'])) {
			$message->picture_thumbnail = $origin['thumbnail_pic'];
			$message->picture_original = $origin['original_pic'];
		}
		
		if (isset($origin['retweeted_status']) && is_array($origin['retweeted_status'])) {
			$message->retweet_origin = $this->formatMessage($origin['retweeted_status']);
		}
		
		$user = $message->user;
		$user->id = $origin['sender']['id'];
		$user->name = $origin['sender']['name'];
		$user->screen_name = $origin['sender']['screen_name'];
		$user->avatar = $origin['sender']['profile_image_url'];
		
		return $message;
	}
	
	protected function formatText($text) {
		// relpace hyper link
		$text = preg_replace( "/ *(http:\/\/[a-zA-Z0-9\/\.-]*) ?/u", " <a href=\"\\1\" target=\"_blank\">\\1</a> ", $text);
		// replace #
		$text = preg_replace( "/ *#([\x{4e00}-\x{9fa5}A-Za-z0-9_\ ]*)# ?/u", " <a class=\"_topic_link\" href=\"#\" topic=\"\\1\">#\\1#</a> ", $text); 
		// replace user
		$text = preg_replace( "/ *@([\x{4e00}-\x{9fa5}A-Za-z0-9_]*) ?/u", " <a class=\"_user_link\" href=\"#\" user=\"\\1\">@\\1</a> ", $text);
		
		// replace emotions
		foreach (sinaCacheManager::getInstance()->getEmotions() as $motion) {
			$imgNode = '<img class="motion" src="' . $motion['url'] . '" />';
			$text = str_replace($motion['phrase'], $imgNode, $text);
		}
		return $text;
	}
	
	protected function fillMessageCommentsAndRetweetCount($data) {
		if ($data === false || $data === null || isset($data['error_code'])) {
			return false;
		}
		
		$messageIds = array();
		foreach ($data as $msg) {
			$messageIds[] = $msg['id'];
			if (isset($msg['retweeted_status']) && is_array($msg['retweeted_status'])) {
				$messageIds[] = $msg['retweeted_status']['id'];
			}
		}
		
		$countData = $this->getMessageCommentsAndRetweetCount($messageIds);
		
		for ($index = 0; $index < count($data); ++$index) {
			if (isset($countData[$data[$index]['id']])) {
				$data[$index]['comments'] = $countData[$data[$index]['id']]['comments'];
				$data[$index]['rt'] = $countData[$data[$index]['id']]['rt'];
			}
			if (isset($data[$index]['retweeted_status']) && is_array($data[$index]['retweeted_status'])) {
				if (isset($countData[$data[$index]['retweeted_status']['id']])) {
					$data[$index]['retweeted_status']['comments'] = $countData[$data[$index]['retweeted_status']['id']]['comments'];
					$data[$index]['retweeted_status']['rt'] = $countData[$data[$index]['retweeted_status']['id']]['rt'];
				}
			}
		}
		
		return $data;		
	}
	
	protected function getMessageCommentsAndRetweetCount($messageIdArray) {
		// check parameter	
		if ($messageIdArray == null) {
			return null;
		}
		
		// check apiConsumer
		if (!$this->apiConsumer) {
			return null;
		}
		
		if (is_array($messageIdArray)) {
			$messageIds = implode(',', $messageIdArray);
		} else {
			$messageIds = $messageIdArray;
		}
		
		$data = $this->apiConsumer->get_count_info_by_ids($messageIds);
		
		$newData = array();
		foreach ($data as $item) {
			$newData[$item['id']] = array('comments'=>$item['comments'], 'rt'=>$item['rt']);
		}
		
		return $newData;
	}
}
