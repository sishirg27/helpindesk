<?php
session_start();
include_once("../../ext_functions/connect.php");
include_once("../../ext_functions/select_user.php");
include_once("../../ext_functions/profile_pic_upload.php");
include_once("../../ext_functions/time.php");
include_once("../../ext_functions/bestAns.php");
include_once("../../ext_functions/prevent.php");


// if this is not register or in activity: We are doing Redirection
if(!isset ($_SESSION['sess_user'])) {
  header("location: ../../login.php");
}
else {
  ?>

  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="../../images/helpindeskmini.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../css/home_main.css">
    <link rel="stylesheet" href = "../../css/search.css"> <!-- css for search box at the header  and the search footer-->
    <link rel="stylesheet" href="../../css/style.css"> <!-- css to design the header including the search box and the nav  -->
    <link rel="stylesheet" href="../../css/homenotif.css"> <!-- css for the notification dropdown and menu dropdown -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script type="text/javascript" src = "../../js/jquery.min.js"></script>
    <script type="text/javascript" src = "../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src = "../../js/popupdropdown.js"></script> <!-- required for notification dropdown and setting dropdown -->
    <script type="text/javascript" src = "../../js/typeahead.min.js"></script>

  </head>
  <body>

    <?php //includes the header searchbox including notification dropdowns
    $back = "../../";
    $position= "fixed";
    $pgtitle = "Helpindesk Search";
    include("../headernav.php");
    ?>

    <br/> <br/> <br/> <br/> <br/>

    <?php
    $key_search =$_GET['typeahead'];
    $array = array();
    $search_query = $db_conn -> query("SELECT cau_questions.question, cau_questions.id, cau_questions.user_id, cau_questions.id, cau_questions.cate_id, cau_questions.date, cau_questions.uploadimg,
                                              caucus_class.title, users.user_name, users.profile_pic
      FROM cau_questions
      LEFT JOIN caucus_class
      ON cau_questions.cate_id = caucus_class.cate_id
      LEFT JOIN users
      ON cau_questions.user_id = users.user_id
      WHERE cau_questions.question LIKE '%{$key_search}%'
      AND type = 0");
      $num_result = $search_query -> num_rows;
      ?>

      <div class = "wrap_all" >
        <div class = "cover_wrap" style = "margin-left: 75px; height: auto; overflow: hidden">
          <p style = "font-family: Arial; font-size: 13.5px;color: gray;margin-top: 15px;"> About <?php echo $num_result; ?> results </p>
          <div class = "wrap_search" style = "width: 800px; height: auto; overflow:hidden; ">

            <?php
            while($row = $search_query -> fetch_object()){
              $question = $row->question;
              $pid      = escape($row->id);
              $cid      = escape($row->cate_id);
              $user_id = escape($row->user_id);
              $user_name = escape($row->user_name);
              $profile_pic = escape($row->profile_pic);
              $post_id = escape($row->id);
              $subject  = escape($row->title);
              $uploadImg = escape($row->uploadimg);
              $date = escape($row->date);

              date_default_timezone_set('America/Los_Angeles'); // Very important

              if($profile_pic == "default_pic.png"){
                $update_profile_pic = "../../images/default_pic.png";
              }
              else {
                $update_profile_pic = "../../user_data/profile_pic/". $profile_pic;
              }

              $select_numans = $db_conn -> query("SELECT comm_id FROM cau_comments WHERE post_id = '$post_id'");
              $num_ans = $select_numans -> num_rows;
              ?>

              <div id = 'cover_posts' style = "border: 1px solid lightgray">
                <div id = 'diss_posts'>
                  <div id = "alignuser_img" >

                    <img src = "<?php echo $update_profile_pic; ?>"  alt = "<?php echo $user_name;?>"  id = "comm_cover" style = "height: 40px; width: 40px;  object-fit: cover;"/>
                    <a style = "color: #333;"> <b> <?php echo ucfirst($user_name); ?> </b>  </a> <br>

                    <p style = 'margin-top: 0px;' id = 'que_extra'> &nbsp; <?php echo time_ago($date);?>
                      <span> <a> <?php echo ucfirst($subject); ?> </a>

                        <img src = "../../images/num_comm.png" height = "14" width = "14" style = " "/>
                        <a id = "number_ans" style = "font-weight: 600; font-size: 0.9em; color: black; margin-left: 2px;"><?php echo $num_ans;?></a>

                        <span style = "border-left: 1px solid lightgray;"> <i class="fa fa-globe" aria-hidden="true" style = "width: 15px; margin-left: 3px;"></i> </span> </span>
                      </p>
                    </div>
                    <div id = "question"> <a  target='_parent' href = '../../class/anspage.php?p_id=<?php echo $post_id;?>'> <?php echo html_entity_decode($question); ?> </a> </div>
                    <?php if($uploadImg != ""){?>
                    <img src="../../uploadImgs/<?php echo $uploadImg;?>" alt="Question Image" style = "width: 100%; height: 60vh;object-fit: contain;">
                  <?php } ?>
                  </div>
                    </div>
                  <?php
                  $back = "../../";
                  getBestans($pid,$cid,$profile_pic,$back);
                }
             ?>
            </div>
          </div>
        </div>
      </body>
      </html>
      <?php
    }
  ?>
