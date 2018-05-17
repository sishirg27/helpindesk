<?php
session_start();
//error_reporting(0); // If you do not want the user to see any error
error_reporting(E_ALL); // to display all the error in a elegant fashion
ini_set('display_errors', 'On');
include("../ext_functions/connect.php");
include("../ext_functions/security.php");
include("../ext_functions/select_user.php");
include("../ext_functions/profile_pic_upload.php");
include("../ext_functions/get_user.php");
include("queryfunction.php");

// select if the user is logged in or not

if(!isset($_SESSION['sess_user'])) {
  header("location:../login.php");
}
else {
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <link rel="shortcut icon" href="../images/helpindeskmini.png" type="image/x-icon" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <?php
  $back = "../";
  $pgtitle = "$user_name | Queries";
  include("../css/defaultcss.php");
  ?>


  </head>

  <body style = "background:#fafafa">
    <?php //includes the header searchbox including notification dropdowns
    $back = "../";
    $position= "fixed";
    include("../userfunctions/headernav.php");
    ?>

    <script type="text/javascript" src = "../js/cauJS/scrollAnchor.js"></script> <!-- JS for scroller anchor for user-profile menu -->
    <script type="text/javascript" src = "../js/cauJS/userQ.js"></script>


    <br/> <br/> <br/> <br/> <br/>

    <div style = "height: auto; overflow: hidden; margin-left: auto; margin-right: auto; width: 1300px;">
      <div class="scroller_anchor"></div>
      <div id = "queries_stats">
        <p id = "subj"> Subject Statistics </p>

        <?php
        $select_class = $db_conn -> query("SELECT * FROM subjects");

        while($getsubs = $select_class -> fetch_object()) {
          $id = escape($getsubs->id);
          $subject = escape($getsubs->subjects);

          $select_question = $db_conn -> query("SELECT id FROM cau_questions WHERE user_id = '$user_ids' AND sub_id = '$id'");
          $numQue = $select_question -> num_rows;

          $select_Bans = $db_conn -> query("SELECT leader_id FROM cau_leaderboard WHERE leader_id = '$user_ids' AND sub_id = '$id'");
          $num_Bans = $select_Bans -> num_rows;
          ?>

          <div class="accordion">
            <h3> <?php echo $subject; ?></h3>
            <div id = "nums">
              <p> <b> <?php echo $numQue;?> </b> <br> <span> Quer. </span> </p>
              <p>  <b> <?php echo $num_Bans;?> </b> <br> <span> B.Ans </span> </p>
            </div>
          </div>
          <?php } ?>
        </div>

        <div class = 'wrap_box' style = "width: 750px;margin-right: 75px;">
          <div class="container" style = "width: 100%;">
            <!-- Css connected to home_main.css-->
            <ul class="nav nav-tabs" style = "border: none; font-size: 11.5px;">
              <li class="active" > <a data-toggle="tab" href="#home" class = "pubQue"> Public Queries </a></li>
              <li><a data-toggle="tab" href="#menu1" class = "priQue" > Private Queries</a></li>
            </ul>

            <div class="tab-content" >
              <div id="home" class="tab-pane fade in active">
                <div class = 'style2' style = "min-height: 500px;height: auto; overflow: hidden;border: none;">
                  <div id = "diss_queries_Science" style = "width: 100%; height: auto; overflow:hidden;"></div>
                  <div id= "loadmoreajaxloader"> <img src = "../images/loading.gif" style = "height: 60px; width: 60px;"/> </div>
                  <p id = 'noquery' > <b> You don't have any Caucus. </b> </p>
                </br>
              </div>
            </div>

            <div id="menu1" class="tab-pane fade" style = "height: auto; overflow: hidden">
              <div class = "style2"  style = "height: auto;border: none;">
                <br>
                <br>
                <div class="nav" style = "font-size: 13px; margin-left: 10px;">
                  <nav>
                    <a href="#1" class = "transfer">My Queries</a> &nbsp;
                    <a href="#2" class = "transfer1">Asked Queries</a>&nbsp;
                  </nav>
                </div>

                <div id="first" class="navLinks"  style = "display: block;">  <?php  get_queries_private(); ?></div>
                <div id="second" class="navLinks">
                   <br/> <p id  = "resQue"> Queries Resoved: <b><span id = "resQuenum"></span></b> </p>
                   <?php get_asked_queries_private(); ?>
                 </div>
              </div>

              <script type="text/javascript">
              document.addEventListener('DOMContentLoaded', function() { // this is similar to document.ready function
                // all three functions are concerned with the user profile stats
                //get the scrolldown function for user_profile
                user_profileScroll('#queries_stats');

                // get the question posts anso queries stats stick on left
                var u_id = '<?php echo $user_ids;?>';
                uQue(u_id);

                // toggle between myqieries and asked queries_stats
                toggleQue();

                // get Number Resolved queries
                getResolvedNum(u_id);

              });
              </script>

            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  </html>
  <?php
}
?>
