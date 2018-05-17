<?php

include ("ext_functions/connect.php");
include ("ext_functions/security.php");

$update_hash = $db_conn -> query("UPDATE users SET recoveryemail_enc = ''  WHERE `date` < DATE_SUB(NOW(), INTERVAL 1 MINUTE)");

if(isset($_GET['key'])){
  $emailKey = escape($_GET['key']);

  $select_recEmail = $db_conn -> query("SELECT recoveryemail_enc FROM users WHERE recoveryemail_enc = '$emailKey'");
  $recEmail = $select_recEmail -> fetch_object();

  $key = escape($recEmail->recoveryemail_enc);

  if($emailKey == $key){
    if(isset($_POST['newPass']) && isset($_POST['comPass'])) {
      $newPass = escape($_POST['newPass']);
      $comPass = escape($_POST['comPass']);
      //  echo $emailKey;
      if($newPass != '' && $comPass != ''){
        if($newPass == $comPass){
          $encry_pass = password_hash($comPass, PASSWORD_DEFAULT); // More secure password with HASHING
          $update_pass = $db_conn -> query("UPDATE users SET password = '$encry_pass' WHERE recoveryemail_enc = '$emailKey'");

          if($update_pass){
            $delete_pass = $db_conn -> query("UPDATE users SET recoveryemail_enc = '' WHERE recoveryemail_enc = '$emailKey'");
            if($delete_pass){
              echo "<script> alert('Successfully Updated'); </script> ";
              echo "<script> window.location.href = 'http://caucusio.com'; </script> ";
            }
            //header("Location:login.php");
          }
        }
        else {
          echo "<script> alert('The new password doesnot match.'); </script> ";
        }
      }
    }
  } else {
  echo "<script> window.location.href = 'http://caucusio.com'; </script> ";
  }
}
else {
echo "<script> window.location.href = 'http://caucusio.com'; </script> ";
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="css/login_register.css">
  <script type="text/javascript" src = "js/jquery.min.js"></script>
</head>
<body>

  <body style = "background : #fafafa">

    <div id = "whole" >
      <div id = "home_img">
        <img src="images/CaucusHome.png" alt="" style = "heigth: 100%; width: 100%;  margin-left: 10px;">
      </div>

      <div id = "user_in">
        <div id = "Caucus_logo">
          <img src = "images/CaucusLogo.png" alt = "Caucus Logo"  style = "width: 75%; " />
          <h2> Let No Question Go Unanswered </h2>
        </div>

        <div id = "wrap_forgot" style = "display: block;">
          <div id = "forgot" >
            <form action = "" method = "POST">
              <div  class="g-signin2" data-onsuccess="onSignIn" data-theme="light"></div>
              <div id = "U_txtbox" class = "username">
                <p> New Password</p>
                <input type = "text" name="newPass" id = "emailtxt" class = "text" required> <br/>
              </div>

              <div id = "U_txtbox" class = "username">
                <p>Confirm Password:</p>
                <input type = "text" name="comPass" id = "emailtxt" class = "text" required> <br/>
              </div>

              <br/>

              <div id = "U_txtbox" class = "signup">
                <input type = "submit" value = "Reset Password" name = "subEmail" class ="question_submit" style = "width: 80%;"/>
              </div>

            </form>

            <br/>
          </div>
           <br/>
        </div>

      </div>
    </div>

    <footer><p style = "font-size: 1em;"> Copyright &copy Sishir Giri All Rights Reserved. </p> </footer>

  </body>
  </html>
