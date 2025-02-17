<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.bootgrid.min.js"></script>
	<script type="text/javascript" src="js/szolgalok.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.bootgrid.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<title>Tatai Református Gyülekezet szolgálói beosztás</title>
</head>

<body>
<div class="container">

<?php
include_once("connection.php");
if (isset($_GET["new_dates"])) {
	for ($i=0; $i<$_GET["new_dates"]; $i++ ) {
		$sql = "INSERT INTO events (date) select date_add(max(date), interval 7 day) as date from events";
		$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
	}
}

$sql = "SELECT id, code, name, fields, labels FROM event_views";
$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
$views = mysqli_fetch_all($res, MYSQLI_ASSOC);

$sql = "SELECT e.id, e.date, e.description, e.views, count(f.id) as count_of_fields".
	   " FROM events as e LEFT JOIN event_fields as f on e.id = f.event_id".
	   " WHERE date >= curdate()".
	   " GROUP BY e.id".
	   " ORDER BY date";
$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
$events = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>

<div id="debug">
	<p><?php var_dump($views); ?></p>
	<p><?php var_dump($events);?></p>
</div>
<div id="msg" class="alert"></div>

<div class="container">
<div class="table-responsive">
		<table id="grid" class="table table-condensed table-hover table-striped">
			<thead>
				<tr>
				<th>Törlés</th>
				<th>Dátum</th>
				<th>Megjegyzés</th>
					<?php foreach($views as $view) {
						echo "<th>".$view['name']."</th>";
					}; ?>
				</tr>
			</thead>
			<tbody id="event_table">
				<?php foreach($events as $event) { ?>
					<tr id="data-row-<?php echo $event['id'];?>" data-row-id="<?php echo $event['id'];?>">
						<td id="delete-<?php echo $event['id'];?>">
							<?php if ($event['count_of_fields'] == 0) { echo '<img data-row-id="'.$event['id'].'" class="remove-event hidden-md" src="pic/remove-event.png"/>'; } ?>
						</td>
						<td class="edit-event" contenteditable="true" id="date-<?php echo $event['id'];?>"><?php echo $event['date'];?></td>
						<td class="edit-event" contenteditable="true" id="description-<?php echo $event['id'];?>"><?php echo $event['description'];?></td>
						<?php foreach ($views as $view) { 
							$col_name = $view['code'];
							$is_checked = str_contains($event['views'], $view['code']) ? 'checked="checked"' : '';
							echo '<td class="editable-col"><input id="view-'.$view['code'].'-'.$event['id'].'"col="'.$view['code'].'" type="checkbox" '.$is_checked.' onchange="updateEvent(this)"></td>';
						}; ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<div class="container">
	<form action="alkalmak.php" method="get">
		<input type="number" name="new_dates" id="new_dates">
		<input type="submit" value="Új alkalmak">
	</form>	
</div>

</html>