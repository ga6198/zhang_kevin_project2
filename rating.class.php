<?php
require_once 'clan.php';

class Rating{
	var $username;
	var $rating;
	var	$message;
	var	$profile_picture;
	
	
	function __construct($username, $rating, $message, $profile_picture){
		$this->username = $username;
		$this->rating = $rating;
		$this->message = $message;
		$this->profile_picture = $profile_picture;
	}
}
?>