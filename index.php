<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.bootgrid.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.bootgrid.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<title>Tatai Református Gyülekezet szolgálói beosztás</title>
</head>

<body>
<div class="container">
<div id="msg" class="alert"></div>

<?php
include_once("connection.php");
$sql = "SELECT * from url";
$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");

$urls = mysqli_fetch_all($res, MYSQLI_ASSOC);
var_dump($urls);
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
                    </tr>
				</thead>
				<tbody id="data_table">
                    <?php foreach($urls as $u) :?>
						<tr id="data-row-<?php echo $u['id'];?>" data-row-id = "<?php echo $u['id'];?>">
							<?php
                            echo '<td class="edit-data" contenteditable="true" col="name">'.$u['name'].'</td>'; 
                            echo '<td class="edit-data" contenteditable="true" col="short_name">'.$u['short_name'].'</td>'; 
                            echo '<td class="edit-data" contenteditable="true" col="url">'.$u['url'].'</td>'; 
                            echo '<td class="edit-data" contenteditable="true" col="valid_from">'.$u['valid_from'].'</td>'; 
                            echo '<td class="edit-data" contenteditable="true" col="valid_to">'.$u['valid_to'].'</td>'; 
                            ?>
							
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>

</html>