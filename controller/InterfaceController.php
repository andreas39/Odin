<?php

require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Database.php';
require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'Interface.php';
require ODIN_ROOT . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'User.php';

class InterfaceController{
	public static function odinInterface($r){
		if(!empty($r[1]) && !empty($r[2]) && !empty($_REQUEST['role']) && !empty($_REQUEST['hash'])){
			$project = $r[1];
			$interface = $r[2];
			$role = $_REQUEST['role'];
			$hash = $_REQUEST['hash'];
		}
		else
			exit(Shared::outputJson(ODIN_ERROR_MISSING_PARAMETER_CODE, ODIN_ERROR_MISSING_PARAMETER_STRING));

		$if = new Interfaces();
		$ifInfo = $if->info($project, $interface);
		InterfaceController::checkIfInfo($ifInfo);

		$user = new User();
		$userInfo = $user->info($role, $hash);
		InterfaceController::checkUserInfo($userInfo, $ifInfo);

		$user->changeUserTime($role, -1);

		InterfaceController::executeProgram($ifInfo);
	}

	public static function checkIfInfo($ifInfo){
		if(!isset($ifInfo[0]))
			exit(Shared::outputJson(ODIN_ERROR_NO_SUCH_INTERFACE_CODE, ODIN_ERROR_NO_SUCH_INTERFACE_STRING));
		if($ifInfo[0]['interface_status'] == 2)
			exit(Shared::outputJson(ODIN_ERROR_INTERFACE_DISABLED_CODE, ODIN_ERROR_INTERFACE_DISABLED_STRING));
		$interfaceParameters = json_decode($ifInfo[0]['interface_parameters'], true);
		foreach($interfaceParameters as $key => $value){
			if(empty($_REQUEST[$key]))
				exit(Shared::outputJson(ODIN_ERROR_MISSING_PARAMETER_CODE, ODIN_ERROR_MISSING_PARAMETER_STRING));
		}
	}

	public static function checkUserInfo($userInfo, $ifInfo){
		if(!isset($userInfo[0]))
			exit(Shared::outputJson(ODIN_ERROR_WRONG_HASH_CODE, ODIN_ERROR_WRONG_HASH_STRING));
		if($userInfo[0]['user_status'] == 2)
			exit(Shared::outputJson(ODIN_ERROR_ACCOUNT_DISABLED_CODE, ODIN_ERROR_ACCOUNT_DISABLED_STRING));
		if($userInfo[0]['user_left'] <= 0)
			exit(Shared::outputJson(ODIN_ERROR_NO_TIME_LEFT_CODE, ODIN_ERROR_NO_TIME_LEFT_STRING));
		if($userInfo[0]['user_rank_id'] < $ifInfo[0]['interface_allow_rank_id'])
			exit(Shared::outputJson(ODIN_ERROR_NO_PERMISSION_CODE, ODIN_ERROR_NO_PERMISSION_STRING));
	}

	public static function executeProgram($ifInfo){
		$file = ODIN_ROOT . DIRECTORY_SEPARATOR . ODIN_UPLOAD_DIR . DIRECTORY_SEPARATOR . $ifInfo[0]['project_path'] . DIRECTORY_SEPARATOR . $ifInfo[0]['interface_path'];
		echo $file;
		switch($ifInfo[0]['interface_execute_method']){
			case 1:
				require $file;
				break;

			case 2:
				require $file;
				$io = new $ifInfo[0]['interface_execute_classname']();
				break;

			default:
				exit(Shared::outputJson(ODIN_ERROR_SYSTEM_ERROR_CODE, ODIN_ERROR_SYSTEM_ERROR_STRING));
				break;
		}
	}

	
}