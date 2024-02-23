<?php
	namespace Core;
	
	class Controller {
		protected $layout = 'default';
		
		protected function getPage($view, $data = []) {
			return new Page($this->layout, $this->title, $view, $data);
		}
	}
