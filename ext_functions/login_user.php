<?php
require_once ("connect.php");
require_once ("security.php");

if(isset($_POST['lsubmit'])){

  if(isset($_POST['lname'], $_POST['lpass'])){
    $filter_lname =  escape($_POST['lname']);
    $filter_lpass =  escape($_POST['lpass']);

    if(!empty($filter_lname) && !empty($filter_lpass)){

      // Trying to decrypt or UNDO HASH the password ALSO CHECK PASSWORD
      $select_pass = $db_conn -> query("SELECT * FROM users WHERE user_name= '$filter_lname'");
      $row_result = $select_pass -> fetch_assoc();
      $num_user = mysqli_num_rows($select_pass);
      $hash_pwd = $row_result['password'];
      $hash = password_verify($filter_lpass,$hash_pwd);

      if($hash == 0 || $num_user == 0){
        echo "<script> alert('Incorrect Password and Username. Please try Again.') </script>";
        echo("<script>window.location = 'login.php';</script>");
        exit();
      }


      else {

           $getUser_query = $db_conn -> query("SELECT user_name, password FROM users WHERE user_name = '$filter_lname' AND password = '$hash_pwd'");

            $row_users = $getUser_query -> fetch_object();
            $dbusername = escape($row_users->user_name);
            $dbpassword = escape($row_users->password);

            $lowercase_loginname = strtolower($dbusername);
            $lowercase_loginpass = strtolower($dbpassword);


            $lowercase_loginname = strtolower($filter_lname);
            $lowercase_loginpass = strtolower($login_pass);

            if($lowercase_loginpass == $lowercase_dbusername && $lowercase_loginpass == $lowercase_dbpassword){
              session_start();
              $_SESSION['sess_user'] = $lowercase_loginname;
              header("Location:home.php");
            }
            $getUser_query -> free();

        }
      }
    }
    else {
      echo "<script> alert('All fields are required.') </script>";
    }
  }
?>
