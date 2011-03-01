<?php

class authAction extends sohuAction {
	public function execute($request) {
		$addTab = $request->getParameter('addTab', false);
		$this->getUser()->setAttribute("addTab", $addTab && true);
		
		$to = new SohuOAuth($this->consumerKey, $this->consumerSecret);
		$tok = $to->getRequestToken();
		$this->getUser()->setAttribute("SohuOAuthToken", $tok);
		$request_link = $to->getAuthorizeURL($tok['oauth_token'], false, $this->callbackUrl);
		return $this->redirect($request_link);		
	}
}
