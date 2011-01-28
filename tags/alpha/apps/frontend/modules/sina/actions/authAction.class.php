<?php

class authAction extends sinaAction {
	public function execute($request) {
		$to = new WeiboOAuth($this->consumerKey, $this->consumerSecret);
		$tok = $to->getRequestToken();
		$this->getUser()->setAttribute("SinaOAuthToken", $tok);
		$request_link = $to->getAuthorizeURL($tok['oauth_token'], false, $this->callbackUrl);
		return $this->redirect($request_link);		
	}
}
