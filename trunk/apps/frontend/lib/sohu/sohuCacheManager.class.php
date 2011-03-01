<?php

class sohuCacheManager {
	private static $instance;
	private $cache;
	private $countPerCall;
	private $cacheFreshTheshold;
	
	private function __construct() {
		$this->cache = new sfMemcacheCache();
		$this->countPerCall = intval(sfConfig::get('app_sohu_count_per_call', 200));
		$this->cacheFreshTheshold = intval(sfConfig::get('app_sohu_cache_refresh_threshold', 50));
		
		$this->consumerKey = sfConfig::get('app_sohu_consumer_key');
	    $this->consumerSecret = sfConfig::get('app_sohu_consumer_secret');		
	}
	
	public static function getInstance() {
		if (!isset(self::$instance)) {
			$clazz = __CLASS__;
			self::$instance = new $clazz;
		}
		
		return self::$instance;
	}
	
	// Prevent users to clone the instance
	public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
	
	public function home_timeline($profile_id, $apiConsumer, $since_id = null, $before_id = null, $count = 20) {
		$cacheMessageListName = 'sohu_' . 'list_home_' . $profile_id;
		return $this->getMessagesBySinceIdOrBeforeId($apiConsumer, 'home_timeline', $cacheMessageListName, $since_id, $before_id, $count);
	}
	
	public function list_dm($profile_id, $apiConsumer, $since_id = null, $before_id = null, $count = 20) {
		$cacheMessageListName = 'sohu_' . 'list_dm_' . $profile_id;
		return $this->getMessagesBySinceIdOrBeforeId($apiConsumer, 'list_dm', $cacheMessageListName, $since_id, $before_id, $count);
	}

	public function mentions($profile_id, $apiConsumer, $since_id = null, $before_id = null, $count = 20) {
		$cacheMessageListName = 'sohu_' . 'list_mentions_' . $profile_id;
		return $this->getMessagesBySinceIdOrBeforeId($apiConsumer, 'mentions', $cacheMessageListName, $since_id, $before_id, $count);
	}

	public function user_timeline($profile_id, $apiConsumer, $since_id = null, $before_id = null, $count = 20, $uid_or_name = null) {
		$cacheMessageListName = 'sohu_' . 'list_user_' . $uid_or_name . '_' . $profile_id;
		return $this->getMessagesBySinceIdOrBeforeId($apiConsumer, 'user_timeline', $cacheMessageListName, $since_id, $before_id, $count, $uid_or_name);
	}
	
	public function favorite_timeline($profile_id, $apiConsumer, $since_id = null, $before_id = null, $count = 20) {
		$cacheMessageListName = 'sohu_' . 'list_favorite_' . $profile_id;
		return $this->getMessagesBySinceIdOrBeforeId($apiConsumer, 'get_favorites', $cacheMessageListName, $since_id, $before_id, $count, $uid_or_name);
	}
	
	public function getEmotions() {
		$cacheEmotionsName = 'sohu_emotions_list';
		$emotions = $this->cache->get($cacheEmotionsName);
		if ($emotions) {
			return $emotions;
		}
		
		$emotions = json_decode(file_get_contents('http://api.t.sohu.com.cn/emotions.json'), true);
		$this->cache->set($cacheEmotionsName, $emotions);
		return $emotions;
	}

	protected function getMessagesBySinceIdOrBeforeId($apiConsumer, $functionName, $cacheMessageListName, $since_id = null, $before_id = null, $count = 20, $addtional_arg1 = null, $addtional_arg2 = null, $addtional_arg3 = null) {
		$cacheMessageListLastModify = $this->cache->getLastModified($cacheMessageListName);
		$cacheMessageList = $this->cache->get($cacheMessageListName, array());
		$newCacheMessageList = $cacheMessageList;
		if ($before_id === null) {
			if (time() - $cacheMessageListLastModify > $this->cacheFreshTheshold) {
				$new_since_id = count($cacheMessageList) > 1 ? $cacheMessageList[1] : null;
				$data = $apiConsumer->$functionName($new_since_id, 1, $this->countPerCall, null, $addtional_arg1, $addtional_arg2, $addtional_arg3);
				$this->cacheMessages($data);
				$ids = $this->getIdListFromApiData($data);
				$newCacheMessageList = $this->mergeIdLists($ids, $cacheMessageList);	
				$this->cache->set($cacheMessageListName, $newCacheMessageList);			
			}
			$offset = array_search($since_id, $newCacheMessageList);
			if ($since_id != null && $offset !== FALSE && $offset < $count) {
				return $this->getMessagesFromCache(array_splice($newCacheMessageList, 0, $offset));
			} else {
				return $this->getMessagesFromCache(array_splice($newCacheMessageList, 0, $count));
			}
		} else { // $max_id != null
			if (array_search($before_id, $cacheMessageList) === FALSE) {
				return array();
			} else {
				$offset = array_search($before_id, $cacheMessageList);
				$total = count($cacheMessageList);
				if ($total - $offset < $count) {
					$max_id = end($cacheMessageList);
					$data = $apiConsumer->$functionName(null, 1, $this->countPerCall, $max_id, $addtional_arg1, $addtional_arg2, $addtional_arg3);
					$this->cacheMessages($data);
					$ids = $this->getIdListFromApiData($data);
					$newCacheMessageList = $this->mergeIdLists($cacheMessageList, $ids);	
					$this->cache->set($cacheMessageListName, $newCacheMessageList);			
					return $this->getMessagesFromCache(array_splice(array_slice($newCacheMessageList, $offset + 1), 0, $count));
				} else {
					return $this->getMessagesFromCache(array_splice(array_slice($cacheMessageList, $offset + 1), 0, $count));					
				}
			}
		}
	}
	
	protected function getIdListFromApiData($data) {
		$ids = array();
		foreach ($data as $message) {
			$ids[] = $message['id'];
		}
		return $ids;
	}
	
	public function cacheMessages($data) {
		if (isset($data['id'])) {
			$this->cache->set('sohu_message_' . $data['id'], $data);
		} else {
			foreach ($data as $message) {
				$this->cache->set('sohu_message_' . $message['id'], $message);
			}
		}
	}
	
	protected function mergeIdLists($newList, $oldList) {
		if (count($oldList) == 0 || array_search(end($newList), $oldList) === FALSE) {
			return $newList;
		} else {
			$offset = array_search(end($newList), $oldList) + 1;
			$oldList = array_slice($oldList, $offset);
			return array_merge($newList, $oldList);
		}
	}
	
	protected function getMessagesFromCache($idArray) {
		$result = array();
		foreach ($idArray as $id) {
			$result[] = $this->cache->get('sohu_message_' . $id);
		}
		return $result;
	}
}
