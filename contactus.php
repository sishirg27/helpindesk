<?php
session_start();
error_reporting(0); // If you do not want the user to see any error
//error_reporting(E_ALL); // to display all the error in a elegant fashion
//ini_set('display_errors', 'On');
include("ext_functions/connect.php");
include("ext_functions/security.php");
include("ext_functions/select_user.php");
include("ext_functions/profile_pic_upload.php");

// if this is not register or in activity: We are doing Redirection
if(!isset ($_SESSION['sess_user'])) {
  header("location: login.php");
}

else {
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Contact Us</title>
   <?php
   $back = "";
   include("css/defaultcss.php");
   ?>

  <link rel="stylesheet" href="css/popup.css"> <!-- css for the popup for ask someone in private -->
  <link rel="stylesheet" href="css/caucusbox.css"> <!-- to show YOUR CAUCUSES boxes AND THE EDIT Profile popup -->
  <link rel="stylesheet" href="css/contactus.css">
  </head>

  <body style = "background: #fafafa">

    <?php
    $back = "";
    $position= "fixed";
    $pgtitle = "Helpindesk";
    include("userfunctions/headernav.php");
    ?>

    <script type="text/javascript" src = "js/cauJS/scrollAnchor.js"></script> <!-- JS for scroller anchor for user-profile menu -->
    <script type="text/javascript" src = "js/cauJS/userStats.js"></script> <!-- JS for the User profile Stats and for showing classes in Your Classes -->
    <script type="text/javascript" src="js/mathquill.js"></script>
    <script type="text/javascript" src="js/ckeditor.js"></script>

  <br/> <br/> <br/> <br/> <br/> <br/> <br/>

    <div class="contact_wrap">
      <div class="contact_cover">
         <div class="contact_infotxt">
          <h1><b> Contact Us</b></h1>
          <p>We are here to answer any questions you may have about Helpindesk. Reach out to us and we'll respond as soon as we can. </p>
      </div>

      <div class="contact_txtboxes">
         <span> Name: </span> <br/>
         <input type="text" name="" value="">

          <br/>

         <span> Email: </span> <br/>
         <input type="text" name="" value="">

           <br/>

         <span> Message: </span> <br/>
         <input type="text" name="" value="">
      </div>

   </div>
</div>

  </body>
</html>
<?php } ?>
