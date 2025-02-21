<?php
	//include connection file 
	include_once("../connection.php");

	$id = $_POST['id'];
	$date = $_POST['date'];
	$description = $_POST['description'];
	$views = $_POST['views'];

	$sql = "UPDATE events SET date = '$date', description = '$description', views = '$views' WHERE id=$id";
	$status = mysqli_query($conn, $sql);

	if($status){
		$msg = array('status' => $status, 'msg' => 'A módosítást elmentettük.');
	} else {
		$msg = array('status' => $status, 'msg' => 'Nem sikerült elmenteni a módosítást.', 'sql' => $sql);
	}

	// send data as json format
	echo json_encode($msg);
?>