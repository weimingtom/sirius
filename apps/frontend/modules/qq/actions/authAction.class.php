<?php

class authAction extends QQAction {
	public function execute($request) {
		$to = new QQOAuth($this->consumerKey, $this->consumerSecret);
		$tok = $to->getRequestToken($callback_url); 
		$this->getUser()->setAttribute("QQOAuthToken", $tok);
		$request_link = $to->getAuthorizeURL($tok['oauth_token'], false, $this->callbackUrl);
		return $this->redirect($request_link);		
	}
}
