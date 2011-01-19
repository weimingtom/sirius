<?php
class sendAction extends sfAction {
	public function execute($request) {
		$message = $request->getParameter('message');
		$profiles = $request->getParameter('profiles');
		$type = $request->getParameter('type');
		$profile_type = $request->getParameter('profile_type');
		$target_message_id = $request->getParameter('target_message_id');
		$image = $request->getParameter('image');
		
		$this->forward404Unless($message && strlen($message) > 0);
		$this->forward404Unless($profiles && is_array($profiles) && count($profiles) > 0);
		
		foreach($profiles as $profile) {
			list($profileType, $profileId) = explode('|', $profile . '|');
			
			if ($profile_type != null && $profile_type != $profileType) {
				continue;
			}
			
			$clazz = new ReflectionClass($profileType . 'MessageSender');
			$sender = $clazz->newInstance();
			
			switch ($type) {
				case 'retweet':
					$sender->retweetMessage($profileId, $message, $target_message_id);
					break;
				case 'comment':
					$sender->commentMessage($profileId, $message, $target_message_id);
					break;
				default:
					$sender->sendMessage($profileId, $message, $image);
					break;
			}			
		}
		
		return $this->renderText("");
	}
}