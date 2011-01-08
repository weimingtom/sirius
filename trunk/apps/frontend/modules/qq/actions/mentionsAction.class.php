<?php

class mentionsAction extends QQAction {
	public function execute($request) {
		$consumer_key = sfConfig::get('app_qq_consumer_key');
	    $consumer_secret = sfConfig::get('app_qq_consumer_secret');
		$profile_id = $request->getParameter('profile_id');
		$this->forward404Unless($profile_id);
		
		// get profile
		$profile = Doctrine::getTable('profile')->find($profile_id);
		$this->forward404Unless($profile);

		// check user
		$this->forward404Unless($profile->getOwnerId() == $this->getUser()->getId());
		
		$connectData = json_decode($profile->getConnectData(), true);
		$weibo = new QQClient($consumer_key, $consumer_secret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		$data  = $weibo->mentions();
		
		$data = $data['data']['info'];
		if ($request->hasParameter('since_id')) {
			$sinceId = $request->getParameter('since_id');
			foreach ($data as $key => $value) {
				if ($value['id'] == $sinceId) {
					$data = array_slice($data, 0, $key);
				}
			}
		}		
		
		$messages = $this->formatMessages($data);
		return $this->renderText(json_encode($messages));
	}
}
