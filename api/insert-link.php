<?php
    include_once('../connection.php');

	$name = htmlspecialchars($_POST['name']);
	$short_name = htmlspecialchars($_POST['short_name']);
	$url = htmlspecialchars($_POST['url']);
	$valid_from = htmlspecialchars($_POST['valid_from']);
	$valid_to = htmlspecialchars($_POST['valid_to']);

	$sql = "INSERT INTO url (name, short_name, url, valid_from, valid_to) VALUES ('$name','$short_name','$url','$valid_from','$valid_to')";
	
	$status = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="0; url='../index.php'">
    <title>Document</title>
</head>
<body>
    
</body>
</html>

