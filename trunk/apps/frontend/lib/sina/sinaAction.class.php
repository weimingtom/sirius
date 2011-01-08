<?php

abstract class sinaAction extends sfAction {	
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
		
		$message->text = $origin['text'];
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
		
		$message->text = $origin['text'];
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
}
