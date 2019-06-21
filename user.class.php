<?php
class User{
	var $id;
	var $username;
	var $email;
	var $verified;
	var $token;
	var $password;
	
	function __construct($id, $username, $email, $verified, $token, $password){
		$this->id = $id;
		$this->username = $username;
		$this->email = $email;
		$this->verified = $verified;
		$this->token = $token;
		$this->password = $password;
		}
}
?>