<?php
abstract class fanfouAction extends myAction {
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
		$this->apiConsumer = new fanfouClient($connectData['username'], base64_decode($connectData['password']));

		return $this->apiConsumer;			
	}
	
	protected function formatMessages($originMessages, $isDM = false) {
		$messages = array();
		if (!$isDM) {
			//$originMessages = $this->fillMessageCommentsAndRetweetCount($originMessages);
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
	
	protected function formatDirectMessage($origin) {
		$message = new Message();
		$message->id = $origin['id'];
		
		$message->created_at = $this->formatTime($origin['created_at']);
		
		if (isset($origin['photo'])) {
			$message->picture_thumbnail = $origin['photo']['thumburl'];
			$message->picture_medium = $origin['photo']['largeurl'];
			$message->picture_original = $origin['photo']['largeurl'];
		}
		
		$message->text = $this->formatText($origin['text']);
		$message->truncated = $origin['truncated'];
		$message->favorited = $origin['favorited'];
		
		$user = $message->user;
		$user->id = $origin['sender']['id'];
		$user->name = $origin['sender']['id'];
		$user->screen_name = $origin['sender']['screen_name'];
		$user->avatar = $origin['sender']['profile_image_url'];
		
		return $message;
	}
	
	protected function formatMessage($origin) {
		$message = new Message();
		$message->id = $origin['id'];
		
		$message->created_at = $this->formatTime($origin['created_at']);
		
		if (isset($origin['photo'])) {
			$message->picture_thumbnail = $origin['photo']['thumburl'];
			$message->picture_medium = $origin['photo']['largeurl'];
			$message->picture_original = $origin['photo']['largeurl'];
		}
		
		$message->text = $this->formatText($origin['text']);
		$message->truncated = $origin['truncated'];
		$message->favorited = $origin['favorited'];
		$message->source = $origin['source'];
		
		$user = $message->user;
		$user->id = $origin['user']['id'];
		$user->name = $origin['user']['id'];
		$user->screen_name = $origin['user']['screen_name'];
		$user->avatar = $origin['user']['profile_image_url'];
		
		return $message;
	}
	
	protected function formatText($text) {
		// relpace hyper link
		//$text = preg_replace( "/ *(http:\/\/[a-zA-Z0-9\/\.-]*) ?/u", " <a href=\"\\1\" target=\"_blank\">\\1</a> ", $text);
		// replace #
		//$text = preg_replace( "/ *#([\x{4e00}-\x{9fa5}A-Za-z0-9_\ ]*)# ?/u", " <a class=\"_topic_link\" href=\"#\" topic=\"\\1\">#\\1#</a> ", $text); 
		// replace user
		$text = preg_replace( "/ *@<a href=\"http:\/\/fanfou.com\/([\x{4e00}-\x{9fa5}A-Za-z0-9_~]*)\" class=\"former\"> ?/u", "<a class=\"_user_link\" href=\"#\" user=\"\\1\">@", $text);
		
		return $text;
	}
	
}