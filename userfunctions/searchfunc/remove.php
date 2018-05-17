
 <?php
session_start();

include "../../ext_functions/connect.php";
include "../../ext_functions/security.php";
include "../../ext_functions/select_user.php";

if(isset($_GET['remove'])){
$sql = $db_conn -> query("UPDATE likecomm_notification SET status = 'seen' WHERE status = 'unseen' AND respond_uid = '$user_ids' AND (type = 'comment' OR type = 'like')");
if ($sql == TRUE) {}

  $sql1 = $db_conn -> query("SELECT * FROM likecomm_notification WHERE status = 'unseen' AND respond_uid ='$user_ids' AND (type = 'comment' OR type = 'like')");
  $count1 = $sql1 -> num_rows;
  echo escape($count1);

  $db_conn -> close();
}

if(isset($_GET['remove_private'])){
$sql = $db_conn -> query("UPDATE likecomm_notification SET status = 'seen' WHERE status = 'unseen' AND respond_uid = '$user_ids' AND (type = 'prique' OR type = 'pricomm' OR type = 'resComm')");
if ($sql == TRUE) {}

  $sql1 = $db_conn -> query("SELECT * from likecomm_notification where status = 'unseen' AND respond_uid ='$user_ids' AND (type = 'prique' OR  type = 'pricomm' OR type = 'resComm')");
  $count1 = $sql1 -> num_rows;
  echo escape($count1);

  $db_conn -> close();
}

?>
