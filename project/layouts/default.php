<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $title; ?></title>
		<link rel="icon" type="image/x-icon" href="/project/public/images/favicon.png">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	</head>
	<body class="p-3">
		<?php if (!empty($_SESSION['auth'])): ?>
			<header class="row px-3 pb-3 border-bottom mb-3">
				<div class="text-left w-50 d-flex align-items-center">
					<a href="/" class="font-weight-bold">Головна</a>
				</div>
				<div class="text-right w-50">
					<span>Привіт, <?= $_SESSION['user_data']['name']; ?>!</span>
					<a href="/logout" class="btn btn-primary">Вийти</a>
				</div>
			</header>
		<?php endif; ?>

		<?= $content; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="/project/public/js/script.js"></script>
	</body>
</html>
