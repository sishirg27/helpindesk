<?php
require_once "connect.php";
require_once "security.php";
require_once "datafilter.php";

if(isset($_POST['reset'])){
  if(isset($_POST['emailtxt'])){

    // Harvest submitted email address
    if(filter_var($_POST['emailtxt'], FILTER_VALIDATE_EMAIL)){
      $email = escape($_POST['emailtxt']);
      //echo $email;
    } else {
      echo "Email is not Valid";
       exit;
    }

    // Check to see if a user exists with this email
    $query = $db_conn -> query("SELECT email FROM users WHERE email = '$email'");
    $userExist = $query -> fetch_object();
    $userEmail = escape($userExist->email);

     echo $userEmail;
  }
}

?>
