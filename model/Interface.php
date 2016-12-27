<?php

class Interfaces{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function info($project, $interface){
		return $this->db->getData("SELECT * FROM interface, project WHERE interface_name = '$interface' AND project_name = '$project'");
	}

	public function addInterface($pid, $name, $description, $parameters, $usage, $allow_rank_id, $status, $execute_method, $execute_parameters, $path){
		//
	}

	public function modifyInterface($data){
		//
	}

	public function deleteInterface($data){
		//
	}
}

?>