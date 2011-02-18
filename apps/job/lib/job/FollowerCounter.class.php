<?php

/**
 * Call provider's API to get profile's follower count and friend count, and record them into DB for furture report
 * @author Cary Yang <getcary@gmail.com>
 *
 */
class MixMes_Job_FollowerCounter extends MixMes_Job_Abstract {
	
	/**
	 * Execute GetFollowerAndFriendCount job
	 * @param $time		job execution timestamp
	 * @param $params   job execution parameters
	 * @return void
	 * 
	 */
	public function execute($time, $params = array()) {
		$this->time = $time;
		$this->date = date('Y-m-d', $time);
		$monitorProfiles = Doctrine::getTable('MonitorProfile')
			->createQuery('')
			->fetchArray();
		foreach ($monitorProfiles as $profile) {
			$targetName = $profile['target_name'];
			$targetType = $profile['target_type'];
			$profileId = $profile['profile_id'];
			if ($profileId != null) {
				// get connect data from profile table
				$profile = Doctrine::getTable('profile')->find($profileId);
				if (!$profile) {
					continue;
				}
				$connectData = json_decode($profile->getConnectData(), true);
				$this->getFollowerAndFriendCount($targetType, $connectData, $targetName);
			}
		}
	}
	
	protected function getFollowerAndFriendCount($type, $connectData, $userName) {
		$apiConsumer = $this->getApiConsumer($type, $connectData);
		if (!$apiConsumer) {
			return false;
		}
		switch ($type) {
			case 'sina':
				list($followerCount, $friendCount, $messageCount) = $this->getSinaFollowerAndFriendCount($apiConsumer, $userName);
				break;
			case 'qq':
				list($followerCount, $friendCount, $messageCount) = $this->getQQFollowerAndFriendCount($apiConsumer, $userName);
				break;
			default:
				return false;
		}
		$record = new ReportDailyCount;
		$record->setFollowersCount($followerCount);
		$record->setFriendsCount($friendCount);
		$record->setStatusesCount($messageCount);
		$record->setProfileName($userName);
		$record->setProfileType($type);
		$record->setDate($this->date);
		$record->save();
	}
	
	protected function getSinaFollowerAndFriendCount($apiConsumer, $userName) {
		$user = $apiConsumer->show_user($userName);
		$followerCount = $user['followers_count'];
		$friendCount = $user['friends_count'];
		$messageCount = $user['statuses_count'];	
		return array($followerCount, $friendCount, $messageCount);	
	}
	
	protected function getQQFollowerAndFriendCount($apiConsumer, $userName) {
		$user = $apiConsumer->show_user($userName);
		$followerCount = $user["data"]['fansnum'];
		$friendCount = $user["data"]['idolnum'];
		$messageCount = $user["data"]['tweetnum'];	
		return array($followerCount, $friendCount, $messageCount);	
	}
	
	protected function getApiConsumer($type, $connectData) {
		switch ($type) {
			case 'sina':
			case 'qq':
				$consumerKey = sfConfig::get('app_' . $type . '_consumer_key');
	    		$consumerSecret = sfConfig::get('app_' . $type . '_consumer_secret');
				break;
			default:
				return false;
		}
		
		switch ($type) {
			case 'sina':
				return new WeiboClient($consumerKey, $consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
			case 'qq':
				return new QQClient($consumerKey, $consumerSecret, $connectData['oauth_token'], $connectData['oauth_token_secret']);
		}
		
		return false;
	}
}