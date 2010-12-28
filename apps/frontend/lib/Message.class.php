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
	
	public $id;
	public $created_at;
	public $source;
	public $text;
	public $truncated;
	public $user;
}
