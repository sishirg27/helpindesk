<?php
session_start();

include_once("../ext_functions/connect.php");
include_once("../ext_functions/security.php");
include_once("../ext_functions/select_user.php");

 if(isset($_POST['post_id']) && ($_POST['user_id'])){
   $post_id =  escape($_POST['post_id']);
   $u_id =  escape($_POST['user_id']);

   if($u_id == $user_ids){
    $deletemyquery = $db_conn -> query("DELETE FROM cau_questions WHERE id = '$post_id' AND user_id = '$u_id'");
    $deletemyleader = $db_conn -> query("DELETE FROM cau_leaderboard WHERE post_id ='$post_id'");
    $deleteNotif = $db_conn -> query("DELETE FROM likecomm_notification WHERE post_id = '$post_id' AND user_id = '$u_id'");
  }
 }
?>
