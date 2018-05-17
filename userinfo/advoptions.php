<?php
session_start();

include_once("../ext_functions/connect.php");
include_once("../ext_functions/security.php");
include_once("../ext_functions/prevent.php");
include_once("../ext_functions/select_user.php");
include_once("../ext_functions/time.php");
include_once("../ext_functions/bestAns.php");

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>AdvOptions</title>

  <link rel = "stylesheet" href = "../css/home_main.css">
  <link rel="stylesheet" href="../css/style.css">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Boostrap tabs -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>


</head>
<body  style = 'background:transparent;'>
  <?php

  if(isset($_GET['all_id'])){
    $query_all_id = $_GET['all_id'];

    $get_queries = "SELECT cau_questions.id, cau_questions.user_id, cau_questions.cate_id,  cau_questions.question, cau_questions.uploadimg,
     cau_questions.type, cau_questions.date, users.user_name, users.profile_pic, caucus_class.title

    FROM cau_questions
    LEFT JOIN users
    ON users.user_id = cau_questions.user_id
    LEFT JOIN caucus_class
    ON caucus_class.cate_id = cau_questions.cate_id
    WHERE cau_questions.id = '$query_all_id' and cau_questions.user_id = '$user_ids' ORDER by 1 DESC";

    $run_queries = $db_conn -> query($get_queries);

    while($row_posts = $run_queries  -> fetch_assoc()){
      $post_id       = escape($row_posts['id']);
      $user_id       = escape($row_posts['user_id']);
      $cate_id       = escape($row_posts['cate_id']);
      $post_question = htmlspecialchars_decode($row_posts['question']);
      $tags          = escape($row_posts['title']);
      $date          = escape($row_posts['date']);
      $profile_pic   = escape($row_posts['profile_pic']);
      $type          = escape($row_posts['type']);
      $user_name     = escape($row_posts['user_name']);
      $uploadimg     = escape($row_posts['uploadimg']);

      date_default_timezone_set('America/Los_Angeles'); // Very important

      if($profile_pic == "default_pic.png"){
        $update_profile_pic = "../images/default_pic.png";
      }
      else {
        $update_profile_pic = "../user_data/profile_pic/". $profile_pic;
      }
      ?>

      <div id = 'cover_posts'>
        <div id = 'diss_posts'>
         <div id = "alignuser_img">
            <img src = "<?php echo $update_profile_pic; ?>"  alt = "<?php echo $user_name;?>"  id = "comm_cover" style = "width: 45px; height: 45px; object-fit: cover;"/>
            <a style = "color: #333;"> <b> <?php echo ucfirst($user_name); ?> </b>  </a> <br>

            <p style = 'margin-top: 0px; height: auto; overflow: hidden;' id = 'que_extra'> &nbsp; <?php echo time_ago($date);?>
              <span> <a> <?php echo ucfirst($tags); ?> </a>
                <img src = "../images/num_comm.png" height = "14" width = "14" style = " "/>
                <a id = "number_ans" style = "font-weight: 600; font-size: 0.9em; color: black; margin-left: 2px;"></a>
                <span style = "border-left: 1px solid lightgray;"> <i class="fa fa-globe" aria-hidden="true" style = "width: 15px; margin-left: 3px;"></i> </span> </span>
              </p>
            </div>

            <div class = 'dropdown' style= 'margin-right: 0px;'>
              <?php include_once("ellipsisDdown.php"); ?>
            </div>

            <div id = "question"> <a  target='_parent' href =  '../class/anspage.php?p_id=<?php echo $post_id;?>'> <?php echo securify($post_question); ?>
              <?php if($uploadimg != ""){?>
            <img  src="../uploadImgs/<?php echo $uploadimg;?>"  alt="" style = "width: 100%; height: 100vh; object-fit: contain;">
          <?php } ?>
            </a> </div>
          </div>

        <?php
        $back = "../";
        getBestans($post_id,$cate_id,$profile_pic,$back);
      }
      ?>
    </div>

    <script type="text/javascript">
    var p_id = '.dQue<?php echo $post_id; ?>';
    var post_id = '<?php echo $post_id?>';
    var u_id = '<?php echo $user_ids; ?>';

    $(p_id).click(function () {
      $.ajax({
        type: 'POST',
        url: 'deletequery.php',

        data: {
          'post_id': post_id,
          'user_id': u_id
        },

        success:function(data){
          window.parent.location.reload();
        }
      });
    });
    </script>

      <script type = "text/javascript">
      function addmsg(type, msg){
        $('#number_ans').html(msg);
      }

      function waitForMsg(){
        $.ajax({
          type: 'GET',
          url: '../class/numans.php?p_id=<?php echo $query_all_id;?>',
          async: true,
          cache: false,
          timeout:50000,

          success:function(data){
            addmsg("new", data);
            setTimeout(
              waitForMsg,
              1000
            );
          },

          error: function(XMLHttpRequest, textStatus, errorThrown){
            addmsg("error", textStatus + " (" + errorThrown + ")");
            setTimeout(
              waitForMsg,
              15000);
            }
          });
        };

        $(document).ready(function (){
          waitForMsg();
        });

        </script>

        <?php
      }
      ?>

    </body>
    </html>
