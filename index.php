	<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	include_once("connection.php");
	//var_dump($_GET);
	if (isset($_GET['short_name'])) {
		$sql = "SELECT * from url_ordered where short_name = '".$_GET['short_name']."' limit 1";
		

		$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
		$url = mysqli_fetch_all($res, MYSQLI_ASSOC);
		echo '<meta http-equiv="refresh" content="0; url='.$url[0]['url'].'">';
	} else {
		echo '<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>';
		echo '<script type="text/javascript" src="js/jquery.bootgrid.min.js"></script>';
		echo '<script type="text/javascript" src="js/script.js"></script>';
		echo '<link rel="stylesheet" type="text/css" href="css/jquery.bootgrid.min.css">';
		echo '<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>';
		echo '<link rel="stylesheet" type="text/css" href="css/main.css">';
	
	}
	// ha nincsen valasz akkor handle
	// duplikatok order by

	?>
	<title>Trg Shorturl</title>
</head>

<body>
<?php
//		var_dump($url);
?>
<div class="container">
<div id="msg" class="alert"></div>

<?php



$sql = "SELECT * FROM url";
$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");

$urls = mysqli_fetch_all($res, MYSQLI_ASSOC);
//var_dump($urls);

/**
 * duplikatok kezelese:
 * ha a linkbol van olyan, ami aktiv, akkor REDIRECTEL
 * kulonben ha a linkbol van olyan, ami aktiv lesz, azt irja ki hogy MEG NEM AKTIV
 * kulonben ha a linkbol csak olyan van, ami inaktiv, azt irja ki, hogy MAR NEM AKTIV
 */
?>
	
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
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>

</html>