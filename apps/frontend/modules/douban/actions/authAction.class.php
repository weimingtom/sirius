<?php

class authAction extends doubanAction {
	public function execute($request) {
		$to = new doubanOAuth($this->consumerKey, $this->consumerSecret);
		$tok = $to->getRequestToken($this->callbackUrl); 
		$this->getUser()->setAttribute("doubanOAuthToken", $tok);
		$request_link = $to->getAuthorizeURL($tok['oauth_token'], false, $this->callbackUrl);
		return $this->redirect($request_link);		
	}
}
