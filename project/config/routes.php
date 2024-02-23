<?php
	use \Core\Route;
	
	return [
		new Route('/', 'film', 'showAll'),
		new Route('/film/show/:id', 'film', 'showById'),
		new Route('/film/add', 'film', 'add'),
		new Route('/film/delete', 'film', 'deleteMany'),
		new Route('/film/search', 'film', 'search'),
		new Route('/film/import', 'film', 'import'),
		new Route('/login', 'page', 'login'),
		new Route('/logout', 'page', 'logout'),
		new Route('/register', 'page', 'register')
	];
