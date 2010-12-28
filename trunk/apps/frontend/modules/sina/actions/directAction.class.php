<?php

class directAction extends sfAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_sina_consumer_key');
	    $consumer_secret = sfConfig::get('app_sina_consumer_secret');
		$profile_id = $request->getParameter('profile_id');
		$this->forward404Unless($profile_id);
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new WeiboClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$since_id = $request->getParameter('since_id');
		$data  = $weibo->list_dm($since_id);

		$messages = $this->formatMessages($data);
		return $this->renderText(json_encode($messages));
	}
	
	protected function formatMessages($originMessages) {
		$messages = array();
		foreach ($originMessages as $originMessage) {
			$message = new Message();
			$message->id = $originMessage['id'];
			$timestamp = strtotime($originMessage['created_at']);
			$message->created_at = strftime('%b %d, %I:%M ', $timestamp) . strtolower(strftime('%p', $timestamp));
			$message->text = $originMessage['text'];
			$message->truncated = $originMessage['truncated'];
			$message->source = $originMessage['source'];
			$user = $message->user;
			$user->id = $originMessage['user']['id'];
			$user->name = $originMessage['user']['name'];
			$user->screen_name = $originMessage['user']['screen_name'];
			$user->avatar = $originMessage['user']['profile_image_url'];
			$messages[] = $message;
		}
		return $messages;
	}
}
