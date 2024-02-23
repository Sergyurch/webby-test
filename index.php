<?php
	namespace Core;
	use \Project\Controllers\ErrorController;
	
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/project/config/connection.php';
	spl_autoload_register();
	session_start();
	
	$routes = require $_SERVER['DOCUMENT_ROOT'] . '/project/config/routes.php';
	
	try {
		$track = (new Router)->getTrack($routes, $_SERVER['REQUEST_URI']);

		if (
			$track->controller !== '\Project\Controllers\errorController' &&
			$track->action !== 'notFound' && 
			(!isset($_SESSION['auth']) || !$_SESSION['auth']) && 
			$_SERVER['REQUEST_URI'] !== '/register' && 
			$_SERVER['REQUEST_URI'] !== '/login'
		) {
			header('location: /login');
		}
		
		(new $track->controller)->{$track->action}($track->params);
	} catch (\Exception $error) {
		header("HTTP/1.0 500 Internal Server Error");
		(new ErrorController)->internalError();
	}
