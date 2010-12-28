<?php

class homeAction extends sfAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_qq_consumer_key');
	    $consumer_secret = sfConfig::get('app_qq_consumer_secret');
		$profile_id = $request->getParameter('profile_id');
		$this->forward404Unless($profile_id);
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new QQClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$data  = $weibo->home_timeline();
		
		$messages = $this->formatMessages($data['data']['info']);
		return $this->renderText(json_encode($messages));
	}
	
	protected function formatMessages($originMessages) {
		$messages = array();
		foreach ($originMessages as $originMessage) {
			$message = new Message();
			$message->id = $originMessage['id'];
			$timestamp = $originMessage['timestamp'];
			$message->created_at = strftime('%b %d, %I:%M ', $timestamp) . strtolower(strftime('%p', $timestamp));
			$message->text = $originMessage['text'];
			$message->truncated = false; //TODO
			$message->source = $originMessage['from'];
			$user = $message->user;
			$user->id = $originMessage['name'];
			$user->name = $originMessage['name'];
			$user->screen_name = $originMessage['nick'];
			$user->avatar = $originMessage['head'] . '/40';
			$messages[] = $message;
		}
		return $messages;
	}
}
