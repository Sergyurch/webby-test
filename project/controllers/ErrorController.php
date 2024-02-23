<?php
	namespace Project\Controllers;
	use \Core\Controller;
	use \Core\View;
	
	class ErrorController extends Controller {
		public function notFound() {
			$this->title = 'Сторінка не знайдена';
			$page =  $this->getPage('error/notFound');

			echo (new View)->render($page);
		}

		public function internalError() {
			$this->title = 'Помилка сервера';
			$page =  $this->getPage('error/internalError');
			
			echo (new View)->render($page);
		}
	}
