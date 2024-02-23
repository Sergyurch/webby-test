<?php
	namespace Core;
	
	class Router {
		public function getTrack($routes, $uri) {
			foreach ($routes as $route) {
				$pattern = $this->createPattern($route->path);
				
				if (preg_match($pattern, $uri, $params)) {
					$params = $this->clearParams($params); 
					$className = ucfirst($route->controller) . 'Controller';
					$fullName = "\\Project\\Controllers\\$className";

					return new Track($fullName, $route->action, $params);
				}
			}
			
			header("HTTP/1.0 404 Not Found");
			return new Track('\Project\Controllers\ErrorController', 'notFound');
		}
		
		private function createPattern($path) {
			return '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $path) . '/?$#';
		}
		
		private function clearParams($params) {
			$result = [];
			
			foreach ($params as $key => $param) {
				if (!is_int($key)) {
					$result[$key] = $param;
				}
			}
			
			return $result;
		}
	}
