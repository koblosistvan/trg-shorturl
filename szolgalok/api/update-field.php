<?php
	//include connection file 
	include_once("../connection.php");

	$event_id = $_POST['event'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	date_default_timezone_set('Europe/Budapest');
	$date = date('Y.m.d H:i:s', time());
	$message = $date.': '.$value;

	$sql = "SELECT id FROM event_fields WHERE event_id = $event_id and field = '$field'";
	$res = mysqli_query($conn, $sql);		
	$row = mysqli_fetch_all($res, MYSQLI_ASSOC);

	if($row) {
		$sql = "UPDATE event_fields SET value = '$value', log = concat(log, '\n', '$message') WHERE event_id = $event_id and field = '$field'";
	} else {
		$sql = "INSERT INTO event_fields(event_id, field, value, log) VALUES ($event_id, '$field', '$value', '$message')";
	}
	$status = mysqli_query($conn, $sql);

	if($status){
		$msg = array('status' => $status, 'msg' => 'A módosítást elmentettük.');
	} else {
		$msg = array('status' => $status, 'msg' => 'Nem sikerült elmenteni a módosítást.', 'sql' => $sql);
	}

	// send data as json format
	echo json_encode($msg);
?>