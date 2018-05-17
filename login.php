<?php
namespace PHPMailer\PHPMailer;
error_reporting(0); // If you do not want the user to see any error
//error_reporting(E_ALL); // to display all the error in a elegant fashion
//ini_set('display_errors', 'On');

include ("ext_functions/connect.php");
include ("ext_functions/security.php");

require_once 'vendor/autoload.php';


if(isset($_POST['subEmail'])){
  $rEmail = $_POST['Remail'];
  $secret = escape(md5(uniqid(rand(), true))); // creates a random number hash
  $validateEmail = filter_var(strtolower($rEmail),FILTER_VALIDATE_EMAIL);

  $check_email = $db_conn -> query("SELECT * FROM users WHERE email = '$validateEmail'");
  $get_uData = $check_email->fetch_object();
  $username = escape($get_uData->user_name);
  $num_email = $check_email -> num_rows;

  if($num_email == 0){
    echo "<script> alert('The email doesnot exist'); </script> ";
  }
  else {
    $insert_hash = $db_conn -> query("UPDATE users SET recoveryemail_enc = '$secret' WHERE email = '$validateEmail'");

    if($insert_hash){
      $html_msg = "
      <div class='cover_resetPass' style = 'width: 700px; height: auto; overflow: hidden; margin: 0 auto; border: 1px solid lightgray;'>
      <div class='wrap_resetPass' style = 'height: auto; overflow: hidden; display: inline: width: 80%;'>
      <div class='wrap_head' style = 'height: 50px; overflow: hidden; margin: 0; text-align: center;'>
      <h2 style = 'margin-top: 10px; font-family: Segoe UI; display: inline-block'> Reset Password</h2>
      <span style = 'display: inline-block; margin-top: -20px; font-size: 32px; margin-left: 10px;'>|</span>
      <p id= 'Cautxt' style = 'display: inline-block; font-family: Calibri; margin-top: -20px; font-size: 22px; margin-left: 20px;'> Helpindesk </p>
      </div>
      <hr/>
      <div class='wrap_Msg' style = 'margin: 20px; font-family: Calibri;'>
      <p> Hi $username, </p>
      <p>You requested to reset the password for your Helpindesk account with the e-mail address ($validateEmail). Please click this link to reset your password. </p>
      <p> <a href= 'https://www.helpindesk.com/resetPass.php?key=$secret'> https://www.helpindesk.com/resetPass.php?key=$secret </a> </p>
      <p> Thanks, <br/>
      The Helpindesk Team</p>
      </p>
      </div>
      </div>
      </div>
      ";

      // echo $html_msg;

      $mail = new PHPMailer;

      $mail->IsSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.mailgun.org';                 // Specify main and backup server
      $mail->Port = 587;                                    // Set the SMTP port
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'mainemail@caucusio.com';                // SMTP username
      $mail->Password = '44XX6eB?BNwd!y@8';                  // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

      $mail->From = 'news@caucusio.com';
      $mail->FromName = 'Helpindesk';
      $mail->AddAddress($validateEmail, 'Sishir Giri');  // Add a recipient
      //$mail->AddAddress('ellen@example.com');               // Name is optional

      $mail->IsHTML(true);                                  // Set email format to HTML

      $mail->Subject = 'Reset Password';
      $mail->Body    =  $html_msg;
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


      if(!$mail->Send()) {
        echo "<script> alert('Message could not be sent.'); </script> '";
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        exit;
      }else {
        echo "<script> alert('Password recovery link has been sent to your email.'); </script> ";
        echo("<script>window.location = 'login.php';</script>");
        exit();
      }
    }
  }
}


?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title> Helpindesk </title>
  <link rel="shortcut icon" href="images/helpindeskmini.png" type="image/x-icon" />
  <link rel="stylesheet" href="css/forgetpass.css">
  <link rel="stylesheet" href="css/login_register.css">

  <script type="text/javascript" src = "js/jquery.min.js"></script>
  <script type="text/javascript" src = "js/bootstrap.min.js"></script>
  <script type="text/javascript" src = "js/cauJS/login_reg.js"></script>

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89453744-3', 'auto');
  ga('send', 'pageview');
  </script>

</head>

<body style = "background : #fafafa">

  <div id = "whole">
    <div id = "home_img">
      <img src="images/CaucusHome.png" alt="">
    </div>

