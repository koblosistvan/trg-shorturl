<?php
	include_once('../connection.php');

	$id = htmlspecialchars($_POST['id']);
	$name = htmlspecialchars($_POST['name']);
	$short_name = htmlspecialchars($_POST['short_name']);
	$url = htmlspecialchars($_POST['url']);
	$valid_from = htmlspecialchars($_POST['valid_from']);
	$valid_to = htmlspecialchars($_POST['valid_to']);

	$sql = "SELECT 1 from url where id = '$id'";
	$res = mysqli_fetch_all(mysqli_query($conn, $sql));

	if ($res) {
		$sql = "UPDATE url SET name='$name', short_name='$short_name', url='$url', valid_from='$valid_from', valid_to='$valid_to' where id='$id'";
	} else {
		$sql = "INSERT INTO url VALUES ('$id','$name','$short_name','$url','$valid_from','$valid_to')";
	}
	$status = mysqli_query($conn, $sql);

	if($status){
		$msg = array('status' => $status, 'msg' => 'A módosítást elmentettük.');
	} else {
		$msg = array('status' => $status, 'msg' => 'Nem sikerült elmenteni a módosítást.', 'sql' => $sql);
	}

	echo json_encode($msg);
?>