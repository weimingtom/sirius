<?php

class userInfoAction extends sohuAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// user
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		$user = $this->apiConsumer->show_user($username);
		//var_dump($user);die();
		$user['profile_image_url_180'] = $user['profile_image_url'];
		if ($user['domain'] == '') {
			$user['domain'] = $user['id'];
		}
		
		if (strlen($user['url']) <= 10) {
			$user['url'] = "";
		}
		$this->userData = $user;
	}
}