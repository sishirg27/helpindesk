<?php
function getBestans($postid, $cate_id, $profile_pic,$back){
  include ("connect.php");


  //******************************************************************************
  // Getting the best answer
  //*********************************************************************************
  $get_comment = $db_conn -> query("SELECT max(likes),post_id FROM cau_comments WHERE post_id = '$postid'");
  $row_posts = $get_comment -> fetch_assoc();
  $post_id_comm  = escape($row_posts['post_id']); //modified post_id 1
  $likes         = escape($row_posts[('max(likes)')]);

  $get_ids = $db_conn -> query("SELECT * FROM cau_comments WHERE likes = '$likes' AND post_id = '$post_id_comm'");
  $row_ids = $get_ids  -> fetch_assoc();
  $comment_id    = escape($row_ids['comm_id']);
  $post_comm_ids = escape($row_ids ['post_id']); // modified post_id 2


  if($likes == 0){
    $delete_leaderboard = $db_conn -> query("DELETE FROM cau_leaderboard WHERE post_id='$post_comm_ids'");
  }

  if($likes >= 1){     // If the max like is 1 or more

    $select_correct_comm = "SELECT cau_comments.comm_id, cau_comments.comment, cau_comments.user_id, cau_comments.post_id, users.user_name, caucus_class.cate_id, caucus_class.best_ans,users.profile_pic
    FROM cau_comments
    LEFT JOIN users
    ON cau_comments.user_id = users.user_id
    LEFT JOIN caucus_class
    ON caucus_class.cate_id = $cate_id
    WHERE comm_id = '$comment_id' AND post_id = '$postid'";

    $select_correct_comm = $db_conn -> query($select_correct_comm); // post id is the original post_id, unmodified from the cau_comments

    $row_comm = $select_correct_comm -> fetch_assoc();
    $post_comment = $row_comm['comment'];
    $profile_pic_comm = escape($row_comm['profile_pic']);
    $comm_id      = escape($row_comm['comm_id']);
    $user_id      = escape($row_comm['user_id']);
    $post_comm_id = escape($row_comm['post_id']); // modified post_id 3
    $user_name_commenter = escape($row_comm['user_name']);
    $best_ans_color      = escape($row_comm['best_ans']);


    if($profile_pic_comm == "default_pic.png"){
      $update_profile_pic_comm = $back."images/default_pic.png";
    }
    else {
      $update_profile_pic_comm = $back."user_data/profile_pic/". $profile_pic_comm;
    }

    ?>
    <div id = 'best_ans' style = 'background:<?php echo $best_ans_color;?>'>
      <p style = 'font-family: Calibri light; font-size: 16px; margin-top: 0px;'><span style = 'font-size: 16px;  color: gray;'>  Best Answer  </span> </p>
      <img src='<?php  echo $update_profile_pic_comm; ?>' alt = '<?php echo $user_name_commenter; ?>' style = 'float: left; margin-top: -10px; height: 25px; border: 1px solid lightgray;vertical-align: middle; width: 25px; border-radius: 100px;'/>
      &nbsp;
      <div id = 'img_author' style = "margin-top: -30px;">
        &nbsp;<span style = 'color: blue; font-size: 16px; font-family: Calibri; font-weight: 400;'> <?php echo  ucfirst($user_name_commenter);?> </span>
      </div>
      <div id = 'auth_comm' style = "margin-top: 5px; margin-left: 30px;font-family: Segoe UI;">
        <?php echo htmlspecialchars_decode($post_comment);?> </p>
      </div>
    </div>
    <?php


    //******************************************************
    // Inserting and updating the leaderboard
    //*******************************************************

    $select_leaderboard = $db_conn -> query("SELECT post_id FROM cau_leaderboard WHERE post_id = '$postid'");

    $row_lead = $select_leaderboard -> fetch_assoc();
    $post_id_leader = escape($row_lead['post_id']);

    if($post_id_leader != $postid){
      $insert_leaderboard = $db_conn -> prepare("INSERT INTO cau_leaderboard(leader_id, sub_id, cate_id, post_id, comment_id) VALUES(?, ?, ?, ?, ?)");
      $insert_leaderboard -> bind_param("iiiii", $user_id, $sub_id, $cate_id, $post_comm_id, $comm_id);
      $insert_leaderboard -> execute();
    }

    else if($post_id_leader == $postid) {
      $update_leaderboard = $db_conn -> query("UPDATE cau_leaderboard SET leader_id = '$user_id', comment_id = '$comm_id' WHERE post_id = '$post_comm_id' AND cate_id = '$cate_id'");
    }

  }
}
?>
