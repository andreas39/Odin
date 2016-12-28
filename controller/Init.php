<?php

require ODIN_ROOT . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'Shared.php';
require ODIN_ROOT . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'Route.php';

class Init
{
	public static function odinInit(){

		if(ODIN_DATA_AUTO_CLEAR){
			$_REQUEST = Shared::clearArray($_REQUEST);
		}

		$r = Route::get();

		switch($r[0]){
			case '':
				Shared::view('index');
				break;

			case 'api':
				require ODIN_ROOT . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'InterfaceController.php';
				InterfaceController::odinInterface($r);
				break;

			case 'doc':
				require ODIN_ROOT . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'DocumentController.php';
				DocumentController::odinDocument($r);
				break;

			case 'dashboard':
				require ODIN_ROOT . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'DashboardController.php';
				//DashboardController::odindashboard($r);
				break;

			case 'manage':
				require ODIN_ROOT . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'ManageController.php';
				//ManageController::odinManage($r);
				break;
			
			default:
				Shared::view('404');;
				break;
		}
	}
}