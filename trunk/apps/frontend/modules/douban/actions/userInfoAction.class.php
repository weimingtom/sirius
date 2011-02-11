<?php

class userInfoAction extends doubanAction {
	public function execute($request) {
		// prepare apiConsumer
		$this->forward404Unless($this->prepareApiConsumer($request));
		
		// user
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		
		$user = $this->apiConsumer->show_user($username);
		$this->forward404Unless($user['ret'] == 0);
		
		$user['links']= array();
		foreach ($user['link'] as $link) {
			switch ($link['@rel']) {
				case 'icon':
					$user['head'] = $link['@href'];
					break;
				case 'alternate':
				case 'homepage':
					$user['links'][] = $link['@href'];
					break;
			}	
		}
				
		$this->userData = $user;
	}
}
