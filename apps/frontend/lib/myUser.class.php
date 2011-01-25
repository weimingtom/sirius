<?php

class myUser extends sfBasicSecurityUser
{	
	public function setId($id) {
		$this->setAttribute("user_id", $id);
	}
	
	public function getId() {
		return $this->getAttribute("user_id");
	}
	
	public function setEmail($email) {
		$this->setAttribute("user_email", $email);
	}
	
	public function getEmail() {
		return $this->getAttribute("user_email");
	}
	
	public function setName($email) {
		$this->setAttribute("user_name", $email);
	}
	
	public function getName() {
		return $this->getAttribute("user_name");
	}
	
	public function getTheme() {
		return 'NoTheme';
	}
}
