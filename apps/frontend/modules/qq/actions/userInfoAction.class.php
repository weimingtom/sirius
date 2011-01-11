<?php

class userInfoAction extends QQAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_qq_consumer_key');
	    $consumer_secret = sfConfig::get('app_qq_consumer_secret');
		
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
		$weibo = new QQClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$user = $weibo->show_user($username);
		$this->forward404Unless($user['ret'] == 0);
		
		$user = $user["data"];
		
		if ($user['head'] == '') {
			$user['head'] = $this->getEmptyAvatar(120);
		} else {
			$user['head'] .= '/120';
		}

		
		if (strlen($user['url']) <= 10) {
			$user['url'] = "";
		}
		$this->userData = $user;
	}
}
