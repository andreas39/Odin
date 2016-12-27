<?php

require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Database.php';
require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Interface.php';

class DocumentController{
	public static function odinDocument($r){
		print_r($r);
	}
}