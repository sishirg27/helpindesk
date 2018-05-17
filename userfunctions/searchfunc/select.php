<?php
session_start();

include "../../ext_functions/connect.php";
include "../../ext_functions/security.php";
include "../../ext_functions/select_user.php";

if(isset($_GET['select'])){
$sql1 = $db_conn -> query("SELECT status, respond_uid FROM likecomm_notification WHERE  status = 'unseen' AND respond_uid = '$user_ids' AND (type = 'comment' OR type = 'like')");
$count = $sql1 -> num_rows;
echo escape($count);
$db_conn->close();
}

if(isset($_GET['select_private'])){
$sql1 = $db_conn -> query("SELECT status, respond_uid FROM likecomm_notification WHERE status = 'unseen' AND respond_uid = '$user_ids' AND (type = 'prique' OR type = 'pricomm' OR type = 'resComm')");
$count = $sql1 -> num_rows;
echo escape($count);
$db_conn->close();
}
?>
