<?php
session_start();
// IF and SWITCH to check ajax sending functions
if (isset($_GET['f'])) {
  switch ($_GET['f']) {

    case 'queries':
    get_user_queries();
    break;

    case 'ans':
    get_best_ans();
    break;

    case 'uRank':
    getRank();
    break;

    default:
    header('Location: ../home.php');
    exit;
    break;
  }
}


// Ot get the user Al queries
function get_user_queries() {
  include_once("connect.php");
  include_once("security.php");
  include_once("select_user.php");

  if(!empty($user_ids)){
    $select_queries = $db_conn -> query("SELECT user_id FROM cau_questions WHERE user_id = '$user_ids'");
    $queries = $select_queries -> num_rows;

    echo $queries;
  }
}


function get_best_ans(){
  include_once("connect.php");
  include_once("security.php");
  include_once("select_user.php");

  if(!empty($user_ids)){
    $select_from_leaderboard = $db_conn -> query("SELECT leader_id FROM cau_leaderboard WHERE leader_id = '$user_ids'");

    $best_ans =  $select_from_leaderboard  -> num_rows;
    echo $best_ans;
  }
}



function getRank(){
  include_once("connect.php");
  include_once("security.php");
  include_once("select_user.php");

  if(!empty($user_ids)){

    $i = 1;
    $select_rank = $db_conn -> query("SELECT COUNT(*),leader_id FROM cau_leaderboard GROUP BY leader_id ORDER BY COUNT(*) DESC LIMIT 5");

    while($result_rank = $select_rank -> fetch_assoc()){
      $leader_id = escape($result_rank['leader_id']);
      $user_position = escape($result_rank['COUNT(*)']);

      if($leader_id == $user_ids){
        echo  $i;
      }
      $i++;
    }
  }
}
?>
