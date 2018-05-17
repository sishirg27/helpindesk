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
      <title>Helpindesk</title>
      <link rel="shortcut icon" href="images/helpindeskmini.png" type="image/x-icon" />
   <?php
   $back = "";
   include("css/defaultcss.php");
   ?>

  <link rel="stylesheet" href="css/popup.css"> <!-- css for the popup for ask someone in private -->
  <link rel="stylesheet" href="css/caucusbox.css"> <!-- to show YOUR CAUCUSES boxes AND THE EDIT Profile popup -->
  <link rel="stylesheet" href="css/login_register.css">
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


    <br/> <br/> <br/> <br/> <br/>

    <div class="all_wrap">
      <div class="scroller_anchor"></div>
      <div class = 'user_profile' id = "ran_color">
        <br>
        <div class= 'user_imginfo'>
          <div class="image-upload" >
            <label for="file">
              <span class="glyphicon glyphicon-camera" style = "font-size: 20px;"></span>
            </label>

            <label for="editProfile" class="cd-popup-trigger" style = "margin-top: 35px;">
              <span class="glyphicon glyphicon-edit" style = "font-size: 20px;"></span>
            </label>

            <div class="cd-popup" role="alert">
              <div class="cd-popup-container" >
                <div id = 'editProfile'>
                  <img src="images/Caucus-1.png" style = "width: 200px; height: 100%; border: none; border-radius: 0px; margin-left: 10px; margin-top: 15px;">
                  <h2> <b> Edit your Profile </b></h2>
               </div>

               <div id = "editOpts">
                <a href = "#a"> <button class = "editReal"> <img src="images/edit.png" alt="" style = "border-radius: 0; border: none;">  <span> Change  </br>  Name </span>  </button> </a>  &nbsp;
                <a href = "#b"> <button class = "editReal1"> <img src="images/edit.png" alt="" style = "border-radius: 0; border: none;"> <span> Change  </br>  User Name </span>  </button> </a> &nbsp;
                <br/> <br/>
                <a href = "#c"> <button class = "editReal2"> <img src="images/edit.png" alt="" style = "border-radius: 0; border: none;"> <span> Change  </br> Password    </span>    </button> </a> &nbsp;
                <a href = "#d"> <button class = "editReal3"> <img src="images/edit.png" alt="" style = "border-radius: 0; border: none;"> <span> Change  </br>  Email  </span></button> </a> &nbsp;
               </div>

               <div id = "editUser">
                 <div id="images">
                 <a id = 'back' style = "margin-top: 0px;"> <img src="images/back.png" alt="Edit" style = "border: none; "></a>
                 <a id = 'back' class = "editReal"> <img src="images/fullName.png" alt="Edit" style = "border: none; "></a>
                 <a id = 'back' class = "editReal1"> <img src="images/userName.png" alt="Edit" style = "border: none; "></a>
                 <a id = 'back' class = "editReal2"> <img src="images/password.png" alt="Edit" style = "border: none; "></a>
                 <a id = 'back' class = "editReal3"> <img src="images/email.png" alt="Edit" style = "border: none; "></a>
                </div>

               <p id = "ttest" style = "color: red; font-weight: 600; font-size: 14px; text-align: center; margin-left: 100px; width: 300px; "></p>

               <div id = "U_txtbox" >
                 <p> Current <span class="modifytxt"> </span> </p>
                 <input type="text" id = "currProf" required/> <br/>
               </div>

               <div id = "U_txtbox">
                 <p> New <span class="modifytxt"> </span>  </p>
                 <input type="text" id = "newProf" required/>
               </div>

               <div id = "U_txtbox">
                 <p> Confirm <span class="modifytxt"> </span> </p>
                 <input type="text" id = "cnewProf" required/>
               </div>
               <br/>

               <div id = "U_txtbox">
                 <input type="submit" id = "changeProf" value="Submit" class ="question_submit"/>
                  <br/> <br/> <br/>
               </div>
             </div>

                <a href="#0" class="cd-popup-close img-replace">Close</a>
              </div>
              <!-- cd-popup-container -->
            </div>

            <form action="home.php" id = "form" method="post" enctype="multipart/form-data">
              <input type="file" name="file" id ="file" >
            </form>
          </div>

          <?php
          // GET THE USER PROFILE COLOR
          $select_color = $db_conn -> query("SELECT color FROM users WHERE user_id = '$user_ids' and user_name = '$user'");
          $result_color = $select_color -> fetch_object();
          $color = escape($result_color->color);
          ?>

          <script type="text/javascript">
          var dom = document.getElementById('ran_color');
          var color = "<?php echo $color;?>";
          dom.style.background= 'linear-gradient(to top,' + color + ' 0%, #ffffff 100%)';
          </script>

          <script>
          document.getElementById("file").onchange = function() {
            document.getElementById("form").submit();
          };
          </script>

          <img src="<?php echo $profile_pic;?>" alt="<?php echo $user;?>" class = "cover">
          <p> <?php echo escape(ucfirst($full_name));?> </p>
        </div>
        <br>

        <div class = 'user_options'>
          <div class = 'answers'> <p> <span id = "q_ans"> </span> <br> Best Ans. </p> </div>
          <a href = "userinfo/user_queries.php"> <div class = 'queries'> <p> <span id = 'q_txt'> </span> <br> Queries </p></div></a>
          <div class = 'rank'> <p> <span id = "u_rank"> </span> <br> Rank </p> </div>
        </div>

        <div class = 'add_options'>
          <!-- <a> <img src="images/support.png" alt="support" height = "32" width = "32"/>&nbsp; Support   </a>
          <a> <img src="images/contact.png" alt="contact" height = "30" width = "30"/>&nbsp; Contact us </a> -->
          <a href = "reportbug.php"> <img src="images/bug.png" alt="report a bug" height = "30" width = "30"/>&nbsp; Report a bug </a>
          <a href="logout.php"> <img src="images/logout.png" alt="Log Out" height = "30" width = "30"/>&nbsp; Log Out </a>
        </div>

      </div>

      <div class = 'wrap_box'>

        <div class="container" style = "width: 100%;">
          <!-- Css connected to home_main.css-->
          <ul class="nav nav-tabs" style = "width: 775px;">
            <li class="active" ><a data-toggle="tab" href="#home"> Categories</a></li>
            <li ><a data-toggle="tab" href="#menu1" > Classes</a></li>
          </ul>

          <div class="tab-content" >
            <div id="home" class="tab-pane fade in active"  >
              <div class = 'style2' >
                <br>

                <?php
                for($i = 1; $i<=5; $i++){  // To get all the classes stored in CAUCUS
                  ?>

                  <div class = 'title'>
                    <?php
                    // Query to get the SUBJECT title Class
                    $select_subj = $db_conn -> query("SELECT subjects FROM subjects WHERE id = $i");
                    $result_subject = $select_subj->fetch_object();
                    ?>

                    <p> <?php echo $result_subject->subjects; ?> </p>
                  </div>

                  <div class = 'caucus_wrap' style = "height: auto;">
                    <div class = 'caucus_class'>
                      <?php
                      $select_query = $db_conn->query("SELECT * FROM caucus_class WHERE sub_id = $i");
                      while($row = $select_query -> fetch_object()){
                        $cate_id = escape($row->cate_id);
                        ?>
                        <iframe src="userfunctions/caucusbox.php?cate_id=<?php echo $cate_id;?>" frameborder ='0' width="150" height="150" scrolling = "no"  style ="padding: 0;margin:0;"></iframe>
                        <?php
                      }
                      ?>
                    </div>
                  </div>
                  <?php } ?>
                </br>
              </div>
            </div>

            <div id="menu1" class="tab-pane fade" >
              <div class = "style2" >
                <br>
                <div class = 'title'>
                  <p> Your Classes</p>
                </div>

                <div class = 'caucus_wrap'  style = "height: auto; overflow: hidden">
                  <div class = 'caucus_user'></div>
                  <!-- *********** GET THE USER SELECTED SUBJECTS ***************** -->

                  <script type="text/javascript">
                  document.addEventListener('DOMContentLoaded', function() { // this is similar to document.ready function
                    CKEDITOR.config.removePlugins = 'elementspath,link,maximize,list,';
                    CKEDITOR.config.resize_enabled = false;
                    CKEDITOR.config.height = '50px';

                    // all three functions are concerned with the user profile stats
                    user_queries();
                    user_bans();
                    user_rank();

                    //get the scrolldown function for user_profile
                    user_profileScroll('.user_profile');

                    // get_uclass returns the clicked class

                  });

                  $(document).ready(function (){
                      get_uclass();
                      corrBtnClick();

                  });

                  function hideshowEdit(){
                    $('#editUser').show();
                    $('#editOpts').hide();
                  }

                    $('#back').click(function (){
                    $('#editUser').hide();
                    $('#editOpts').show();
                    });


                    $('#changeProf').click(function (){
                      var hashKey = window.location.hash;

                      var currVal =$('#currProf').val();
                      var newVal = $('#newProf').val();
                      var nnewVal = $('#cnewProf').val();

                      if(newVal !== nnewVal){
                        alert('The new name doesnot match.');
                      }
                      if(currVal === '' || newVal === '' || nnewVal === ''){
                        alert('Please input all the values');
                      }

                      else{
                        $.ajax({
                          type: 'GET',
                          url: 'changeProfile.php',

                          data:{
                            'key': hashKey,
                            'cVal': currVal,
                            'nVal': newVal,
                            'nnewVal': nnewVal
                          },

                          success:function(data){
                            $('#ttest').html(data);
                            $('#currProf').val('');
                            $('#newProf').val('');
                            $('#cnewProf').val('');
                          }
                        });
                      }
                    });

                  </script>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<?php } ?>
