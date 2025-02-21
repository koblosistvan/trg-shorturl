<?php
	//include connection file 
	include_once("connection.php");

	$sql = "INSERT INTO szolgalo (datum) select date_add(max(datum), interval 7 day) as datum from szolgalo";
	$status = mysqli_query($conn, $sql); 

	$sql = "SELECT id, datum FROM szolgalo ORDER BY id desc LIMIT 1";
	if($result = mysqli_query($conn, $sql)){
		$row = mysqli_fetch_object($result);
		$res = array('status' => true, 'msg' => 'Új alkalom létrehozva.', 'id' => $row->id, 'datum' => $row->datum, 'row' => $row);
	} else {
		$res = array('status' => false, 'msg' => 'Nem sikerült új alkalmat létrehozni.');	
	}
	echo json_encode($res);
?>