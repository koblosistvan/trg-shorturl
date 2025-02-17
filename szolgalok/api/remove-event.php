<?php
  //include connection file 
  include_once("../connection.php");
  
  if(isset($_POST)){
    $id = $_POST['id'];
	
	$sql = "DELETE FROM events WHERE id = ".$id;
    $status = mysqli_query($conn, $sql); 

    if($status){
      $res = array('status' => $status, 'msg' => 'Az alkalom törölve.');
    } else {
      $res = array('status' => $status, 'msg' => 'Hiba a törlés közben.');	
    }
  }
  echo json_encode($res);
  
?>