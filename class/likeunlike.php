<?php
session_start();
//error_reporting(0); // If you do not want the user to see any error
error_reporting(E_ALL); // to display all the error in a elegant fashion
ini_set('display_errors', 'On');

include("../ext_functions/connect.php");
include("../ext_functions/security.php");
include("../ext_functions/select_user.php");
include("../ext_functions/time.php");

// if this is not register or in activity: We are doing Redirection
if(!isset ($_SESSION['sess_user'])) {
  header("location: ../login.php");
}
else {

  $total_likes = 0;
  $total_unlikes = 0;

  if(isset($_GET['comment_id']) && isset($_GET['post_id'])){

    $unique_id = escape($_GET['comment_id']);
    $post_id =  escape($_GET['post_id']);

    $select_uid = $db_conn -> query("SELECT * FROM cau_comments WHERE comm_id = '$unique_id' AND post_id = '$post_id'");

    if($select_uid -> num_rows == 1){
      $row = $select_uid -> fetch_object();
      $unique_id = escape($row->comm_id);
      $total_likes= escape($row->likes);


    } else { $select_uid->close();}

    $select_comm_id_user = $db_conn -> query("SELECT user_id FROM cau_comments WHERE comm_id = '$unique_id'");

    $comm_user_id = $select_comm_id_user -> fetch_object();
    $user_comm_id = escape($comm_user_id->user_id);
    $status = "unseen";
    $type = "like";


    $select_checkuserlike = $db_conn -> query("SELECT id FROM comm_userlikes WHERE user_id = '$user_ids' AND liked_commentid = '$unique_id'");
    $run_numrowlikes = $select_checkuserlike -> num_rows;

    $select_date_query = $db_conn -> query("SELECT date FROM cau_comments WHERE comm_id = '$unique_id'");
    $row = $select_date_query -> fetch_object();
    $dates = escape($row->date);
    date_default_timezone_set('America/Los_Angeles'); // Very important

    if(isset($_POST['liked'])){
      $select_checkrepetativeuserlike = $db_conn -> query("SELECT * FROM comm_userlikes WHERE user_id='$user_ids'  AND liked_commentid = '$unique_id'");

       if($select_checkrepetativeuserlike -> num_rows == 0){

         $total_likes = $total_likes + 1;
         $update_like = $db_conn -> query("UPDATE cau_comments SET likes= '$total_likes'  WHERE comm_id ='$unique_id'");

        $insert_userlikes = $db_conn -> prepare("INSERT INTO comm_userlikes(user_id, user_comm_id, liked_commentid, like_date) VALUES (?, ?, ?, NOW())");
        $insert_userlikes ->  bind_param("iii", $user_ids, $user_comm_id, $unique_id);
        $insert_userlikes -> execute();
        $insert_userlikes -> close();

        if($user_ids != $user_comm_id){   // to make sure that the user donot get a notification when he likes his own post
          $insert_notif_like = $db_conn -> prepare("INSERT INTO likecomm_notification(post_id, comm_id, user_id, respond_uid,status,type,date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
          $insert_notif_like -> bind_param('iiiiss', $post_id, $unique_id, $user_ids, $user_comm_id, $status, $type);
          $insert_notif_like -> execute();
          $insert_notif_like -> close();
        }

     }
    }

    if(isset($_POST['unliked'])){
      $remove_likes = $total_likes - 1;
      $update_unlike = $db_conn -> query("UPDATE cau_comments SET likes = '$remove_likes'  WHERE comm_id = '$unique_id'");
      $remove_user = $db_conn -> query("DELETE FROM comm_userlikes WHERE user_id ='$user_ids' AND liked_commentid = '$unique_id'");
      $remove_notif = $db_conn -> query("DELETE FROM likecomm_notification WHERE user_id = '$user_ids' AND respond_uid = '$user_comm_id'");
    }


  }

  ?>

  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/likeunlikebox.css">
    <script src= "../js/jquery.min.js"></script>
  </head>
  <body>


    <?php if($run_numrowlikes == 0) { ?>
      <div id ='prelike'   title="Shreejit Giri">
        <input type="button" value='&#x02605;' id = "like"/>
        <div style = 'display:inline; font-size: 22px; font-family: Calibri;'>
          <a id = "totlike" style = "margin-left: 5px;"> <?php echo $total_likes; ?> </a>
          &nbsp; &nbsp; &nbsp;
          <span style = 'color: gray; font-size: 14px; font-weight: 400;font-family: Segoe UI;'><?php echo time_ago($dates); ?></span>
        </div>
      </div>

    <?php } ?>

    <?php if($run_numrowlikes >= 1){ ?>
      <div id ='postlike'  title="Sishir Giri">
        <input type="button" value='&#x02605;' id = "unlike"/>
        <div style = 'display:inline; font-size: 22px;font-family: Calibri; font-weight: 600; color:#E06E00;'>
          <a id = "totlike"  style = "margin-left: 5px;">  <?php echo $total_likes; ?> </a>
          &nbsp; &nbsp; &nbsp;
          <span style = 'color: gray; font-size: 14px; font-weight: 400;font-family: Segoe UI;'><?php echo time_ago($dates); ?></span>
        </div>
      </div>

      <?php
    }
  }
  ?>

  <script type="text/javascript">
  $('#like').click(function() {
    $.ajax({
      type: 'POST',
      url: '',
      data: {
        'liked': 1
      },
      success:function(data){
        $('#prelike').hide();
       $('#postlike').show();
       window.location.reload(); // This is not jQuery but simple plain ol' JS
      }
    });
  });

  $('#unlike').click(function() {
    $.ajax({
      type: 'POST',
      url: '',
      data: {
        'unliked': 1
      },
      success:function(data){
       $('#postlike').hide();
      $('#prelike').show();
       window.location.reload(); // This is not jQuery but simple plain ol' JS
      }
    });
  });


  </script>


</body>
</html>
