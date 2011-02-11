<?php

class savePreferenceAction extends sfAction {
	public function execute($request) {
		$userId = $this->getUser()->getId();
		
		$name = $request->getParameter("name");
		$value = $request->getParameter("value");
		
		$this->forward404If($name === null || $value === null);

		$user = Doctrine_Core::getTable("User")->find($this->getUser()->getId());
	    $dashboardSettings = $user->getDashboardSettings();

	    switch ($name) {
	    	case 'threadWidth':
	    		$value = intval($value);
	    		if ($value > 600 || $value < 300) {
	    			return $this->getErrorMessage("宽度设置有误");
	    		}
	    		$dashboardSettings->setThreadWidth($value);
	    		$dashboardSettings->save();
	    		return sfView::NONE;
	    	case 'refreshFrequency':
	    		$value = intval($value);
	    		if (!in_array($value, array(0, 5, 10, 20))) {
	    			return $this->getErrorMessage("刷新频率设置有误");
	    		}
	    		$dashboardSettings->setRefreshFrequency($value);
	    		$dashboardSettings->save();
	    		return sfView::NONE;
	    	default:
	    		return $this->getErrorMessage("无法识别设置项");
	    }
	}

	protected function getErrorMessage($msg) {
		return $this->renderText(json_encode(array("error" => $msg)));
	}
}
