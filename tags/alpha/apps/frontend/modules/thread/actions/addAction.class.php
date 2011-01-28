<?php

class addAction extends sfAction {
	public function execute($request) {
		$this->forward404Unless($request->hasParameter("tabId"));
		$this->forward404Unless($request->hasParameter("profileId"));
		$this->forward404Unless($request->hasParameter("type"));
		
		$ownerId = $this->getUser()->getId();
		$threadTitle = $request->getParameter("title", "Untitled Thread");
		$type = $request->getParameter("type");
		$parameters = $request->getParameter("parameters", "");

		$tabId = $request->getParameter("tabId");
		$tab = Doctrine_Core::getTable("Tab")->find($tabId);
		$this->forward404Unless($tab);
		$this->forward404Unless($tab->getOwnerId() == $ownerId);		
		
		$profileId = $request->getParameter("profileId");
		$profile = Doctrine_Core::getTable("Profile")->find($profileId);
		$this->forward404Unless($profile);
		$this->forward404Unless($profile->getOwnerId() == $ownerId);
		$profileName = $profile->getScreenName();
		$profileType = $profile->getType();
		
		$thread = new Thread();
		$thread->setTitle($threadTitle);
		$thread->setOwnerId($ownerId);
		$thread->setTabId($tabId);
		$thread->setProfileId($profileId);
		$thread->setProfileName($profileName);
		$thread->setProfileType($profileType);
		$thread->setType($type);
		$thread->setParameters($parameters);
		$thread->save();
		
		$threadIds = json_decode($tab->getThreadIds(), true);
		$threadIds[] = $thread->getId();
		$tab->setThreadIds(json_encode($threadIds));
		$tab->save();

		return $this->renderText(json_encode($thread->toArray()));
	}
}
