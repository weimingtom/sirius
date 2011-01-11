<?php

class userInfoAction extends sinaAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_sina_consumer_key');
	    $consumer_secret = sfConfig::get('app_sina_consumer_secret');
		
		$profile_id = $request->getParameter('profileId');
		$this->forward404Unless($profile_id);
		
		// user
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new WeiboClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$user = $weibo->show_user($username);
		
		$user['profile_image_url_180'] = str_replace('/50/', '/180/', $user['profile_image_url']);
		if ($user['domain'] == '') {
			$user['domain'] = $user['id'];
		}
		$this->userData = $user;
		
//		var_dump($user);die();
	}
}
