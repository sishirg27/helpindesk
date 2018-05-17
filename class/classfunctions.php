
<?php
 
// IF and SWITCH to check ajax sending functions
if (isset($_GET['f'])) {
  switch ($_GET['f']) {

    case 'comm':
    insertcomm();
    break;

    case 'singleque':
    singlequestion();
    break;

    case 'discomm':
    displaycomments();
    break;

    case 'bestans':
    getbestans();
    break;

    case 'leaders':
    userleaderboard();
    break;

    case 'que':
    get_posts();
    break;

    case 'ctype':
    changetype_que();
    break;

   default:
   header('Location: ../home.php');
   exit;
   break;
  }
}

function insertcomm(){
  include_once("../ext_functions/connect.php");
  include_once("../ext_functions/security.php");
  include_once("../ext_functions/select_user.php");


  if(isset($_POST['comm_post'], $_POST['comment_comm'])){
    $get_id = escape($_POST['post_id']);
    $type_id = escape($_POST['type_id']);
    $comment = escape($_POST['comment_comm']);
    $u_id = escape($_POST['user_id']);

    $select_query = $db_conn -> query("SELECT user_id,question_uid,id FROM cau_questions WHERE id = $get_id");
    $row_post = $select_query -> fetch_object();

    $user_id  = escape($row_post->user_id); // This is the user who ask the question
    $ques_uid = escape($row_post->question_uid);
    $post_id  = escape($row_post->id);
    $totallike = 0;
    $status = "unseen";
    $uploadimg_comm = "";


    if(!empty($get_id) && !empty($comment) && $u_id == $user_ids) {
      $insert_comm = $db_conn -> prepare("INSERT INTO cau_comments(post_id, user_id, question_uid,comment, uploadimg_comm, likes, date)
                                          VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $insert_comm -> bind_param("iiissi", $post_id,  $u_id, $user_id, $comment, $uploadimg_comm, $totallike); // user_ids here is the user who write the comment
      $insert_comm -> execute();
      $insert_comm -> close();

      // to get the comment_id
      $selectcommid = $db_conn -> query("SELECT comm_id FROM cau_comments ORDER BY comm_id DESC LIMIT 1");
      $row_result_commid = $selectcommid-> fetch_object();
      $comment_id = $row_result_commid->comm_id;
      $selectcommid->close();

      // Making sure if the post comment is in a private post or public post
      if($type_id == 1){
        $type = 'pricomm';
      }

      if($type_id == 0){
        $type = 'comment';
      }

      if($user_ids == $user_id){ // A very important if statememtn which lets the private question to be commented by same person
        $user_id = $ques_uid;
      }

      $insert_notif_comm = $db_conn -> prepare("INSERT INTO likecomm_notification(post_id, comm_id, user_id, respond_uid,status,type,date)
                                                VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $insert_notif_comm -> bind_param('iiiiss', $post_id, $comment_id, $user_ids, $user_id, $status, $type);
      $insert_notif_comm -> execute();
      $insert_notif_comm -> close();

    } else { }
  }
}

function get_posts(){
  include_once("../ext_functions/connect.php");
  include_once ("../ext_functions/security.php");
  include_once("../ext_functions/prevent.php");
  include_once("../ext_functions/time.php");

  if(isset($_GET['cateid'], $_GET['offset'], $_GET['limit'])){
    $cate_id = escape($_GET['cateid']);
    $limit   = escape($_GET['limit']);
    $offset  = escape($_GET['offset']);

    $select_queries = "SELECT cau_questions.id,cau_questions.uploadimg, cau_questions.type,users.user_name,
                              cau_questions.cate_id, cau_questions.sub_id,
                              cau_questions.question, users.profile_pic,
                              cau_questions.date, caucus_class.title
    FROM cau_questions
    LEFT JOIN users
    ON cau_questions.user_id = users.user_id
    LEFT JOIN caucus_class
    ON cau_questions.cate_id = caucus_class.cate_id
    WHERE cau_questions.cate_id = $cate_id AND cau_questions.question_uid = 0 AND cau_questions.type = 0
    ORDER BY id DESC LIMIT {$limit} OFFSET {$offset} ";
    $queries = $db_conn -> query($select_queries);

    while($result_queries = $queries -> fetch_object()) {
      $postid      = escape($result_queries->id);
      $sub_id      = escape($result_queries->sub_id);
      $question    = htmlspecialchars_decode($result_queries->question);
      $user_name   = escape($result_queries->user_name);
      $profile_pic = escape($result_queries->profile_pic);
      $subject     = escape($result_queries->title);
      $type        = escape($result_queries->type);
      $date        = escape($result_queries->date);
      $uploadimg   = escape($result_queries->uploadimg);

      date_default_timezone_set('America/Los_Angeles'); // Very important

      if($profile_pic == "default_pic.png"){
        $update_profile_pic = "../images/default_pic.png";
      }
      else {
        $update_profile_pic = "../user_data/profile_pic/". $profile_pic;
      }
      ?>

      <div id = 'cover_posts'>
        <div id = 'diss_posts' >
          <div id = "alignuser_img" >
            <img src = "<?php echo $update_profile_pic; ?>"  alt = "<?php echo $user_name;?>"  id = "comm_cover" style = "height: 40px; width: 40px;  object-fit: cover;"/>
            <a style = "color:#333;"> <b> <?php echo ucfirst($user_name); ?> </b>  </a> <br>

            <p style = 'margin-top: 0px;' id = 'que_extra'><?php echo time_ago($date); ?>
              <span> <a> <?php  echo ucfirst($subject); ?> </a> <i class="fa fa-globe" aria-hidden="true" style = "margin-left: -5px; width: 15px;"></i> </span>
            </p>

          </div>
          <div id = "question"> <a href ='anspage.php?p_id=<?php echo $postid;?>'> <?php echo securify($question); ?>
            <?php if(!($uploadimg == "")){?>
            <div id = "upImg">
              <img src="../uploadImgs/<?php echo $uploadimg;?>" alt="Question Image"  style = "margin: 0; padding: 0; width: 100%; height: 100%;"/>
            </div>
          <?php }?>
            </a>
          </div>
        </div>

        <?php
        //******************************************************************************
        // Getting the best answer
        //*********************************************************************************
        $get_comment   = $db_conn -> query("SELECT max(likes),post_id FROM cau_comments WHERE post_id = '$postid'");
        $row_posts     = $get_comment->fetch_assoc();
        $post_id_comm  = escape($row_posts['post_id']); //modified post_id 1
        $likes         = escape($row_posts['max(likes)']);

        $get_comment->close();

        $get_ids       = $db_conn -> query("SELECT * FROM cau_comments WHERE likes = '$likes' AND post_id = '$post_id_comm'");
        $row_ids       = $get_ids  -> fetch_assoc();
        $comment_id    = escape($row_ids['comm_id']);
        $post_comm_ids = escape($row_ids ['post_id']); // modified post_id 2

        $get_ids->close();

        if($likes == 0){
          $delete_leaderboard = $db_conn -> query("DELETE FROM cau_leaderboard WHERE post_id='$post_comm_ids'");

        }

        if($likes >= 1){     // If the max like is 1 or more
          $select_correct_comm = "SELECT cau_comments.comm_id, cau_comments.comment, cau_comments.uploadimg_comm,
                                         cau_comments.user_id, cau_comments.post_id,
                                         users.user_name, caucus_class.cate_id,
                                         caucus_class.best_ans, users.profile_pic
          FROM cau_comments
          LEFT JOIN users
          ON cau_comments.user_id = users.user_id
          LEFT JOIN caucus_class
          ON caucus_class.cate_id = $cate_id
          WHERE comm_id = '$comment_id' AND post_id = '$postid'";
          $select_correct_comm = $db_conn -> query($select_correct_comm); // post id is the original post_id, unmodified from the cau_comments

          $row_comm = $select_correct_comm -> fetch_assoc();
          $post_comment        = htmlspecialchars_decode($row_comm['comment']);
          $comm_id             = escape($row_comm['comm_id']);
          $user_id             = escape($row_comm['user_id']);
          $profile_pic_comm    = escape($row_comm['profile_pic']);
          $post_comm_id        = escape($row_comm['post_id']); // modified post_id 3
          $user_name_commenter = escape($row_comm['user_name']);
          $best_ans_color      = escape($row_comm['best_ans']);
          $uploadImg           = escape($row_comm['uploadimg_comm']);

          if($profile_pic_comm == "default_pic.png"){
            $update_profile_pic_comm = "../images/default_pic.png";
          }
          else {
            $update_profile_pic_comm = "../user_data/profile_pic/". $profile_pic_comm;
          }

          ?>

          <div id = 'best_ans' style = 'background:<?php echo $best_ans_color;?>'>
            <!-- css in question.css-->
            <p id = "b_anstxt"><span style = 'font-size: 16px;  color: gray;'>  Best Answer  </span> </p>
            <img src='<?php  echo $update_profile_pic_comm; ?>' alt = '<?php echo $user_name_commenter; ?>' style = 'float: left; margin-top: -10px; height: 25px; border: 1px solid lightgray;vertical-align: middle; width: 25px; border-radius: 100px;'/>
            &nbsp;

            <div id = 'img_author' style = "margin-top: -30px;">
              &nbsp;<span> <?php echo  ucfirst($user_name_commenter);?> </span>
            </div>

            <div id = 'auth_comm'>
              <?php echo securify($post_comment);?> </p>
              <?php if($uploadImg != ""){?>
              <img src="../uploadImgs/commentimgs/<?php echo $uploadImg;?>" alt="Best Answer Image" style = "object-fit: contain; width: 100%; height: 50vh">
            <?php }?>
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
            $insert_leaderboard = $db_conn -> prepare("INSERT INTO cau_leaderboard(leader_id, sub_id, cate_id, post_id, comment_id)
                                                       VALUES(?, ?, ?, ?, ?)");
            $insert_leaderboard -> bind_param("iiiii", $user_id, $sub_id, $cate_id, $post_comm_id, $comm_id);
            $insert_leaderboard -> execute();
            $insert_leaderboard->close();
          }

          else if($post_id_leader == $postid) {
            $update_leaderboard = $db_conn -> query("UPDATE cau_leaderboard SET leader_id = '$user_id', comment_id = '$comm_id' WHERE post_id = '$post_comm_id' AND cate_id = '$cate_id'");
          }
        }  else {}
          ?>
         <iframe src = "classcomm.php?p_id=<?php echo $postid;?>"   frameborder = '0'  scrolling="no" style="overflow:hidden; width: 100%;  height: 80px; display: block"></iframe>

        </div>
        <?php } ?>
      <?php

      $queries -> close();
    }
  }


  function singlequestion() {
    include_once("../ext_functions/connect.php");
    include_once("../ext_functions/security.php");
    include_once("../ext_functions/prevent.php");
    include_once("../ext_functions/time.php");

    if(isset($_GET['post_id'])){
      $post_id = escape($_GET['post_id']);

      if(!empty($post_id)){
          $select_query = $db_conn -> query("SELECT cau_questions.id, cau_questions.uploadimg, cau_questions.question, cau_questions.type,
                                                    users.user_id, users.user_name,
                                                    users.user_name, users.profile_pic
          FROM cau_questions
          LEFT JOIN users
          ON cau_questions.user_id = users.user_id
          WHERE cau_questions.id = $post_id");

          $row_posts = $select_query -> fetch_object();

          $profile_pic =  escape($row_posts->profile_pic);
          $user_id =      escape($row_posts->user_id);
          $post_id =      escape($row_posts->id);
          $post_question = htmlspecialchars_decode($row_posts->question);
          $user_name =     escape($row_posts->user_name);
          $type =          escape($row_posts->type);
          $uploadImg =     escape($row_posts->uploadimg);

          ?>

          <div id = 'diss_posts' style = "background: #FAFAFA">
            <p>
              <a style = 'font-size: 18px;color: blue;'> <b> <?php echo ucfirst($user_name); ?> </b> </a>
              &nbsp; <?php echo securify($post_question); ?>
            </p>
            <?php if($type == 0 && $uploadImg != "") {?>
             <img src="../uploadImgs/<?php echo $uploadImg;?>" alt="Question Image" style = "width: 100%;height: 72vh; object-fit: contain;"/>
           <?php
         }
         else if($type == 1 && $uploadImg != ""){?>
             <img src="../uploadImgs/asksomeimg/<?php echo $uploadImg;?>" alt="Private Question Image" style = "width: 100%; height: 72vh;object-fit: contain;"/>
           <?php } ?>
           <br/>
          </div>
          <?php
          $select_query->close();
        }
      }
    }

    function displaycomments(){
      include_once("../ext_functions/connect.php");
      include_once("../ext_functions/security.php");
      include_once("../ext_functions/prevent.php");

      if(isset($_GET['post_id'], $_GET['type_id'])) {
        $get_id  = escape($_GET['post_id']);
        $type_id = escape($_GET['type_id']);
        $arrange = "";

        if(!empty($get_id) && !empty(is_numeric(0))){



          $get_comment = "SELECT cau_comments.comment, cau_comments.uploadimg_comm, cau_comments.comm_id,
                                 cau_comments.post_id, users.user_name,
                                 users.profile_pic
          FROM cau_comments
          LEFT JOIN users
          ON cau_comments.user_id = users.user_id
          WHERE  post_id = $get_id
          ORDER BY comm_id DESC";

          $run_comment = $db_conn -> query($get_comment);

          while($row_posts = $run_comment-> fetch_object()){
            $comment_id          = escape($row_posts->comm_id);
            $post_id_comment     = escape($row_posts->post_id);
            $post_comment_author = escape($row_posts->user_name);
            $post_comment        =  htmlspecialchars_decode($row_posts->comment);
            $profile_pic         = escape($row_posts->profile_pic);
            $uploadimg_comm     = escape($row_posts->uploadimg_comm);

            if($profile_pic == "default_pic.png"){
              $update_profile_pic = "../images/default_pic.png";
            }
            else {
              $update_profile_pic = "../user_data/profile_pic/". $profile_pic;
            }


            ?>
            <div id = 'diss_posts1'>
              <div id = 'img_author'>
                <img src='<?php echo $update_profile_pic; ?>' alt = '<?php echo $post_comment_author; ?>' style = 'height: 25px; border: 1px solid lightgray;vertical-align: middle; width: 25px; border-radius: 100px; '/>
                <span><b> <?php echo ucfirst($post_comment_author);?></b> </span>
              </div>

              <div id = 'auth_comm'>
                <p style = "height: auto; overflow: hidden; "> <?php echo securify($post_comment);?> </p>

                <?php if($uploadimg_comm != '') {?>
                <img src="../uploadImgs/commentimgs/<?php echo $uploadimg_comm; ?>" alt=""  style = "width: 100%; height: 50vh; object-fit: contain;">
              <?php } ?>
              </div>

              <?php if($type_id == 0){?>
                <iframe src="likeunlike.php?comment_id=<?php echo $comment_id; ?>&post_id=<?php echo $post_id_comment;?>" frameborder ='0' width="625"   scrolling = "no"  style ="overflow: hidden;display: block; height: 35px; "></iframe>
                <?php  }?>
              </div>
                  <hr style = "border-top: 1px solid gray; ">
              <?php
         }
      }
   }
}

      function getbestans() {
        include_once("../ext_functions/connect.php");
        include_once("../ext_functions/security.php");

        if(isset($_GET['post_id'])){
          $post_id = escape($_GET['post_id']);

          // ********************************************************************
          //  GETTING THE BEST ANSWER
          // ******************************************************************
          if(!empty($post_id)){

            $get_comment = $db_conn -> query("SELECT max(likes), post_id FROM cau_comments WHERE post_id = '$post_id'");
            $row_posts   = $get_comment -> fetch_assoc();

            $post_id_comm = escape($row_posts['post_id']);
            $likes        = escape($row_posts[('max(likes)')]);

            $get_ids = $db_conn -> query("SELECT * FROM cau_comments WHERE likes = '$likes' AND post_id = '$post_id_comm'");

            $row_ids       = $get_ids -> fetch_assoc();
            $comment_id    = escape($row_ids['comm_id']);
            $post_comm_ids = escape($row_ids['post_id']);

            if($likes >= 1){
              $select_correct_comm = "SELECT cau_comments.comm_id, cau_comments.uploadimg_comm, cau_comments.comment,
                                             cau_comments.user_id, cau_comments.post_id,
                                             users.profile_pic, users.user_name
              FROM cau_comments
              LEFT JOIN users
              ON cau_comments.user_id = users.user_id
              WHERE comm_id = $comment_id AND post_id = $post_id";

              $result_select_correct_comm = $db_conn -> query($select_correct_comm);
              $row_comm = $result_select_correct_comm -> fetch_object();
              $post_comment  = $row_comm->comment;
              $comm_id       = escape($row_comm->comm_id);
              $user_id       = escape($row_comm->user_id);
              $post_comm_id  = escape($row_comm->post_id);
              $profile_pic   = escape($row_comm->profile_pic);
              $user_name_commenter = escape($row_comm->user_name);
              $uploadimg = escape($row_comm->uploadimg_comm);

              if($profile_pic == "default_pic.png"){
                $update_profile_pic = "../images/default_pic.png";
              }
              else {
                $update_profile_pic = "../user_data/profile_pic/". $profile_pic;
              }
              ?>

              <p style = 'font-family: Calibri light; font-size: 16px; margin-top: 0px;'><span style = 'font-size: 16px;  color: gray;'> <b> Best Answer </b> </span> </p>
              <img src='<?php  echo $update_profile_pic; ?>' alt = '<?php echo $user_name_commenter; ?>' style = 'float: left; margin-top: -10px; height: 25px; border: 1px solid lightgray;vertical-align: middle; width: 25px; border-radius: 100px;'/>
              &nbsp;

              <div id = 'img_author' style = "margin-top: -7px;">
                &nbsp;<span style = 'color: blue; font-size: 16px; font-family: Calibri; font-weight: 400;'> <?php echo  $user_name_commenter;?> </span>
              </div>

              <div id = 'auth_comm' style = "margin-top: 0px;">
                <?php echo htmlspecialchars_decode($post_comment);?> </p>
                  <img src="../uploadImgs/commentimgs/<?php echo $uploadimg; ?>" alt="" style = "height: 50vh; width: 100%; object-fit: contain;">
              </div>

              <?php

            }
          }
        }
      }

      function userleaderboard() {
        include_once("../ext_functions/connect.php");
        include_once("../ext_functions/security.php");


        if(isset($_GET['c_id'])){
          $cate_id = escape($_GET['c_id']);

          if(!empty($cate_id)){

            $select_lead = "SELECT cau_leaderboard.leader_id, COUNT(*), users.user_name, users.user_id, users.profile_pic
            FROM cau_leaderboard
            INNER JOIN users
            ON cau_leaderboard.leader_id = users.user_id
            WHERE cau_leaderboard.cate_id = $cate_id
            GROUP BY leader_id ORDER BY COUNT(*) DESC LIMIT 10";

            $result_select_lead = $db_conn -> query($select_lead);
            $points = $result_select_lead -> num_rows;

            $x = 1;
            echo '<p> </p>';
            while($row_leader = $result_select_lead -> fetch_assoc()){
              $user_id       = escape($row_leader['leader_id']);
              $user_name     = escape($row_leader['user_name']);
              $user_position = escape($row_leader['COUNT(*)']);
              $profile_pic   = escape($row_leader['profile_pic']);

              if($user_position  <= 1){
                $filter_points = 'Point';
              }

              else if($user_position  > 1){
                $filter_points = 'Points';
              }

              if($profile_pic == "default_pic.png"){
                $update_profile_pic = "../images/default_pic.png";
              }
              else {
                $update_profile_pic = "../user_data/profile_pic/". $profile_pic;
              }

              //******************************************************************************
              // get the leader profile picture end
              // ********************************************************************************
              ?>
              <div class = "cover_leaders" >
                <img src = "<?php echo $update_profile_pic?>" style = "width: 40px; height: 40px;border-radius: 100px; border: 2px solid lightgray; margin: 10px 10px; float: left;">
                <div class = "leader_info">
                  <a style = "color: black;text-decoration: none;"> <b> <?php echo $user_name; ?> </b>  </a> <br>
                  <a style = "font-size: 14px;color: black;text-decoration: none;"> <?php echo $user_position; ?> <?php echo $filter_points; ?> </a>
                </div>

                <?php
                $total =  $x++; // Get the achievement picture
                ?>

                <img src = "../images/<?php echo $total?>.png" onerror='this.style.display = "none"' style = "float: right; margin-right: 10px; height: 35px; width: 35px; margin-top: 10px;">
              </div>
              <br/>
              <?php
            }
        }
    }
}

function changetype_que(){
  include_once("../ext_functions/connect.php");
  include_once("../ext_functions/security.php");

  if(isset($_POST['p_id'], $_POST['u_id'])) {
    $get_id = escape($_POST['p_id']);
    $user_ids = escape($_POST['u_id']);
    $resolve_id = 1;

    if(!empty($get_id)){
      $update_type = $db_conn -> query("UPDATE cau_questions SET resolve = '$resolve_id' WHERE id = '$get_id'");

      $select_query = $db_conn -> query("SELECT user_id,question_uid FROM cau_questions WHERE id = $get_id");
      $row_post = $select_query -> fetch_object();
      $que_uid  = escape($row_post->question_uid);
      $user_id  = escape($row_post->user_id); // This is the user who ask the question

      // to get the comment_id
      $selectcommid = $db_conn -> query("SELECT comm_id FROM cau_comments ORDER BY comm_id DESC LIMIT 1");
      $row_result_commid = $selectcommid-> fetch_object();
      $comment_id        = $row_result_commid->comm_id;

      $status = "unseen";
      $type   = "resComm";

      $insert_notif_comm = $db_conn -> prepare("INSERT INTO likecomm_notification(post_id, comm_id, user_id, respond_uid,status,type,date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $insert_notif_comm -> bind_param('iiiiss', $get_id, $comment_id, $user_id, $que_uid, $status, $type);
      $insert_notif_comm -> execute();
      $insert_notif_comm -> close();

      $select_query->close();
      $selectcommid->close();
    }
  }
}
?>
