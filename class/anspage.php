<?php
session_start();
//error_reporting(0); // If you do not want the user to see any error
error_reporting(E_ALL); // to display all the error in a elegant fashion
ini_set('display_errors', 'On');

include_once("../ext_functions/connect.php");
include_once("../ext_functions/security.php");
include_once("../ext_functions/select_user.php");
include_once("../ext_functions/profile_pic_upload.php");
include_once("uploadQueImg.php");


// if this is not register or in activity: We are doing Redirection
if(!isset ($_SESSION['sess_user'])) {
  header("location: ../login.php");
}
else {
  ?>

  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
      <link rel="shortcut icon" href="../images/helpindeskmini.png" type="image/x-icon" />
    <link rel="stylesheet" href="../css/anspage.css"> <!-- css for the best answer question and display comments -->
    <link rel="stylesheet" type="text/css" href="../js/mathquill.css" /> <!-- mathquill for displaying  the math context in the ckeditor textarea -->

    <?php
    $back = "../";
    include("../css/defaultcss.php");
    ?>

    <script type="text/javascript" src="../js/mathquill.js">     </script>
    <script type="text/javascript" src="../js/ckeditor.js">      </script>

  </head>
  <body style = "background: #FAFAFA;">
    <?php //includes the header searchbox including notification dropdowns
    $back = "../";
    $position= "fixed";
    $pgtitle = "Discussion";
    include_once("../userfunctions/headernav.php");
    ?>
    <br/> <br/> <br/> <br/> <br/>
    <script type="text/javascript" src = "../js/cauJS/scrollAnchor.js"></script> <!-- JS for scroller anchor for user-profile menu -->
    <script type="text/javascript" src = "../js/cauJS/ansPgAJAX.js"></script>

    <?php
    if(isset($_GET['p_id'])) {
      $post_id =  escape($_GET['p_id']);
      $u_id    =  escape($user_ids);
      //$type_id = escape($_GET['t']);

      if(!empty($post_id)){
        // getting the type of that certain post

        $select_tid = $db_conn -> query("SELECT type,resolve,user_id FROM cau_questions WHERE id = '$post_id'");
        $res_tid = $select_tid -> fetch_object();
        $type_id = $res_tid->type;
        $get_uid  = $res_tid->user_id;
        $resolve_id = $res_tid->resolve;

        $select_profilepic = $db_conn -> query("SELECT profile_pic FROM users WHERE user_id = '$u_id'");
        $result_pp = $select_profilepic->fetch_object();
        $profile_pic = $result_pp->profile_pic;

        if($profile_pic == 'default_pic.png'){
          $update_pp = "../images/default_pic.png";
        }  else {
          $update_pp =  "../user_data/profile_pic/". $profile_pic;
        }

        $get_numcomm = $db_conn->query("SELECT comm_id FROM cau_comments WHERE post_id = $post_id");
        $num_ans = $get_numcomm -> num_rows;
        ?>

        <div id = "wrap_all">
        <div class="scroller_anchor"></div>
          <div id = "questiontxt"> </div>

          <div id = "all_comm_sort">
          <?php if($type_id == 0 && $num_ans != 0) {?>
            <div id = "best_ans" style = "border-botom: none"></div>
            <?php } ?>


          <!--  <div id = 'diss_comm'>
              <img src="<?php echo $update_pp;?>" alt="" style ="width: 50px; height: 50px;">
              <div id = "comm_img">
                <!--<form  method = 'post' id = 'reply' autocomplete="off"> -->
                <!--<textarea cols = '120' rows = '4'  id = 'textcmt' placeholder = 'Write your answer...' onfocus="showbtns()"></textarea> </br> -->
                  <!-- </form>
                </div>
              </div> -->

              <script type="text/javascript">
              document.addEventListener('DOMContentLoaded', function() {
                // get the leaderboard alligned with the scroll
                 user_profileScroll('#questiontxt');
              });
              </script>

              <div id = "ans_reply" style = "border-top: none;">
                <?php if($type_id == 1 && $resolve_id == 1){ ?>
                  <a id = "resolve_btn"  title="Resolved!"> <span class="glyphicon glyphicon-ok" ></span>  </a>
                  <?php } ?>

                  <div id = "caucus_answers"></div>
                  <?php if($type_id == 1 && $resolve_id == 0){?>
                    <div id = "priQues">
                      &nbsp; &nbsp; &nbsp;
                      <input type="button"  class="btn btn-info " value="Reply" onclick="showhideeditor()" style ="background: #0271BC; color: white;border-color: lightgray;" /> &nbsp;
                      <?php if($get_uid == $u_id) {?>
                        <input type = 'submit' value = 'Resolve'  class="btn btn-info " data-toggle="modal" data-target="#myModal" style ="background: #eee; color: black; border-color: lightgray;"/>
                        <!-- Modal for the popup Bootstrap-->
                        <div class="modal fade" id="myModal" role="dialog" style = "margin-top: 150px;">
                          <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Resolve</h4>
                              </div>
                              <div class="modal-body">
                                <p>Are you sure you want resolve this query?</p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" onclick= "resolveQue()" style ="background: #0271BC;color: white">Yes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php } ?>

                        <!-- Modal for the popup Bootstrap END-->
                        <form method="post" enctype="multipart/form-data">

                        <div id = "txtContainer" style = "margin-top: 15px;"><textarea name="editor" class = "ckeditor" rows="8" cols="80" id = 'pri_txtcmt'></textarea> </div>

                        <div id ="comm_btns" style = "margin-right: 0px;">

                          <input type="submit" value="Submit" name="comm_submit_pri" id = "pri_Cmtsub" style ="background: #0271BC; color: white;float: right; margin-right: 10px;">
                          <input type="file" name="fileToUpload" id="fileToUpload_commpri" style = "display: none;">
                          <input type="button" name="" class = 'cancel' value="Cancel" onclick = "hidebtns()" style = "margin-right: 10px; float: right;" />
                          <label for="fileToUpload_commpri" style = "border: 1px solid lightgray; padding: 3.5px; float: right; margin-right: 15px;"> &#128247;  </label>
                          <p id = "img_name_commpri" style = "float: right; margin-right: 25px; text-align: left;"> </p>

                          <?php

                          if(isset($_POST['comm_submit_pri'])){
                            uploadImgComm($post_id, $type_id);
                          }

                          ?>
                        </div>
                          </form>
                      </div>
                      <script type="text/javascript">
                      function resolveQue(){
                        var post_id = '<?php echo $post_id;?>';
                        var user_id = '<?php echo $u_id; ?>';

                        changetype(post_id,user_id);
                        $('#priQues').hide();
                        location.reload();
                      }

                      </script>
                      <?php }?>
                    </div>
                    <br/>
                    <?php if($type_id == 0){ ?>
                      <form action = "" method="post" enctype="multipart/form-data">

                      <textarea name="editor" class = "ckeditor" rows="8" cols="80" id = 'textcmt' ></textarea>
                      <div id ="comm_btns">
                        <input type="submit" value="Submit" name="comm_submit" id = "comment_submit" class = "reply" style ="background: #0271BC; color: white;float: right">
                        <input type="file" name="fileToUpload" id="fileToUpload_comm" style = "display: none;">
                        <input type="button" name="" class = 'cancel' value="Cancel" onclick = "hidebtns()" style = "margin-right: 10px; float: right;" />
                        <label for="fileToUpload_comm" style = "border: 1px solid lightgray; padding: 5px; float: right; margin-right: 15px;"> &#128247;  </label>
                        <p id = "img_name_comm" style = "float: right; margin-right: 25px; text-align: left;"> </p>
                      </div>
                    </form>
                  <?php }

                  if(isset($_POST['comm_submit'])){
                    uploadImgComm($post_id, $type_id);
                  }
                  ?>

                </div>
                    <div style = "width: 100%; height: 25px;clear: both;"> </div>
                  </div>

                  <script>

                  $(function () {
                     $('input[type = "file"]').change(function (){

                       var sel_name_comm = $('#fileToUpload_comm').val();
                       var sel_name_commpri = $('#fileToUpload_commpri').val();

                         if($(this).val() != ""){
                           $(this).css('color', '#333');
                           $('#img_name_comm').text(sel_name_comm);
                            $('#img_name_commpri').text(sel_name_commpri);
                         } else {
                           $(this).css('color', 'transparent');
                         }
                     });
                  })

                  // to alter the show buttons
                  CKEDITOR.on('instanceReady', function(evt) {
                    var editor = evt.editor;
                    editor.on('focus', function(e) {
                      showbtns();
                    });
                  });

                  CKEDITOR.replace('textcmt');

                  function showbtns(){
                    $('#comm_btns').show();
                  }

                  function hidebtns(){
                    $('#comm_btns').hide();
                  }

                  function showhideeditor(){
                    $('#txtContainer').toggle();
                    hidebtns();
                  }

                  </script>

                  <script type="text/javascript">

                   $(document).ready(function () {
                   var post_id = '<?php echo $post_id; ?>';
                    var user_id = '<?php echo $u_id; ?>';
                    var type_id = '<?php echo $type_id;?>'

                    get_question(post_id);
                    get_comments(post_id,type_id);
                    get_bestans(post_id);
                  });

                  </script>

                </body>
                </html>
                <?php
              }
            }
          }
     ?>
