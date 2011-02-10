<?php

class userInfoAction extends fanfouAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// user
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		$user = $this->apiConsumer->getUserInfo($username);
		
		$user['profile_image_url'] = str_replace('fanfou.com/s0', 'fanfou.com/l0', $user['profile_image_url']);
		
		if (strlen($user['url']) <= 10) {
			$user['url'] = "";
		}
		$this->userData = $user;
	}
}