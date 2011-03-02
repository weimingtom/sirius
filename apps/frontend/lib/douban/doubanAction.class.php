<?php
abstract class doubanAction extends myAction {
	public function preExecute() {
		parent::preExecute();
		
		$this->consumerKey = sfConfig::get('app_douban_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_douban_consumer_secret');
		$this->callbackUrl = "http://" . $_SERVER['HTTP_HOST'] . sfConfig::get('app_douban_callback_url');		
	}
	
	protected function prepareApiConsumer($request) {
		if (!$request->hasParameter('profile_id')) {
			return false;
		}		
		$this->profileId = $request->getParameter('profile_id');
		
		// get profile
		$this->profile = Doctrine::getTable('profile')->find($this->profileId);
		if (!$this->profile) {
			return false;
		}
		
		// check user
		if ($this->profile->getOwnerId() != $this->getUser()->getId()) {
			return false;
		}
		
		$connectData = json_decode($this->profile->getConnectData(), true);
		$this->apiConsumer = new doubanClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		
		return $this->apiConsumer;			
	}
	
	
	protected function formatMessages($originMessages, $isDM = false) {
		$messages = array();
		foreach ($originMessages as $originMessage) {
			if ($isDM) {
				$messages[] = $this->formatDirectMessage($originMessage);
			} else {
				$messages[] = $this->formatMessage($originMessage);
			}
		}
		return $messages;
	}
	
	protected function formatMessage($origin) {
		$message = new Message();
		$message->id = str_replace('http://api.douban.com/miniblog/','' ,$origin['id']['$t']);
		
		$message->created_at = $this->formatTime($origin['published']['$t']);
		
		$message->text = $this->formatText($origin['content']['$t']);
		$message->truncated = false; //TODO
		$message->favorited = false; //TODO
		
		if (isset($origin['link'])) {
			foreach($origin['link'] as $link) {
				if ($link['@rel'] == 'image') {
					$message->picture_thumbnail = $link['@href'];
					break;
				}
			}
		}
		
		$user = $message->user;
		foreach($origin['author']['link'] as $link) {
			switch ($link['@rel']) {
				case 'self':
					$user->id = str_replace('http://api.douban.com/people/','' ,$link['@href']);
					break;
				case 'alternate':
					$user->name = trim(str_replace('http://www.douban.com/people/','' ,$link['@href']), "/");
					break;
				case 'icon':
					$user->avatar = $link['@href'];
					break;
			}
		}
		
		if (!$user->avatar) {
			$user->avatar = $this->getEmptyAvatar();
		}
		$user->screen_name = $origin['author']['name']['$t'];
		
		return $message;
	}
	
	protected function formatDirectMessage($origin) {
		$message = new Message();
		$message->id = str_replace('http://api.douban.com/doumail/','' ,$origin['id']['$t']);
		
		$message->created_at = $this->formatTime($origin['published']['$t']);
		
		$message->text = $origin['title']['$t'];
		$message->truncated = false; //TODO
		$message->favorited = false; //TODO
		
		if (isset($origin['link'])) {
			foreach($origin['link'] as $link) {
				if ($link['@rel'] == 'alternate') {
					$message->text .= " <a target='_blank' href='" . $link['@href'] . "'>查看豆邮</a>";
					break;
				}
			}
		}
		
		$user = $message->user;
		foreach($origin['author']['link'] as $link) {
			switch ($link['@rel']) {
				case 'self':
					$user->id = str_replace('http://api.douban.com/people/','' ,$link['@href']);
					break;
				case 'alternate':
					$user->name = trim(str_replace('http://www.douban.com/people/','' ,$link['@href']), "/");
					break;
				case 'icon':
					$user->avatar = $link['@href'];
					break;
			}
		}
		
		if (!$user->avatar) {
			$user->avatar = $this->getEmptyAvatar();
		}
		$user->screen_name = $origin['author']['name']['$t'];
		return $message;
	}
	
	protected function getEmptyAvatar() {
		return "http://img3.douban.com/icon/user_normal.jpg";
	}
	
	protected function formatText($text) {
		// replace #
		/* @var unknown_type */
		$text = str_replace( "<a ", "<a target='_blank' ", $text);
		return $text;
	}
}
