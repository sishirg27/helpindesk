<?php

include_once("connect.php");
include_once("security.php");

//if(!isset($_SESSION['sess_user'])){
//header("location:../login.php");
//}
//else {
$user = $_SESSION['sess_user'];
$get_user = $db_conn -> query("SELECT user_id,full_name,user_name FROM users WHERE user_name = '$user'");
$row_uid = $get_user -> fetch_object();

$user_ids = escape($row_uid->user_id);
$user_name = escape($row_uid->user_name);
$full_name = escape($row_uid->full_name);

//}
?>
