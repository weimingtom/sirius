<?php

class userAction extends sinaAction {
	public function execute($request) {
		$profile_id = $request->getParameter('profile_id');
		$this->forward404Unless($profile_id);
		
		// user name
		$username = $request->getParameter('name');
		$this->forward404Unless($username);
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new WeiboClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$since_id = $request->getParameter('since_id');
		$data  = $weibo->user_timeline($username, $since_id);

		$messages = $this->formatMessages($data);
		
		if ($request->hasParameter('format') && $request->getParameter('format') == 'html') {
			return $this->renderPartial('thread/messages', array('messages'=>$messages));
		}
		return $this->renderText(json_encode($messages));
	}
}
