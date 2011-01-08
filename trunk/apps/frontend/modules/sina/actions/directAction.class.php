<?php

class directAction extends sinaAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_sina_consumer_key');
	    $consumer_secret = sfConfig::get('app_sina_consumer_secret');
		$profile_id = $request->getParameter('profile_id');
		$this->forward404Unless($profile_id);
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new WeiboClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$since_id = $request->getParameter('since_id');
		$data  = $weibo->list_dm($since_id);

		$messages = $this->formatMessages($data, true);
		return $this->renderText(json_encode($messages));
	}
}
