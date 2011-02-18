<?php

class ProfileInsertListener extends Doctrine_Record_Listener {
	public function postInsert(Doctrine_Event $event) {
		$invoker = $event->getInvoker();
		
		// add monitor
		$monitorProfile = new MonitorProfile();
		$monitorProfile->setTargetName($invoker->getProfileName());
		$monitorProfile->setTargetType($invoker->getType());
		$monitorProfile->setProfileId($invoker->getId());
		$monitorProfile->save();	
	}
}