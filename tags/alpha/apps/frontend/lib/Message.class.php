<?php
class MessageUser {
	public $id;
	public $name;
	public $screen_name;
	public $avatar;
}

class Message {
	public function __construct() {
		$this->user = new MessageUser;	
	}
	
	public $id = "";
	public $created_at = "";
	public $source = "";
	public $text = "";
	public $user;
	public $truncated = "";
	
	public $retweetCount = -1;
	public $commentCount = -1;
	
	public $picture_thumbnail = "";
	public $picture_original = "";
	//public $video_thumbnail;
	//public $video_original;
	
	public $retweet_origin = null;
}
