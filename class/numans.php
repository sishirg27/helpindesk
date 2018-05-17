<?php
session_start();
include "../ext_functions/connect.php";

// if this is not register or in activity: We are doing Redirection
if(!isset ($_SESSION['sess_user'])) {
  header("location: ../login.php");
}
else {

  if(isset($_GET['p_id']))

    {
    $post_id = mysqli_real_escape_string($db_conn, $_GET['p_id']);

    $select_num_ans = $db_conn -> query("SELECT * FROM  cau_comments WHERE post_id = '$post_id'");
    $posts = $select_num_ans -> num_rows;

    echo $posts;
   }

  ?>

  <?php
}
?>
