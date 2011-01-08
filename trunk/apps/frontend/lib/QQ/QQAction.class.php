<?php
abstract class QQAction extends sfAction {
	
	protected function formatMessages($originMessages, $isDM = false) {
		//var_dump($originMessages);die();
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
		
		$timestamp = $origin['timestamp'];
		$message->created_at = strftime('%b %d, %I:%M ', $timestamp) . strtolower(strftime('%p', $timestamp));
		
		$message->text = $origin['text'];
		$message->truncated = false; //TODO
		$message->source = $origin['from'];
		
		if ($origin['image'] != null && is_array($origin['image']) && count($origin['image']) > 0) {
			$message->picture_thumbnail = $origin['image'][0] . '/160';
			$message->picture_original = $origin['image'][0] . '/2000';
		}
		
		if ($origin['type'] == 2) {
			$message->retweet_origin = $this->formatMessage($origin['source']);
		}
		
		$user = $message->user;
		$user->id = $origin['name'];
		$user->name = $origin['name'];
		$user->screen_name = $origin['nick'];
		if (strlen($origin['head']) > 0) {
			$user->avatar = $origin['head'] . '/40';
		} else {
			$user->avatar = $this->getEmptyAvatar();
		}
		
		return $message;
	}
	
	protected function formatDirectMessage($origin) {
		$message = new Message();
		$message->id = $origin['id'];
		
		$timestamp = $origin['timestamp'];
		$message->created_at = strftime('%b %d, %I:%M ', $timestamp) . strtolower(strftime('%p', $timestamp));
		
		$message->text = $origin['text'];
		$message->truncated = false; //TODO
		$message->source = $origin['from'];
		
		if ($origin['image'] != null && is_array($origin['image']) && count($origin['image']) > 0) {
			$message->picture_thumbnail = $origin['image'][0] . '/160';
			$message->picture_original = $origin['image'][0] . '/2000';
		}
		
		if ($origin['type'] == 2) {
			$message->retweet_origin = $this->formatMessage($origin['source']);
		}
		
		$user = $message->user;
		$user->id = $origin['name'];
		$user->name = $origin['name'];
		$user->screen_name = $origin['nick'];
		if (strlen($origin['head']) > 0) {
			$user->avatar = $origin['head'] . '/40';
		} else {
			$user->avatar = $this->getEmptyAvatar();
		}
		
		return $message;
	}
	
	private function getEmptyAvatar() {
		return "http://mat1.gtimg.com/www/mb/images/head_50.jpg";
	}
}
