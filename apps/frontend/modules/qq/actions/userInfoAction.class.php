<?php

class userInfoAction extends QQAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// user
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		
		$user = $this->apiConsumer->show_user($username);
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
