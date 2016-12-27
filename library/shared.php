<?php

function outputJson($code, $msg, $data = null){
	$arr['code'] = $code;
	$arr['msg'] = urlencode($msg);
	if($data) $arr['data'] = urlencodeArray($data);
	$json = json_encode($arr);
	header('Content-type: application/json');
	exit(urldecode($json));
}

function clearArray($arr){
	foreach($arr as $key => $value) {
		$arr[$key] = addslashes($value);
	}
	return $arr;
}

function urlencodeArray($arr){
	foreach($arr as $key => $value) {
		$arr[$key] = urlencode($value);
	}
	return $arr;
}