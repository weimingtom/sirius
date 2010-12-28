<?php

class authAction extends sfAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_qq_consumer_key');
	    $consumer_secret = sfConfig::get('app_qq_consumer_secret');
		$callback_url = sfConfig::get('app_qq_callback_url');
		
		$to = new QQOAuth($consumer_key, $consumer_secret);
		$tok = $to->getRequestToken($callback_url); 
		$this->getUser()->setAttribute("QQOAuthToken", $tok);
		$request_link = $to->getAuthorizeURL($tok['oauth_token'], false, $callback_url);
		return $this->redirect($request_link);		
	}
}
