<?php

class Route{
	public static function get(){
		return Route::explodeToArr($_SERVER['REQUEST_URI']);
	}

	public static function explodeToArr($uri){
		$uri = str_replace('odin/', '', strtolower($uri));
		$uri = $uri[0] == '/' ? substr($uri, 1) : $uri;
		$uri = strpos($uri, '?') ? substr($uri, 0, strpos($uri, '?')) : $uri;
		$arr = explode('/', $uri);
		return $arr;
	}
}

?>