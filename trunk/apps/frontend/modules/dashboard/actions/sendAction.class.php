<?php
class sendAction extends sfAction {
	public function execute($request) {
		$message = $request->getParameter('message');
		$profiles = $request->getParameter('profiles');
		
		$this->forward404Unless($message && strlen($message) > 0);
		$this->forward404Unless($profiles && is_array($profiles) && count($profiles) > 0);
		
		foreach($profiles as $profile) {
			list($profileType, $profileId) = explode('|', $profile . '|');
			//$this->forward404Unless($profileType!="" && $profileId!="");
			$clazz = new ReflectionClass($profileType . 'MessageSender');
			$sender = $clazz->newInstance();

			$sender->sendMessage($profileId, $message); 
		}
		
		return $this->renderText("abc");
	}
}