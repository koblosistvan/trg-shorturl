<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Trg Shorturl</title>
</head>

<body>
	<div class="container">
		<?php
			// Set internal encoding to UTF-8
			mb_internal_encoding("UTF-8");
			// Output the hashed password
			echo password_hash($_GET['pw'], PASSWORD_BCRYPT, ["cost" => 12]);
		?>
	</div>
</html>