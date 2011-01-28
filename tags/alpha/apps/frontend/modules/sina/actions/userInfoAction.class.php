<?php

class userInfoAction extends sinaAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// user
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		$user = $this->apiConsumer->show_user($username);
		
		$user['profile_image_url_180'] = str_replace('/50/', '/180/', $user['profile_image_url']);
		if ($user['domain'] == '') {
			$user['domain'] = $user['id'];
		}
		
		if (strlen($user['url']) <= 10) {
			$user['url'] = "";
		}
		$this->userData = $user;
	}
}