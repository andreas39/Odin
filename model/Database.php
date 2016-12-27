<?php

class Database{

	private $conn;

	public function __construct(){
		$conn = new mysqli(ODIN_DATABASE_ADDRESS, ODIN_DATABASE_USER, ODIN_DATABASE_PASSWORD, ODIN_DATABASE_TABLE);
		if($conn->connect_error) $this->outputError();
		$conn->query("SET NAMES 'UTF8'");
		$this->conn = $conn;
	}

	public function executeSql($sql){
		return $this->conn->query($sql);
	}

	public function getData($sql){
		$arr = array();
		$rs = $this->executeSql($sql);
		if($rs->num_rows == 0) return $arr;
		while($row = $rs->fetch_assoc()) array_push($arr, $row);
		return $arr;
	}

	public function getNum($sql){
		$rs = $this->executeSql($sql);
		return $rs->num_rows;
	}

	public function close(){
		$this->conn->close();
	}

	private function outputError(){
		exit(outputJson(ODIN_ERROR_SYSTEM_ERROR_CODE, ODIN_ERROR_SYSTEM_ERROR_STRING . ':数据库连接错误'));
	}
}