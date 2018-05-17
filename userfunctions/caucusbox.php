<?php
session_start();
//error_reporting(0); // If you do not want the user to see any error
error_reporting(E_ALL); // to display all the error in a elegant fashion
ini_set('display_errors', 'On');
include("../ext_functions/connect.php");
include("../ext_functions/security.php");
include("../ext_functions/select_user.php");

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
    <title></title>
    <script type="text/javascript" src = "../js/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/caucusbox.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  </head>

  <body style = "background: #fafafa;">
    <?php
    if(isset($_GET['cate_id'])){

      $unique_id = escape($_GET['cate_id']);

      if(!empty($unique_id)) {

        $select_all_cate = $db_conn -> query("SELECT * FROM caucus_class WHERE cate_id = '$unique_id'");

        if($select_all_cate -> num_rows == 0){
          echo "No class Found";
        }
        else {
          $row = $select_all_cate -> fetch_object();
          $sub_id= escape($row->sub_id);
          $title = escape($row->title);
          $color = escape($row->color);
          ?>
          <div class = 'cate'>
            <div class = 'cau_cate' >

              <?php
              $select_cauuser = $db_conn -> query("SELECT * FROM subject_users WHERE cate_id = '$unique_id' AND user_id = '$user_ids'");
              if($select_cauuser -> num_rows == 0){
                ?>
                <button id = "btn" style = "outline: none" onclick="insert_class();"></button>
                <?php
              }
              else if($select_cauuser -> num_rows == 1){
                ?>
                <button id = "btn_clicked" onclick="uninsert_class()">  <span class="glyphicon glyphicon-ok" style = "position: absolute;font-size: 14px;right: 6.7px;top: 6px;color:white; "> </span> </button>

                <?php
              }
              ?>

              <div style = "height: auto; overflow: hidden;" onclick="window.parent.location.href='../class/classque.php?c_id=<?php echo $unique_id;?>&s_id=<?php echo $sub_id;?>';">
   
                  <img src="../images/unfold.png" alt=""  > <br> <br>

                  <p style = 'background: <?php echo $color;?>'> <?php echo ucfirst($title);?> </p>
              </div>
            </div>
          </div>

          <?php
        }

        $select_sub = $db_conn -> query("SELECT sub_id FROM  caucus_class WHERE cate_id = '$unique_id'");
        $result_class = $select_sub->fetch_object();
        $sub_id = escape($result_class->sub_id);

        if(isset($_POST['add'])){

          if($select_cauuser -> num_rows == 0){

            $insert_user = $db_conn -> prepare("INSERT INTO subject_users(sub_id,cate_id,user_id,title,color,date)
            VALUES (?, ?, ?, ?, ?,NOW())");

            $insert_user -> bind_param('iiiss', $sub_id, $unique_id,$user_ids,$title,$color);
            $insert_user -> execute();

            $insert_user -> free();
            $insert_user -> close();
          }
        }

        if(isset($_POST['unadd'])){
          $remove_user = $db_conn -> query("DELETE FROM subject_users WHERE user_id='$user_ids' AND cate_id='$unique_id'");
        }
      }
    }
  }
  ?>


  <script type="text/javascript">
  function insert_class(){
    $.ajax({
      type:'POST',
      url: '',
      data: 'add='+1,
      success:function(data){
        window.location.reload();
      }
    });
  }

  function uninsert_class(){
    $.ajax({
      type: 'POST',
      url: '',
      data:'unadd='+1,
      success:function(data){
        window.location.reload();
      }
    });
  }
  </script>s
</body>
</html>
