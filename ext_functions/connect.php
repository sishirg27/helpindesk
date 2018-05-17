<?php
// connect.php

$servername = "localhost";
$username = "root";
$password = "Code1001";
$database_name = "caucusbold";

// using mysqli ot connect to the database
$db_conn = new mysqli($servername, $username, $password, $database_name);

// Check_connection
if($db_conn ->connect_errno){
  die('Sorry, We are having some problems.');
}

if(mysqli_connect_error()){
  die("Database connection faied: ".mysqli_connect_error());
}


?>
