<?php

abstract class sohuAction extends myAction {
	public function preExecute() {
		parent::preExecute();
		
		$this->consumerKey = sfConfig::get('app_sohu_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_sohu_consumer_secret');
		$this->callbackUrl = "http://" . $_SERVER['HTTP_HOST'] . sfConfig::get('app_sohu_callback_url');
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
		$this->apiConsumer = new SohuClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		
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
				
		if (isset($origin['small_pic']) && strlen($origin['small_pic'])) {
			$message->picture_thumbnail = $origin['small_pic'];
			$message->picture_medium = $origin['middle_pic'];
			$message->picture_original = $origin['middle_pic'];
		}
		
		if (isset($origin['in_reply_to_status_id']) && $origin['in_reply_to_status_id'] !== "") {
			$message->retweet_origin = new Message();
			$message->retweet_origin->id = $origin['in_reply_to_status_id'];
			$message->retweet_origin->text = $origin['in_reply_to_status_text'];
			$message->retweet_origin->user = $message->user;
			$message->retweet_origin->user->id = $origin['in_reply_to_user_id'];
			$message->retweet_origin->user->screen_name = $origin['in_reply_to_screen_name'];
		}
		
		$user = $message->user;
		$user->id = $origin['user']['id'];
		$user->name = $origin['user']['id'];
		$user->screen_name = $origin['user']['screen_name'];
		$user->avatar = $origin['user']['profile_image_url'];
		
		return $message;
	}
	
	protected function formatDirectMessage($origin) {
		$message = new Message();		
		$message->id = $origin['id'];
		
		$message->created_at = $this->formatTime($origin['created_at']);
		
		$message->text = $this->formatText($origin['text']);
		$message->source = '';
				
		$user = $message->user;
		$user->id = $origin['sender']['id'];
		$user->name = $origin['sender']['id'];
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
		foreach (sohuCacheManager::getInstance()->getEmotions() as $motion) {
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
			$newData[$item['id']] = array('comments'=>$item['comments_count'], 'rt'=>$item['transmit_count']);
		}
		
		return $newData;
	}
}
