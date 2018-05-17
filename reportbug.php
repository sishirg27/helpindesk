<?php
session_start();
//error_reporting(0);
error_reporting(E_ALL); // to display all the error in a elegant fashion
ini_set('display_errors', 'On');
include("ext_functions/connect.php");
include("ext_functions/security.php");
include("ext_functions/select_user.php");
include("ext_functions/profile_pic_upload.php");

// if this is not register or in activity: We are doing Redirection
if(!isset ($_SESSION['sess_user'])) {
  header("location: login.php");
}

else{
  ?>

  <!DOCTYPE html>
  <html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/helpindeskmini.png" type="image/x-icon" />

    <link rel="stylesheet" href="css/reportBug.css"> <!-- concerned with user_profile -->
    <link rel="stylesheet" href="css/caucusbox.css"> <!-- to show YOUR CAUCUSES boxes -->
    <link rel="stylesheet" href="css/search.css"> <!-- css for search box at the header -->
    <link rel="stylesheet" href="css/style.css"> <!-- css to design the header including the search box and the nav  -->
    <link rel="stylesheet" href="css/homenotif.css"> <!-- css for the notification dropdown and menu dropdown -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <script type="text/javascript" src = "js/jquery.min.js"></script>
    <script type="text/javascript" src = "js/bootstrap.min.js"></script>
    <script type="text/javascript" src = "js/popupdropdown.js"></script>
    <script type="text/javascript" src = "js/typeahead.min.js"></script>
  </head>

  <body>
    <?php
    $back = "";
    $position= "relative";
    $pgtitle = "Helpindesk";
    include("userfunctions/headernav.php");
    ?>


    <div class="wrapper">
      <h2> Report a Bug </h2>

      <br/> <br/>
      <?php if($user_ids != 1){?>
        <div class = "form">
          <div class="form-group">
            <label for="formGroupExampleInput">Subject</label>
            <input type="text" class="form-control" id="subject" placeholder="Example: Comment Post" required>
          </div>

          <label for="formGroupExampleInput">Bug</label>
          <textarea class="form-control" id="bugtext" name="text" placeholder="Please be very Specific"  rows="10" required></textarea>
        </br>
        <button class="btn btn" type="submit" id = "report">Report Bug</button>

        <div id = "thank_bug">
          <button id = "resolve_btn" title="Thank You!" >
            <i class="fa fa-check" aria-hidden="true" style = "color:white; margin-top: 4px; font-size: 18px;"></i>
          </button>
          <a> &nbsp; Thank You. The Bug will be fixed in few days. </a>
        </div>
      </div>
    <?php }
    else if($user_ids == 1){ ?>

      <p> Users: <span style = "font-size: 26px;" id = "numUsers"> </span> </p>

      <div id = "test"></div>

    <?php }?>

    <br/>
  </div>

  <script type="text/javascript">

    $(document).ready(function() {
      getUsersNum();
      getBug();
    });

  function changeUtxt(type, msgname){
    $('#numUsers').html(msgname);
  }

  function getBug(){
    $.ajax({
      type: 'GET',
      url: 'profFuns/insertbug.php?f=get_bug',
      success:function(data){
        $('#test').html(data);
      }
    });
  }

  function getUsersNum(){
    $.ajax({
      type: 'GET',
      url: 'profFuns/insertbug.php?f=getnumUser',

      async: true,
      cache: false,
      timeout:50000,

      success: function(data){
        changeUtxt("new", data);
        setTimeout(
          getUsersNum,
          1000
        );
      },
    });
  }

  </script>

  <script type="text/javascript">
  $('#report').click(function (){
    var subject = $('#subject').val();
    var bugtxt = $('#bugtext').val();

    $.ajax({
      type: 'POST',
      url: 'profFuns/insertbug.php?f=ins_bug',
      data: {
        'subj': subject,
        'bug': bugtxt
      },

      success:function(data){
        $('#subject').val('');
        $('#bugtext').val('');
        $('.btn').hide();
        $('#thank_bug').show();
      }
    });
  });

  </script>

</body>
</html>

<?php
}
?>
