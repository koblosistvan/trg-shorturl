<?php
	include_once("connection.php");
	mb_internal_encoding("UTF-8");
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
		$action = 'login';
	} else if (isset($_GET['logout'])) {
		// if the user is logged in, we will log them out
		session_start();
		session_destroy();
		$action = "no-short-name";
	} else if (isset($_GET['short_name'])) {
		// if the short name is set, we will redirect to the original URL
		$sql = "SELECT * from url_ordered where short_name = '" . $_GET['short_name'] . "' limit 1";
		$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
		$shorturl = mysqli_fetch_all($res, MYSQLI_ASSOC);
		if (count($shorturl) == 0) {
			$action = "invalid";
			$sql = "INSERT into log VALUES (0, '".$_GET['short_name']."', CURRENT_TIMESTAMP(), '".$action."', '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER["HTTP_USER_AGENT"]."')";
		} else {
			$shorturl = $shorturl[0]; // Get the first element since we used limit 1
			if ($shorturl['status'] == "aktív") {
				$action = "redirect";
			} else {
				$action = "inactive";
			}
			$sql = "INSERT into log VALUES (".$shorturl['id'].", '".$_GET['short_name']."', CURRENT_TIMESTAMP(), '".$action."', '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER["HTTP_USER_AGENT"]."')";
		}
		$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
	} else {
		$action = "no-short-name";
	}

	if (isset($_GET['reset'])) {
		$action = "reset-password";
	}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ($action == "redirect") {
		// if there is a valid short name, redirect the page and log the access
		echo '<meta http-equiv="refresh" content="0; url='.$shorturl['url'].'">';
		
	} else { ?>
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.bootgrid.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
		<link rel="stylesheet" type="text/css" href="css/jquery.bootgrid.min.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<?php } ?>
	<title>Trg Shorturl</title>
</head>

<body>
	<div class="container">
		<?php
		session_start();
		if ($action == 'login') {
			// Handle user login
			$username = $_POST['username'];
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT, ["cost" => 12]);
			$sql = "SELECT * from users where username = '".$username."'";
			$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
			$user = mysqli_fetch_all($res, MYSQLI_ASSOC);
			if (count($user) == 0) {
				echo '<div class="alert alert-danger">Hibás felhasználónév vagy jelszó!</div>';
				$action = 'no-short-name';
			} else {
				$user = $user[0];
				if (!password_verify($_POST['password'], $user['password'])) {
					echo '<div class="alert alert-danger">Hibás felhasználónév vagy jelszó!</div>';
					$action = 'no-short-name';
				} else {
					$_SESSION['username'] = $username;
				}
			}
		}
		
		if ($action == 'inactive' || $action == 'invalid') {
			echo '<div id="vers-div">';
			if ($action == 'inactive' && $shorturl['status'] == "jövőbeli") { ?>
				<h1>Sajnáljuk, ez a link még nem elérhető.</h1>
				<h1>Kérjük, látogass vissza <?php echo $shorturl['valid_from'] ?> után.</h1>
				<hr>
				<h2>Vigasztalásnak itt egy vers:</h2>
				<div id="poet791720" class="vers"><script language="JavaScript" type="text/JavaScript" src="https://www.poet.hu/js.php?r=791720&async=1&kategoria=%C1llatok" async></script></div>
			<?php } else if ($action == 'inactive' && $shorturl['status'] == "lejárt") { ?>
				<h1>Sajnáljuk, ez a link <?php echo $shorturl['valid_to'] ?> dátummal lejárt, már nem elérhető.</h1>
				<hr>
				<h2>Vigasztalásnak itt egy vers:</h2>
				<div id="poet215972" class="vers"><script language="JavaScript" type="text/JavaScript" src="https://www.poet.hu/js.php?r=215972&async=1&kategoria=Term%E9szetv%E9delem" async></script></div>
			<?php } else { ?>
				<h1>Sajnáljuk, ez a link nem működik.</h1>
				<hr>
				<h2>Vigasztalásnak itt egy vers:</h2>
				<div id="poet689335" class="vers"><script language="JavaScript" type="text/JavaScript" src="https://www.poet.hu/js.php?r=689335&async=1&kategoria=Humor" async></script></div>
			<?php }
			echo '</div>';
		} else if (($action == 'no-short-name' || $action == 'login') && isset($_SESSION['username'])) {
			// User is logged in, display the main content
			echo '<div id="user-info">Belépve: '.$_SESSION['username'].' <a href="?logout"><i class="bi bi-box-arrow-right"></i></a></div>';

			$sql = "SELECT * from url_ordered";
			$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
			$urls = mysqli_fetch_all($res, MYSQLI_ASSOC);
			?>

			<div id="msg" class="alert"></div>
			<div>
				<h1>Rövid URL-ek kezelése</h1>
				<p>Szerkesztéshez kattints a cellába és csak írd át a tartalmát. Törölni nem lehet, csak az elérhetőség idejét lehet állítani.</p>
			</div>
			<div class="container">
				<div class="table-responsive">
					<table id="grid" class="table table-condensed table-hover table-striped">
						<thead>
							<tr>
								<th>GO</th>
								<th>Név</th>
								<th>Rövidítés</th>
								<th>Webcím</th>
								<th>Érvényesség kezdete</th>
								<th>Érvényesség vége</th>
								<th>Státusz</th>
								<th>Előző hét</th>
								<th>Összesen</th>
							</tr>
						</thead>
						<tbody id="data_table">
							<?php foreach ($urls as $u) : ?>
								<tr class="edit-field" id="data-row-<?php echo $u['id']; ?>" data-row-id="<?php echo $u['id']; ?>">
									<?php
									echo '<td><i class="bi bi-link-45deg url-go"></i> <i class="bi bi-copy url-copy"></i></td>';
									echo '<td class="edit-data" contenteditable="true" col="name">'.$u['name'].'</td>';
									echo '<td class="edit-data" contenteditable="true" col="short_name">'.$u['short_name'].'</td>';
									echo '<td class="edit-data" contenteditable="true" col="url">'.$u['url'].'</td>';
									echo '<td class="edit-data tol" contenteditable="true" col="valid_from">'.$u['valid_from'].'</td>';
									echo '<td class="edit-data ig" contenteditable="true" col="valid_to">'.$u['valid_to'].'</td>';
									echo '<td class="'.$u['status-class'].'" col="status">'.$u['status'].'</td>';
									echo '<td col="hits_last_week">'.$u['hits_last_week'].'</td>';
									echo '<td col="hits">'.$u['hits'].'</td>';
									?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<div>
						<h1>Új URL létrehozása</h1>
					</div>
					<form action="api/insert-link.php" method="post" id="new-record-form">
						<input type="text" id="name" placeholder="Név..." onfocusout="validate_new();" class="form-text">
						<span id="name-error"></span>
						<input type="text" id="short_name" placeholder="Rövidítés..." onfocusout="validate_new();" class="form-text">
						<span id="short_name-error"></span>
						<input type="text" id="url" placeholder="Webcím..." onfocusout="validate_new();" class="form-text">
						<span id="url-error"></span>
						<input type="text" id="valid_from" placeholder="Érvényesség kezdete..." onfocusout="validate_new();" class="form-text">
						<span id="valid_from-error"></span>
						<input type="text" id="valid_to" placeholder="Érvényesség vége..." onfocusout="validate_new();" class="form-text">
						<span id="valid_to-error"></span>

						<input type="submit" value="Mentés" id="submit-new" disabled>
					</form>
				</div>
			</div>
			<?php } else if ($action == 'no-short-name') { ?>
				<h1>Kérem, adjon meg egy rövid URL-t, vagy jelentkezzen be!</h1>
				<form action="" method="post">
					<div class="container">
						<input type="text" placeholder="Felhasználónév" name="username" required>
						<input type="password" placeholder="Jelszó" name="password" required>
						<button type="submit">Belépés</button>
					</div>
				</form>
			<?php } ?>
	</div>
</html>