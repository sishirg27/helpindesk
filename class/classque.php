<?php
session_start();
//error_reporting(0); // If you do not want the user to see any error
error_reporting(E_ALL); // to display all the error in a elegant fashion
ini_set('display_errors', 'On');
include_once("../ext_functions/connect.php");
include_once("../ext_functions/security.php");
include_once("../ext_functions/select_user.php"); // get the user_ids session value
include_once("../ext_functions/profile_pic_upload.php"); // to change the profile picture
include_once("../ext_functions/get_user.php");
include_once("../ext_functions/bestAns.php");
include_once("uploadQueImg.php");

// select if the user is logged in or not
if(!isset($_SESSION['sess_user'])) {
  header("location:../login.php");
}
else {
  ?>

  <!DOCTYPE html>
  <html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="../images/helpindeskmini.png" type="image/x-icon" />

    <link rel="stylesheet" href="../css/classque.css"> <!-- css for query textbox -->
    <link rel="stylesheet" href="../css/popup.css"> <!-- css for the popup for ask someone in private -->
    <link rel="stylesheet" href="../css/question.css">  <!-- css for the leaderboard and query and best ans and comment section display -->

    <link rel="stylesheet" href="../css/multiselect.css"> <!-- popup multiselect SECRET QUERY CSS -->
    <link rel="stylesheet" type="text/css" href="../js/mathquill.css" /> <!-- mathquill for displaying  the math context in the ckeditor textarea -->

    <?php
    $back = "../";
    include("../css/defaultcss.php");
    ?>

    <script type="text/javascript" src="../js/mathquill.js"></script>
    <script type="text/javascript" src="../js/ckeditor.js"></script>


    <script src='https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js'></script>
    <script src="../js/multi_index.js"></script>

  </head>

  <body style = "background: #fafafa">

        <?php
        if(isset($_GET['c_id'], $_GET['s_id'])) {
          $cate_id = escape($_GET['c_id']);
          $sub_id = escape($_GET['s_id']);

          if(!empty($cate_id) && !empty($sub_id)){
            //includes the header searchbox including notification dropdowns

            $select_class = $db_conn -> query("SELECT caucus_class.title, subjects.subjects
              FROM caucus_class
              LEFT JOIN subjects
              ON caucus_class.sub_id = subjects.id
              WHERE cate_id = $cate_id AND sub_id = $sub_id");


              $result_class = $select_class -> fetch_object();
              $leaderb_title = escape($result_class->title);
              $subject = escape($result_class->subjects);

              $back = "../";
              $position= "fixed";
              $pgtitle =  ucfirst($leaderb_title);
              include_once("../userfunctions/headernav.php");
              ?>
              <script type="text/javascript" src = "../js/cauJS/scrollAnchor.js"></script> <!-- JS for scroller anchor for user-profile menu -->
              <script type="text/javascript" src ="../js/cauJS/classqAJAX.js"></script> <!-- JS for displaying all the AJAX related to insert and get post -->

              <br/> <br/> <br/> <br/> <br/>

          <div id = "wrapper" style = "width: 1345px;">
            <br/>

            <div id = "wrap_small" style = "width: 100%;">

              <div id = "cover" style = "text-align: left;">

                <a href="#0" class="cd-popup-trigger" id = "aSomeone" style = "text-decoration: none;">
                  <button class="btn btn-default" style = "height: 36px;background: #fafafa; border: none;border-radius: 0; border: 1px solid lightgray;"><i class="fa fa-user" aria-hidden="true" style = "width: 15px; font-size: 17px;color: blue;"></i> </button>
                  &nbsp;<button class="btn btn-default"  style = "background: #DA4F43; height: 36px;border: none;border-radius: 0;color: white;font-weight:600;"> Ask Someone </button>
                </a>

                <!-- The popup code for Ask Someone Begins -->
                <div class="cd-popup" role="alert" >
                  <div class="cd-popup-container">
                    <div class="col-md-4"></div>
                    <div id = "upper_txt" style = "text-align: left; margin-left: 20px;">
                      <p style = "font-size: 20px; ">  <?php echo ucfirst($subject); ?>  | <?php echo ucfirst($leaderb_title);?> </p>
                      <p>Ask Someone </p>
                    </div>
                    <br>

                    <div style = "height: auto; width: 450px; text-align: left; margin-left: 10px;">
                    <form action="" method="post" enctype="multipart/form-data">
                      <select name = "secret_select" id = "secret_select"  multiple>
                        <option value="" disabled selected> To </option>
                        <?php users(); ?>
                      </select>
                      <br>

                      <div style = "width: 475px; margin: 0 auto;">
                        <textarea class="ckeditor" contenteditable="true" cols="20" id="editor_priquestion" name="editor1" rows="2" > </textarea>
                        <br/>

                        <input type="submit" name="sec_submit" id ="secret_submit" value="Submit" style = "float: right;" title = "Upload Image">
                        <label for="fileToUpload_sec" style = "border: 1px solid lightgray; padding: 5px; float: right; margin-right: 15px;"> &#128247;  </label>
                        <input type="file" name="fileToUpload_Sec" id="fileToUpload_sec" style = "display: none;">
                        <p id = "img_name_pri" style = "float: right; margin-right: 25px; text-align: left;"> </p>

                      </div>
                      <br/> <br/> <br/>
                    </form>



                    <?php
                    if(isset($_POST['sec_submit'])){
                       insert_que_pri($user_ids, $cate_id, $sub_id);
                    }
                     ?>

                    </div>

                    <a href="#0" class="cd-popup-close img-replace">Close</a>
                  </div>
                  <!-- cd-popup-container -->
                </div>

                <br/> <br/>
                <a href="#0" class="cd-popup-question" style = "text-decoration: none;">
                  <button class="btn btn-default" style = "height: 36px;background: #fafafa; border: none;border-radius: 0;border: 1px solid lightgray;"><i class="fa fa-globe" aria-hidden="true" style = "width: 15px; font-size: 17px;color: blue;"></i> </button>
                  &nbsp;<button class="btn btn-default" style = "background: #DA4F43; height: 36px;border: none;border-radius: 0;color: white;font-weight:600; "> Ask a Question </button>
                </a>

                <div class="cd-question" role="alert">
                  <div class="cd-question-container" >
                    <div class="col-md-4"></div>

                    <div id = "upper_txt" style = "text-align: left; margin-left: 20px; margin-top: 40px;">
                      <p style = "font-size: 20px; ">  <?php echo ucfirst($subject); ?>  | <?php echo ucfirst($leaderb_title);?> </p>
                      <p>Ask a Question </p>
                    </div>

                    <br/>

                    <div style = "width: 475px; margin: 0 auto;">
                      <form action = "" method="post" enctype="multipart/form-data">
                      <textarea class="ckeditor" id="editor_question" name = "textUp"  rows="2" cols="20"></textarea>
                      <br/>
                      <input type="submit" value="Submit" name="que_submit" id = "que_submit" style = "float: right;">
                      <label for="fileToUpload_que" style = "border: 1px solid lightgray; padding: 5px; float: right; margin-right: 15px;"> &#128247;  </label>
                      <input type="file" name="fileToUpload" id="fileToUpload_que" style = "display: none;">
                      <p id = "img_name_que" style = "float: right; margin-right: 25px; text-align: left;"> </p>

                      </form>
                    </div>

                    <?php

                    if(isset($_POST['que_submit'])){
                      insert_que($user_ids, $cate_id, $sub_id);
                    }

                     ?>

                    <script>
                    $(document).ready( function() {
                      CKEDITOR.config.removePlugins = 'elementspath,link,maximize,list,';
                      CKEDITOR.config.resize_enabled = false;
                      CKEDITOR.config.height = '150px';
                    });
                    </script>

                    <a href="#0" class="cd-question-close img-replace">Close</a>
                  </div>
                  <!-- cd-popup-container -->
                </div>
                <!-- popup code for Ask someone ends -->
              </div>


              <script type="text/javascript">
          //    $('#secret_submit').click(function(){
            //    var userid = '<?php echo $user_ids;?>';
            //    var cate_id = '<?php echo $cate_id;?>';
            //    var sub_id = '<?php echo $sub_id; ?>';
            //    submitsecret_query(userid, cate_id, sub_id);
            //  });

              jQuery(function ($) {
                $("#files").shieldUpload();
              });

              //$('#que_submit').click(function(){
              //  var userid = '<?php echo $user_ids;?>';
              //var cate_id = '<?php echo $cate_id;?>';
              //  var sub_id = '<?php echo $sub_id; ?>';

              //submit_query(userid, cate_id, sub_id);
              //});

              </script>


              <div id = "diss" style = "float:right; margin-right: 40px; width: 660px;">
                <div id = "diss_queries"> </div>
                <br> <div id= "loadmoreajaxloader" style = "text-align: center;"> <img src = "../images/loading.gif" style = "height: 60px; width: 60px;"/> </div>
              </div>

              <div class="scroller_anchor"></div>
              <div class = "wrapper_leaderboard">
                <div class = "sub_leaderboard">
                  <p> <?php echo ucfirst($leaderb_title);?> leaderboard   </p>
                </div>
                <div id = "leaders">  </div>
              </div>
            </div>
          </div>  <!-- End for Wrapper div -->

          <script type="text/javascript">
           $(function () {
              $('input[type = "file"]').change(function (){

                var sel_name_que = $('#fileToUpload_que').val();
                var sel_name_sec = $('#fileToUpload_sec').val();

                  if($(this).val() != ""){
                    $(this).css('color', '#333');
                    $('#img_name_pri').text(sel_name_sec);
                    $('#img_name_que').text(sel_name_que);
                  } else {
                    $(this).css('color', 'transparent');
                  }
              });
           })


          document.addEventListener('DOMContentLoaded', function() {
            // get the leaderboard alligned with the scroll
            user_profileScroll('.wrapper_leaderboard');

            // get the user posted questions
            var cate_id = '<?php echo $cate_id;?>';
            getPosts(cate_id);

            // get the user Leaderboard
            getLeaders(cate_id);
          });

          </script>
        </body>
        </html>
        <?php
      }
    }
  }
  ?>
