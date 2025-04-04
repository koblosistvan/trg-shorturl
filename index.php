<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	include_once("connection.php");
	#error_reporting(0);
#	var_dump($_GET);
	if (isset($_GET['short_name'])) {
		$sql = "SELECT * from url_ordered where short_name = '".$_GET['short_name']."' limit 1";
#		var_dump($sql);
		$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
		$data = mysqli_fetch_all($res, MYSQLI_ASSOC);
		$url = $data[0]['url'];
		$status = $data[0]['status'];
		$valid_from = $data[0]['valid_from'];
		$valid_to = $data[0]['valid_to'];
		if ($status == "aktív") {
			echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
		}
		
	} else {
		echo '<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>';
		echo '<script type="text/javascript" src="js/jquery.bootgrid.min.js"></script>';
		echo '<script type="text/javascript" src="js/script.js"></script>';


	}
	// css
	// rekord hozzadas
	// log

	?>
	<link rel="stylesheet" type="text/css" href="css/jquery.bootgrid.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<title>Trg Shorturl</title>
</head>

<body>
<div class="container">


<?php
	session_start();
	// registration.php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    	$username = $_POST['username'];
    	$password = $_POST['password'];#password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Database connection


    // Insert user data
    	$sql = "SELECT * from users where username = '".$username."' and password = '".$password."'";

		$result = $conn->query($sql);

    if ($result->num_rows > 0) {
            $_SESSION['username'] = $username;
		}
}


	if (isset($_SESSION['username'])) {
		echo "Welcome, " . $_SESSION['username'] . "!";
		// Display user-specific content




	if (isset($_GET['short_name'])) {
		echo '<div id="vers-div">';
		if ($status == "jövőbeli") {
			echo '<div class="status-msg">Sajnáljuk, ez a link még nem elérhető.<br>Kérjük, látogass vissza '.$valid_from.' után.<br><br>';
			echo 'Vigasztalásnak itt egy állatos vers:</div><br>';
			echo '<div id="poet791720" class="vers"><script language="JavaScript" type="text/JavaScript" src="https://www.poet.hu/js.php?r=791720&async=1&kategoria=%C1llatok" async></script></div>';
		} else if ($status == "lejárt") {
			echo '<div class="status-msg">Sajnáljuk, ez a link '.$valid_to.' dátummal lejárt, már nem elérhető.<br><br>';
			echo 'Vigasztalásnak itt egy természetvédelmi vers:</div><br>';
			echo '<div id="poet215972" class="vers"><script language="JavaScript" type="text/JavaScript" src="https://www.poet.hu/js.php?r=215972&async=1&kategoria=Term%E9szetv%E9delem" async></script></div>';
		} else {
			echo '<div class="status-msg">Sajnáljuk, ez a link nem működik.<br><br>';
			echo 'Vigasztalásnak itt egy humoros vers:</div><br>';
			echo '<div id="poet689335" class="vers"><script language="JavaScript" type="text/JavaScript" src="https://www.poet.hu/js.php?r=689335&async=1&kategoria=Humor" async></script></div>';
		}
		echo '<script>document.body.classList.add("hatter");</script>';
		echo '</div>';
	} else {
		$sql = "SELECT * from url order by short_name, valid_from desc";
#		var_dump($sql);
		$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
		$urls = mysqli_fetch_all($res, MYSQLI_ASSOC);

	
?>

<div id="msg" class="alert"></div>
	<div class="container">
		<div class="table-responsive">
			<table id="grid" class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th>Név</th>
                        <th>Rövidítés</th>
                        <th>Webcím</th>
                        <th>Érvényesség kezdete</th>
                        <th>Érvényesség vége</th>
						<th>Státusz</th>
                    </tr>
				</thead>
				<tbody id="data_table">
                    <?php foreach($urls as $u) :?>
						<tr class="edit-field" id="data-row-<?php echo $u['id'];?>" data-row-id = "<?php echo $u['id'];?>">
							<?php
                            echo '<td class="edit-data" contenteditable="true" col="name">'.$u['name'].'</td>'; 
                            echo '<td class="edit-data" contenteditable="true" col="short_name">'.$u['short_name'].'</td>'; 
                            echo '<td class="edit-data" contenteditable="true" col="url">'.$u['url'].'</td>'; 
                            echo '<td class="edit-data tol" contenteditable="true" col="valid_from">'.$u['valid_from'].'</td>'; 
                            echo '<td class="edit-data ig" contenteditable="true" col="valid_to">'.$u['valid_to'].'</td>'; 
							echo '<td col="status"></td>';
							?>
							
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

<?php } 

} else {
	echo "You are not logged in.";
	// Redirect to login page or show login form
	?>	
	<form action="index.php" method="post">

  	<div class="container">
    	<input type="text" placeholder="Felhasználónév" name="username" required>

    	<input type="password" placeholder="Jelszó" name="password" required>

    	<button type="submit">Belépés</button>
  </div>
</form>

	<?php 
}

?>
</div>

</html>