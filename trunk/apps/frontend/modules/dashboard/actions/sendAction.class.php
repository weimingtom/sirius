<?php
class sendAction extends sfAction {
	public function execute($request) {
		$message = $request->getParameter('message', '');
		$profiles = $request->getParameter('profiles');
		$type = $request->getParameter('type');
		$profile_type = $request->getParameter('profile_type');
		$target_message_id = $request->getParameter('target_message_id');
		$target_message_content = $request->getParameter('target_message_content');
		$target_message_author = $request->getParameter('target_message_author');
		$image = $request->getParameter('image');
		
		$this->forward404Unless($type == 'retweet' || strlen($message) > 0);
		$this->forward404Unless($profiles && is_array($profiles) && count($profiles) > 0);
		
		foreach($profiles as $profile) {
			list($profileType, $profileId) = explode('|', $profile . '|');
			
			$content = null;
			if ($profile_type != null && $profile_type != $profileType) {
				$content = strip_tags($target_message_content);
			}
			
			$clazz = new ReflectionClass($profileType . 'MessageSender');
			$sender = $clazz->newInstance();
			
			switch ($type) {
				case 'retweet':
					$sender->retweetMessage($profileId, $message, $target_message_id, $content, $target_message_author);
					break;
				case 'comment':
					$sender->commentMessage($profileId, $message, $target_message_id, $content, $target_message_author);
					break;
				default:
					$sender->sendMessage($profileId, $message, $image);
					break;
			}			
		}
		
		return $this->renderText("");
	}
}