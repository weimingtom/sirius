<?php

class authbackAction extends sinaAction {
	public function execute($request) {
		$token = $this->getUser()->getAttribute("SinaOAuthToken");
		$this->forward404Unless($token);
		
		$oauth_verifier = $request->getParameter("oauth_verifier");
		$this->forward404Unless($oauth_verifier);
		
		$to = new WeiboOAuth($this->consumerKey, $this->consumerSecret, $token['oauth_token'], $token['oauth_token_secret']);
		$tokens = $to->getAccessToken($oauth_verifier);
		$this->forward404Unless($token);
		//TODO: check return string
		
		$this->forward404Unless($this->getUser()->getId(), "User not found");
		
		// check fingerprint
		$checkResult = Doctrine::getTable('Profile')
				->createQuery()
				->where('type = ? AND profile_name = ?', array('sina', $tokens['user_id']))
				->fetchOne();
		if ($checkResult) {
			if ($checkResult->getOwnerId() == $this->getUser()->getId()) {
				$this->errorMsg = '您已经添加了该帐号,无法重复添加。';
			} else {
				$this->errorMsg = '该帐号已被其他MixMes用户添加';
			}			
			return sfView::ERROR;
		}
		
		$weibo = new WeiboClient($this->consumerKey, $this->consumerSecret, $tokens['oauth_token'], $tokens['oauth_token_secret']);
		$user_profile = $weibo->show_user($tokens['user_id']);
		
		$profile = new Profile();
		$profile->setOwnerId($this->getUser()->getId());
		$profile->setScreenName($user_profile['screen_name']);
    	$profile->setProfileName($tokens['user_id']);
		$profile->setType('sina');
		$profile->setAvatarUrl($user_profile['profile_image_url']);
		$profile->setConnectData(json_encode($tokens, true));
		$profile->save();
		
		return sfView::SUCCESS;
	}
}
