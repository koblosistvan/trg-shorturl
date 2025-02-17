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
<div id="msg" class="alert"></div>

<?php
include_once("connection.php");
$sql = "SELECT id, code, name, fields, labels FROM event_views";
$res = mysqli_query($conn, $sql) or die("hiba az adatbázis elérésekor");
$views = mysqli_fetch_all($res, MYSQLI_ASSOC);
foreach ($views as $view) {
	if (isset($_GET[$view['code']])) { 
		$view_code = $view['code'];
		$fields = explode(',', $view['fields']);
		$labels = explode(',', $view['labels']);
		echo $view['name'].' | ';
	} else {
		echo '<a href="?'.$view['code'].'">'.$view['name'].'</a> | ';
	}
};

if (isset($fields)) {
	if (isset($_GET["titok"])) {
		$sql_events = "SELECT id, date, description, views FROM events where views like '%$view_code%' order by date";
		$sql_fields = "SELECT f.id, e.date, f.field, f.value FROM event_fields as f join events as e on e.id = f.event_id";
	}
	else {
		$sql_events = "SELECT id, date, description, views FROM events where date >= curdate() and views like '%$view_code%' order by date";
		$sql_data = "SELECT f.id, e.date, f.field, f.value, f.log FROM event_fields as f join events as e on e.id = f.event_id  where e.date >= curdate()";
	}
	$res = mysqli_query($conn, $sql_events) or die("hiba az adatbázis elérésekor");
	$events = mysqli_fetch_all($res, MYSQLI_ASSOC);
	
	$res = mysqli_query($conn, $sql_data) or die("hiba az adatbázis elérésekor");
	$data = mysqli_fetch_all($res, MYSQLI_ASSOC);
	$data_assoc = [];
	foreach ($data as $row) {
		$data_assoc[$row['date'].'-'.$row['field']] = $row;
	}
	?>
	
	<div class="container">
		<div class="table-responsive">
			<table id="grid" class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th>Dátum</th>
						<?php foreach ($labels as $label) { echo "<th>$label</th>"; }; ?>
					</tr>
				</thead>
				<tbody id="data_table">
					<?php foreach($events as $event) :?>
						<tr id="data-row-<?php echo $event['id'];?>" data-row-id = "<?php echo $event['id'];?>">
							<?php echo '<td col="date">'.$event['date'];
							if (strlen($event['date']) > 0) {
								echo '<br><span class="description">'.$event['description'].'</span>';
							}
							echo '</td>'; ?>
							<?php foreach ($fields as $field) { 
								$data_id = $event['date'].'-'.$field;
								if (isset($data_assoc[$data_id])) {
									$val = $data_assoc[$data_id];
									if (isset($_GET["log"])) {
										$title = 'title="'.$val['log'].'" ';
									} else {
										$title = '';
									}
									echo '<td '.$title.'class="edit-data" contenteditable="true" event="'.$event['id'].'" field="'.$field.'">'.$val['value'].'</td>';
								} else {
									echo '<td class="edit-data" contenteditable="true" event="'.$event['id'].'" field="'.$field.'"></td>';
								}
								
							}; ?>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>

<?php } ?>

</html>