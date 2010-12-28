<?php

class authAction extends sfAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_sina_consumer_key');
	    $consumer_secret = sfConfig::get('app_sina_consumer_secret');
		$callback_url = sfConfig::get('app_sina_callback_url');
				
		$to = new WeiboOAuth($consumer_key, $consumer_secret);
		$tok = $to->getRequestToken();
		$this->getUser()->setAttribute("SinaOAuthToken", $tok);
		$request_link = $to->getAuthorizeURL($tok['oauth_token'], false, $callback_url);
		return $this->redirect($request_link);		
	}
}
