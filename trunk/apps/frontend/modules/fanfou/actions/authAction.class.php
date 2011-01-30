<?php

class authAction extends fanfouAction {
	public function execute($request) {
		$username = $request->getParameter('fanfou_username');
		$password = $request->getParameter('fanfou_password');
		if (!$username || !$password) {
			return $this->renderText('{error: "饭否用户名和密码都要填哦!"}');
		}
		
		$apiConsumer = new fanfouClient($username, $password);
		$verifyResult = $apiConsumer->friendsTimeline($username);
		if (isset($verifyResult['error'])) {
			return $this->renderText('{"error": "用户名或者密码好像不对，饭否拒绝认证您的身份!"}');
		}
		
		$this->forward404Unless($this->getUser()->getId(), "User not found");
		
		// check fingerprint
		$checkResult = Doctrine::getTable('Profile')
        ->createQuery()
        ->where('type = ? AND profile_name = ?', array('fanfou', $username))
        ->fetchOne();
		if ($checkResult) {
			if ($checkResult->getOwnerId() == $this->getUser()->getId()) {
				return $this->renderText('{"error": "您已经添加了该帐号,无法重复添加。"}');
			} else {
				return $this->renderText('{"error": "该帐号已被其他MixMes用户添加"}');
			}			
		}
		
		$userInfo = $apiConsumer->getUserInfo($username);
		
		$profile = new Profile();
		$profile->setOwnerId($this->getUser()->getId());
    	$profile->setProfileName($userInfo['name']);
		$profile->setScreenName($userInfo['screen_name']);
		$profile->setType('fanfou');
		$profile->setAvatarUrl($userInfo['profile_image_url']);
		
		$profile->setConnectData(json_encode(array("username"=>$username, "password"=>$password), true));
		$profile->save();
		
		return sfView::NONE;
	}
}
