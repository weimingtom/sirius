<?php

abstract class sinaAction extends sfAction {
	public function preExecute() {
		parent::preExecute();
		
		$this->consumerKey = sfConfig::get('app_sina_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_sina_consumer_secret');
		$this->callbackUrl = sfConfig::get('app_sina_callback_url');
		
	}
		
	protected function formatMessages($originMessages, $isDM = false) {
		$messages = array();
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
		
		$timestamp = strtotime($origin['created_at']);
		$message->created_at = strftime('%b %d, %I:%M ', $timestamp) . strtolower(strftime('%p', $timestamp));
		
		$message->text = $this->formatText($origin['text']);
		$message->truncated = $origin['truncated'];
		$message->source = $origin['source'];
		
		if (isset($origin['thumbnail_pic']) && strlen($origin['thumbnail_pic'])) {
			$message->picture_thumbnail = $origin['thumbnail_pic'];
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
		
		$timestamp = strtotime($origin['created_at']);
		$message->created_at = strftime('%b %d, %I:%M ', $timestamp) . strtolower(strftime('%p', $timestamp));
		
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
		$text = preg_replace( "/ *#([\x{4e00}-\x{9fa5}A-Za-z0-9_]*)# ?/u", " <a class=\"_topic_link\" href=\"#\" topic=\"\\1\">#\\1#</a> ", $text); 
		// replace user
		$text = preg_replace( "/ *@([\x{4e00}-\x{9fa5}A-Za-z0-9_]*) ?/u", " <a class=\"_user_link\" href=\"#\" user=\"\\1\">@\\1</a> ", $text);
		return $text;
	}
	
	protected function getMessageCommentsAndRetweetCount($messageIdArray) {
		if ($messageIdArray == null) {
			return null;
		}
		if (is_array()) {
			$messageIds = implode(',', $messageIdArray);
		} else {
			$messageIds = $messageIdArray;
		}
		
		
	}
}
