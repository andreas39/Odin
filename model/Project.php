<?php

class Project{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function info($project){
		return $this->db->getData("SELECT * FROM project WHERE project_name LIKE '$project'");
	}
}

?>