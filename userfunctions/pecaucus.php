<?php
session_start();
// User Peronal Caucuses
//error_reporting(0); // If you do not want the user to see any error
error_reporting(E_ALL); // to display all the error in a elegant fashion
ini_set('display_errors', 'On');
include ("../ext_functions/connect.php");
include("../ext_functions/security.php");
include("../ext_functions/select_user.php");

$select_all = "SELECT subject_users.title,subject_users.color,subject_users.cate_id, subject_users.sub_id
FROM subject_users
LEFT JOIN  caucus_class
ON subject_users.cate_id = caucus_class.cate_id
WHERE user_id = '$user_ids'";

$result = $db_conn -> query($select_all);

if($result -> num_rows){
  while($row = $result->fetch_object()){
    ?>
    <a href = "class/classque.php?c_id=<?php echo escape($row->cate_id);?>&s_id=<?php echo escape($row->sub_id);?>">
      <div class = 'cate' style = "margin-left: 15px;">
        <div class = 'cau_cate'>
          <img src="images/unfold.png" alt="" >
          <p style = 'margin-top: 19px;background: <?php echo escape($row->color);?>'> <?php echo htmlspecialchars_decode(ucfirst($row->title));?> </p>
        </div>
      </div>
    </a>
    <?php
  }
} else {
  echo "<h3 style = 'text-align: center; margin-top: 175px; color: lightgray'> You haven't selected any class. </h3> ";
}


?>
