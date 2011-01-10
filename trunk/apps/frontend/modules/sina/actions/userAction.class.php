<?php

class userAction extends sinaAction {
	public function execute($request) {
		$profileId = $request->getParameter('profileId');
		if (!$request->hasParameter('tabName')) {
			return $this->renderText('<div>
				<ul>
					<li><a href="/sina/user/' . profileId . '/bio">Ta的简介</a></li>
					<li><a href="/sina/user/' . profileId . '/timeline">Ta的微博</a></li>
				</ul>
			</div>');
		}
		
		$tabName = $request->getParameter('tabName');
		
		switch ($tabName) {
			case 'bio':
				return $this->renderText('bio');
				break;
			case 'timeline':
				return $this->renderText('timeline');
				break;
		}
	}
}
