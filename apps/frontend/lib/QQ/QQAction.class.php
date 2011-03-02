<?php
abstract class QQAction extends myAction {
	public function preExecute() {
		parent::preExecute();
		
		$this->consumerKey = sfConfig::get('app_qq_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_qq_consumer_secret');
		$this->callbackUrl = "http://" . $_SERVER['HTTP_HOST'] . sfConfig::get('app_qq_callback_url');		
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
		$this->apiConsumer = new QQClient($this->consumerKey, $this->consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		
		return $this->apiConsumer;			
	}
	
	
	protected function formatMessages($originMessages, $isDM = false) {
		//var_dump($originMessages);die();
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
		$message->id = $origin['id'];
		
		$message->created_at = $this->formatTime($origin['timestamp']);
		
		$message->text = $this->formatText($origin['text']);
		$message->truncated = false; //TODO
		$message->favorited = false; //TODO
		$message->source = $origin['from'];
		
		if ($origin['image'] != null && is_array($origin['image']) && count($origin['image']) > 0) {
			$message->picture_thumbnail = $origin['image'][0] . '/160';
			$message->picture_medium = $origin['image'][0] . '/460';
			$message->picture_original = $origin['image'][0] . '/2000';
		}
		
		if ($origin['type'] == 2) {
			$message->retweet_origin = $this->formatMessage($origin['source']);
		} else {
			$message->retweetCount = $origin['count'];
		}
				
		$user = $message->user;
		$user->id = $origin['name'];
		$user->name = $origin['name'];
		$user->screen_name = $origin['nick'];
		if (strlen($origin['head']) > 0) {
			$user->avatar = $origin['head'] . '/40';
		} else {
			$user->avatar = $this->getEmptyAvatar();
		}
		
		return $message;
	}
	
	protected function formatDirectMessage($origin) {
		$message = new Message();
		$message->id = $origin['id'];
		
		$message->created_at = $this->formatTime($origin['timestamp']);
		
		$message->text = $this->formatText($origin['text']);
		$message->truncated = false; //TODO
		$message->source = $origin['from'];
		
		if ($origin['image'] != null && is_array($origin['image']) && count($origin['image']) > 0) {
			$message->picture_thumbnail = $origin['image'][0] . '/160';
			$message->picture_original = $origin['image'][0] . '/2000';
		}
		
		if ($origin['type'] == 2) {
			$message->retweet_origin = $this->formatMessage($origin['source']);
		}
		
		$user = $message->user;
		$user->id = $origin['name'];
		$user->name = $origin['name'];
		$user->screen_name = $origin['nick'];
		if (strlen($origin['head']) > 0) {
			$user->avatar = $origin['head'] . '/40';
		} else {
			$user->avatar = $this->getEmptyAvatar();
		}
		
		return $message;
	}
	
	protected function getEmptyAvatar($size = 50) {
		if ($size = 120) {
			return "http://mat1.gtimg.com/www/mb/images/head_120.jpg";
		}
		return "http://mat1.gtimg.com/www/mb/images/head_50.jpg";
	}
	
	protected function formatText($text) {
		// replace #
		$text = preg_replace( "/ *#([\x{4e00}-\x{9fa5}A-Za-z0-9_\ ]*)# ?/u", " <a class=\"_topic_link\" href=\"#\" topic=\"\\1\">#\\1#</a> ", $text); 
		// replace user
		$text = preg_replace( "/ *@([\x{4e00}-\x{9fa5}A-Za-z0-9_]*) ?/u", " <a class=\"_user_link\" href=\"#\" user=\"\\1\">@\\1</a> ", $text);
		
		// replace emotions
		foreach ($this->getEmotions() as $txt => $image) {
			$imgNode = '<img class="motion" src="' . $image . '" />';
			$txt = "/" . $txt;
			$text = str_replace($txt, $imgNode, $text);
		}
		
		return $text;
	}
	
	protected function getEmotions($keyword) {
		$emotions = array(
			"微笑" => "http://mat1.gtimg.com/www/mb/images/face/14.gif",
			"撇嘴" => "http://mat1.gtimg.com/www/mb/images/face/1.gif",
			"色" => "http://mat1.gtimg.com/www/mb/images/face/2.gif",
			"发呆" => "http://mat1.gtimg.com/www/mb/images/face/3.gif",
			"得意" => "http://mat1.gtimg.com/www/mb/images/face/4.gif",
			"流泪" => "http://mat1.gtimg.com/www/mb/images/face/5.gif",
			"害羞" => "http://mat1.gtimg.com/www/mb/images/face/6.gif",
			"闭嘴" => "http://mat1.gtimg.com/www/mb/images/face/7.gif",
			"睡" => "http://mat1.gtimg.com/www/mb/images/face/8.gif",
			"大哭" => "http://mat1.gtimg.com/www/mb/images/face/9.gif",
			"尴尬" => "http://mat1.gtimg.com/www/mb/images/face/10.gif",
			"发怒" => "http://mat1.gtimg.com/www/mb/images/face/11.gif",
			"调皮" => "http://mat1.gtimg.com/www/mb/images/face/12.gif",
			"呲牙" => "http://mat1.gtimg.com/www/mb/images/face/13.gif",
			"惊讶" => "http://mat1.gtimg.com/www/mb/images/face/0.gif",
			"难过" => "http://mat1.gtimg.com/www/mb/images/face/15.gif",
			"酷" => "http://mat1.gtimg.com/www/mb/images/face/16.gif",
			"冷汗" => "http://mat1.gtimg.com/www/mb/images/face/96.gif",
			"抓狂" => "http://mat1.gtimg.com/www/mb/images/face/18.gif",
			"吐" => "http://mat1.gtimg.com/www/mb/images/face/19.gif",
			"偷笑" => "http://mat1.gtimg.com/www/mb/images/face/20.gif",
			"可爱" => "http://mat1.gtimg.com/www/mb/images/face/21.gif",
			"白眼" => "http://mat1.gtimg.com/www/mb/images/face/22.gif",
			"傲慢" => "http://mat1.gtimg.com/www/mb/images/face/23.gif",
		);
		
		return $emotions;
	}
}
