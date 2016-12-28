<?php

require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Database.php';
require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Interface.php';
require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Project.php';

class DocumentController{
	public static function odinDocument($r){
		$if = new Interfaces();
		if(!empty($r[2]) && !empty($r[1])){
			$ifList = $if->info($r[1], $r[2]);
			empty($ifList) ? DocumentController::notfoundView() : DocumentController::totalView($ifList);
		}
		else if(!empty($r[1])){
			$ifList = $if->info($r[1], '%');
			empty($ifList) ? DocumentController::notfoundView() : DocumentController::totalView($ifList);
		}
		else{
			
			DocumentController::projectView();
		}
		
	}

	public static function totalView($ifList){
		Shared::view('doc' . DIRECTORY_SEPARATOR . 'content', $ifList);
	}

	public static function projectView(){
		$project = new Project();
		$projectList = $project->info('%');
		Shared::view('doc' . DIRECTORY_SEPARATOR . 'index', $projectList);
	}

	public static function notfoundView(){
		Shared::view('404');
	}
}