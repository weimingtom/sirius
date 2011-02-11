<?php

class authbackAction extends doubanAction {
	public function execute($request) {
		$token = $this->getUser()->getAttribute("doubanOAuthToken");
		$this->forward404Unless($token);
		
		$oauth_verifier = $request->getParameter("oauth_token");
		$this->forward404Unless($oauth_verifier);

		$to = new doubanOAuth($this->consumerKey, $this->consumerSecret, $token['oauth_token'], $token['oauth_token_secret']);
		$tokens = $to->getAccessToken($oauth_verifier);
		$this->forward404Unless($tokens);
		//TODO: check return string
		$this->forward404Unless(count($tokens) > 1);
		
		$this->forward404Unless($this->getUser()->getId(), "User not found");

		// check fingerprint
		$checkResult = Doctrine::getTable('Profile')
        ->createQuery()
        ->where('type = ? AND profile_name = ?', array('douban', $tokens['douban_user_id']))
        ->fetchOne();
		if ($checkResult) {
			if ($checkResult->getOwnerId() == $this->getUser()->getId()) {
				$this->errorMsg = '您已经添加了该帐号,无法重复添加。';
			} else {
				$this->errorMsg = '该帐号已被其他MixMes用户添加';
			}			
			return sfView::ERROR;
		}
		$weibo = new doubanClient($this->consumerKey, $this->consumerSecret, $tokens['oauth_token'], $tokens['oauth_token_secret']);
		$user_profile = $weibo->show_user();

		$profile = new Profile();
		$profile->setOwnerId($this->getUser()->getId());
    	$profile->setProfileName($tokens['douban_user_id']);
		$profile->setScreenName($user_profile['title']['$t']);
		$profile->setType('douban');
		
		foreach ($user_profile['link'] as $link) {
			if ($link['@rel'] == 'icon') {
				$profile->setAvatarUrl($link['@href']);
				break;
			}
		}
		
		$profile->setConnectData(json_encode($tokens, true));
		$profile->save();
		
		return sfView::SUCCESS;
	}
}