<br/> <br/>

    <div id = "user_in">
      <div id = "Caucus_logo">
        <img src = "images/caucusfinal.png" alt = "Helpindesk Logo" />
      </div>
      <br/>
      <div id = "wrap_login">
        <div id = "login">

          <form method="post">
            <div  class="g-signin2" data-onsuccess="onSignIn" data-theme="light"></div>
            <div id = "U_txtbox" class = "username">
              <p> User Name </p>
              <input type = "text" name = "lname" class = "text" required> <br/>
            </div>

            <div id = "U_txtbox" class = "password">
              <p> Password </p>
              <input type = "password" name = "lpass"  class = "text" required> <br/>
            </div>

            <br/>

            <div id = "U_txtbox" class = "signup">
              <input type = "submit" name = "lsubmit" class ="question_submit" value = "Log In"/>
            </div>
          </form>
          <br/>

          <hr>
          <div id = "forgotPass" >
            <p style = "text-align: center;"> <a id = "gotoForgot">  Forgot Password?</a> </p>
          </div>
        </div>

        <br/>
        <div id = "signup" >
          <p> Don't have an account? <a id = "gotoRegister" > &nbsp; <b> Sign Up </b> </a> </p>
        </div>
        <br/> </br> <br/>
      </div>

      <?php include_once("ext_functions/login_user.php");?>

      <div id="wrap_register">
        <form method="post" action = "">
          <div id = "loginup" >
            <div id = "U_txtbox" class = "fullname">
              <p> Full Name</p>
              <input type = "text" name = "fName"  class = "text" required> <br/>
            </div>

            <div id = "U_txtbox" class = "username">
              <p> User Name </p>
              <input type = "text" name = "uName"  class = "text" required> <br/>
            </div>

            <div id = "U_txtbox" class = "password">
              <p> Password </p>
              <input type = "password" name = "uPass" class = "text" required> <br/>
            </div>

            <div id = "U_txtbox" class = "email">
              <p> Email</p>
              <input type = "text" name = "uEmail"   class = "text" required> <br/>
            </div>

            <br/>

            <div id = "U_txtbox" class = "signup">
              <input type = "submit" name = "insert_user" class ="question_submit" value = "Sign Up "/>
            </div>

            <br/>

            <div id = "terms"   >
              <p> By registering, you agree to the <a href = ""> privacy policy </a> and  <a href = ""> terms of service. </a> </p>
            </div>
          </div>
        </form>

        <br/>
        <div id = "signin" >
          <p> Already have an account? <a id = "gotoLogin"  > &nbsp;  <b> Sign In </b> </a> </p>
        </div>
        <br/> <br/> <br/>
      </div>

      <?php include_once("ext_functions/user_insert.php");?>

      <div id = "wrap_forgot">

        <div id = "forgot" >
          <form action = "" method = "POST">
            <div  class="g-signin2" data-onsuccess="onSignIn" data-theme="light"></div>
            <div id = "U_txtbox" class = "username">
              <p> Enter your Email Address</p>
              <input type = "text" name = "Remail" id = "emailtxt" class = "text" required> <br/>
            </div>

            <br/>

            <div id = "U_txtbox" class = "signup">
              <input type = "submit" value = "Reset" name = "subEmail" class ="question_submit"/>
            </div>
          </form>
          <br/>
        </div>

        <br/>
        <div id = "signin" >
          <p> <a id = "goBack"> &nbsp;  <b> Go Back </b> </a> </p>
        </div>
        <br/>
      </div>

    </div>
  </div>

  <footer><p style = "font-size: 1em;"> Copyright &copy Sishir Giri All Rights Reserved. </p> </footer>

  <script type="text/javascript">

  $('#gotoRegister').click(function (){
    $('#wrap_register').show();
    $('#wrap_login').hide();
    $('#wrap_forgot').hide();
  });

  $('#gotoLogin').click(function (){
    $('#wrap_register').hide();
    $('#wrap_forgot').hide();
    $('#wrap_login').show();
  });

  $('#goBack').click(function (){
    $('#wrap_login').show();
    $('#wrap_forgot').hide();
  });

  $('#gotoForgot').click(function () {
    $('#wrap_login').hide();
    $('#wrap_register').hide();
    $('#wrap_forgot').show();
  });
  </script>

</body>
</html>
