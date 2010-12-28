<?php

class authbackAction extends sfAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_qq_consumer_key');
	    $consumer_secret = sfConfig::get('app_qq_consumer_secret');
		
		$token = $this->getUser()->getAttribute("QQOAuthToken");
		$this->forward404Unless($token);
		
		$oauth_verifier = $request->getParameter("oauth_verifier");
		$this->forward404Unless($oauth_verifier);
		
		$to = new QQOAuth($consumer_key, $consumer_secret, $token['oauth_token'], $token['oauth_token_secret']);
		$tokens = $to->getAccessToken($oauth_verifier);
		$this->forward404Unless($token);
		//TODO: check return string
		
		$this->forward404Unless($this->getUser()->getId(), "User not found");

		// check fingerprint
		$checkResult = Doctrine::getTable('Profile')
        ->createQuery()
        ->where('profile.type = ? AND profile.ProfileName = ?', 'qq', $token['name'])
        ->fetchOne();
    $this->forward404If($checkResult);
		
		$weibo = new QQClient($consumer_key, $consumer_secret, $tokens['oauth_token'], $tokens['oauth_token_secret']);
		$user_profile = $weibo->show_user();

		$profile = new Profile();
		$profile->setOwnerId($this->getUser()->getId());
    $profile->setProfileName($token['name']);
		$profile->setScreenName($user_profile['data']['nick']);
		$profile->setType('qq');
		$profile->setAvatarUrl($user_profile['data']['head']);
		$profile->setConnectData(json_encode($tokens, true));
		$profile->save();
		
		return $this->renderText("{'result':'success'}");
	}
}
