<?php
/* Database connection start */
$servername = "localhost";
$username = "3ih67oot";
$password = "97iHNZmUO2";
$dbname = "3ih67oot";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>