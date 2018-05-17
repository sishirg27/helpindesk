<?php
session_start();

// IF and SWITCH to check ajax sending functions
if (isset($_GET['f'])) {
  switch ($_GET['f']) {
    case "ins_bug":
    insertBug();
    break;

    case "get_bug":
    getBug();
    break;

    case "getnumUser":
    getnumUser();
    break;

    default:
    header('Location: ../home.php');
    exit;
    break;
  }
}

function getBug() {
  include_once("../ext_functions/connect.php");
  include_once("../ext_functions/security.php");
  include_once("../ext_functions/select_user.php");
  include_once("../ext_functions/time.php");

if($user_ids == 1){
  $result=array();
  $get_Bug = $db_conn -> query("SELECT allbugs.user_id, allbugs.subject, allbugs.bug, allbugs.fixed, allbugs.date, users.user_name
    FROM allbugs
    LEFT JOIN users
    ON allbugs.user_id = users.user_id
    ORDER BY date DESC");

    while($row_bug = $get_Bug -> fetch_object()){
      $user_name  =   escape($row_bug->user_name);
      $subject    =   escape($row_bug->subject);
      $bug        =   escape($row_bug->bug);
      $fixed      =   escape($row_bug->fixed);
      $date       =   escape($row_bug->date);
      date_default_timezone_set('America/Los_Angeles'); // Very important

      ?>
      <div id = "ownerWrap">
        <div id = "uName">   <b> Name:    </b> <a class = "name"> <?php echo $user_name; ?> &nbsp; &nbsp;  <?php  echo time_ago($date); ?></a> </div>
        <div id = "usubject"> <b> Subject: </b> <a class = "sub"> <?php echo html_entity_decode($subject); ?></a> </div>
        <div id = "urealBug"> <b> Bug:     </b> <a class = "bug"> <?php echo html_entity_decode($bug); ?></a> </div>
      </div>
      <?php
    }
  } else {
      header('Location: ../home.php');
  }
  }

  function insertBug() {
    include_once("../ext_functions/connect.php");
    include_once("../ext_functions/security.php");
    include_once("../ext_functions/select_user.php");

    if(isset($_POST['subj'], $_POST['bug'])){
      $subject = escape($_POST['subj']);
      $bug_txt = escape($_POST['bug']);
      $fixed = 0;

      if(!empty($subject) && !empty($bug_txt)){
        $insertBug = $db_conn -> prepare("INSERT INTO allbugs(user_id,subject,bug, fixed, date) VALUES(?, ?, ?, ?, NOW())");
        $insertBug -> bind_param('issi', $user_ids, $subject, $bug_txt, $fixed);
        $insertBug -> execute();
        $insertBug -> close();

      }
    }
  }

  function getnumUser(){
    include_once("../ext_functions/connect.php");
    include_once("../ext_functions/security.php");
    include_once("../ext_functions/select_user.php");

        if($user_ids == 1){
          $num_users = $db_conn -> query("SELECT user_id FROM users");
          $users = number_format($num_users -> num_rows);
          echo $users;
        }
  }

  ?>
