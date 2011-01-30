<?php

class authbackAction extends QQAction {
	public function execute($request) {
		$token = $this->getUser()->getAttribute("QQOAuthToken");
		$this->forward404Unless($token);
		
		$oauth_verifier = $request->getParameter("oauth_verifier");
		$this->forward404Unless($oauth_verifier);

		$to = new QQOAuth($this->consumerKey, $this->consumerSecret, $token['oauth_token'], $token['oauth_token_secret']);
		$tokens = $to->getAccessToken($oauth_verifier);
		$this->forward404Unless($tokens);
		//TODO: check return string
		$this->forward404Unless(count($tokens) > 1);
		
		$this->forward404Unless($this->getUser()->getId(), "User not found");

		// check fingerprint
		$checkResult = Doctrine::getTable('Profile')
        ->createQuery()
        ->where('type = ? AND profile_name = ?', array('qq', $tokens['name']))
        ->fetchOne();
		if ($checkResult) {
			if ($checkResult->getOwnerId() == $this->getUser()->getId()) {
				$this->errorMsg = '您已经添加了该帐号,无法重复添加。';
			} else {
				$this->errorMsg = '该帐号已被其他MixMes用户添加';
			}			
			return sfView::ERROR;
		}
		
		$weibo = new QQClient($this->consumerKey, $this->consumerSecret, $tokens['oauth_token'], $tokens['oauth_token_secret']);
		$user_profile = $weibo->show_user();

		$profile = new Profile();
		$profile->setOwnerId($this->getUser()->getId());
    	$profile->setProfileName($tokens['name']);
		$profile->setScreenName($user_profile['data']['nick']);
		$profile->setType('qq');
		
		if ($user_profile['data']['head'] != "") {
			$profile->setAvatarUrl($user_profile['data']['head'] . '/40');
		} else {
			$profile->setAvatarUrl($this->getEmptyAvatar());
		}
		$profile->setConnectData(json_encode($tokens, true));
		$profile->save();
		
		return sfView::SUCCESS;
	}
}
