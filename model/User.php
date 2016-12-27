<?php

class User{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function info($role, $hash){
		return $this->db->getData("SELECT * FROM user WHERE user_id = '$role' AND user_hash = '$hash'");
	}

	public function addUser($data){
		//
	}

	public function deleteUser($data){
		//
	}

	public function disableUser($data){
		//
	}

	public function enableUser($data){
		//
	}

	public function changeUserTime($user_id, $num){
		return $this->db->executeSql("UPDATE user SET user_left = user_left + $num WHERE user_id = $user_id");
	}

	public function changeUserRank($data){
		//
	}
}

?>