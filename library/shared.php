<?php

class Shared{

	public static function outputJson($code, $msg, $data = null){
		$arr['code'] = $code;
		$arr['msg'] = urlencode($msg);
		if($data) $arr['data'] = Shared::urlencodeArray($data);
		$json = json_encode($arr);
		header('Content-type: application/json');
		exit(urldecode($json));
	}

	public static function clearArray($arr){
		foreach($arr as $key => $value) {
			$arr[$key] = addslashes($value);
		}
		return $arr;
	}

	public static function urlencodeArray($arr){
		foreach($arr as $key => $value) {
			$arr[$key] = urlencode($value);
		}
		return $arr;
	}

	public static function view($page, $p = null){
		require ODIN_ROOT . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $page . '.html';
	}

}