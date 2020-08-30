<?php
$conn = mysqli_connect("localhost", "username", "password", "DBname");
if(!$conn){
  echo "Connection error" . mysqli_connect_error();
}
 ?>
