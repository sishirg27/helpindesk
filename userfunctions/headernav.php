<script>
$(document).ready(function(){
  $('input.typeahead').typeahead({
    name: 'typeahead',
    remote:'<?php echo $back;?>userfunctions/searchfunc/search.php?key=%QUERY',
    limit : 10
  });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
  <?php if(isset($_GET['typeahead'])) {
    $que_txt = $_GET['typeahead'];
    ?>
    $("#searchbox").val("<?php echo htmlspecialchars($que_txt);?>");
    <?php } ?>
  });
  </script>

  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">

  </head>
  <body>


    <div id = "header" style = "position: <?php echo $position;?>"> <!-- css in style.css-->
      <div id = "header_child" style = "margin-top: 5px;"> <!-- css in style.css-->
        <a href = "<?php echo $back;?>home.php"> <img class = "caucus_logo" src = "<?php echo $back;?>images/Caucus-1.png" alt = "Caucus Logo" style = "height:40px; display: inline; margin-top: 15px;"/> </a>
        <div id="custom-search-input"> <!-- css in search.css-->
          <div class="input-group col-md-12">
            <form action = "<?php echo $back;?>userfunctions/searchfunc/searchresult.php" id = "search_form" >
              <input type="text" id = "searchbox" name="typeahead" class="typeahead tt-query" autocomplete="off"  spellcheck="false" placeholder="Search Questions" size="65" />
            </form>
            <span class="input-group-btn">
              <button class="btn btn-info btn-lg" type="button" id = "search" onclick = "clicksearch()">
                <i class="glyphicon glyphicon-search"></i>
              </button>
            </span>
          </div>
        </div>

        <script>

        function clicksearch() {
          $('#search').click(function (){
            if($('#searchbox').val().length != 0 ) {
              $("#search_form").submit();
              $("#search_form").submit();
            }
            else {
            }
          });
        }

        $('#searchbox').keydown(function(event){
          var keyCode = (event.keyCode ? event.keyCode : event.which);
          if (keyCode == 13) {
            $('#search').trigger('click');
            $('#search').trigger('click');
          }
        });

        </script>

        <?php
        $no_pic = "images/default_pic.png";
        $pic = "user_data/profile_pic/";
        user_profile($no_pic,$pic);
        ?>

        <div  id = "allnotif_dd"> <!-- css in style.css -->
          <ul class="nav" style = "float: right; margin-right: 0px; padding: 5px 5px; margin-top: 10px;">

            <!-- ****************************************************
            *************** NOFIFICATION FOR PRIVATE_QUERIES ******** -->

            <div id="notification_count_query"></div>
            <li class="button-dropdown" style = "width: 50px; float: left;border-right: 1px solid lightgray;">
              <a href="javascript:void(0)" class="dropdown-toggle" id="notificationLink" style= "height: 35px;"onclick = "all_function_privatequery()">
                <i class="fa fa-question-circle-o" aria-hidden="true" id = "notifbell" style= "font-size: 24px; margin-top: -5px;"></i>
              </a>

              <ul class="dropdown-menu" style = "margin-left: -221px; width: 295px;">
                <div class = "limit_notif_query" >
                  <li id = "txt_query"> </li>
                </div>
              </ul>

            </li>

            <!-- ****************************************************
            *************** NOFIFICATION FOR LIKE AND COMMENT ******** -->

            <div id="notification_count" style = "margin-left: 73px;"></div>
            <li class="button-dropdown" style = "width: 50px; float: left;border-right: 1px solid lightgray;">
              <a href="javascript:void(0)" class="dropdown-toggle" id="notificationLink" style= "height: 35px;"onclick = "all_func()"  >
                <i class="fa fa-bell" aria-hidden="true" id = "notifbell"  ></i>
              </a>
              <ul class="dropdown-menu" style = "margin-left: -221px; width: 295px;">
                <div class = "limit_notif">
                  <li id = "txt"> </li>
                </div>
              </ul>

            </li>

            <li class="button-dropdown" style = "width: 50px; float: left; ">
              <a href="javascript:void(0)" class="dropdown-toggle" style= "height: 35px;">
                <!-- <img  src = "<?php echo $profile_pic;?>"  width= "35" height="35" id = "wrap" alt = "<?php echo $_SESSION['sess_user'];  ?>"  title = "<?php echo $_SESSION['sess_user'];  ?>" style = " border: 1px solid lightgray; border-radius: 100%;vertical-align: bottom;">-->
                <span class="glyphicon glyphicon-menu-hamburger" style = "margin-top: -2.5px;  "></span>
              </a>
              <ul class="dropdown-menu" id="usermenu" style = "margin-left: -108px; width: 175px;">
                <li>
                  <a href="<?php echo $back;?>userinfo/user_queries.php">
                    <img src = "<?php echo $back;?>images/mycauc.png"  width= "25" height = "25" title = "Caucus"/>&nbsp; &nbsp;  My Queries
                  </a>
                </li>

              <!--  <li>
                  <a href="#">
                    <img src = "<?php echo $back;?>images/support.png"  width= "25" height = "25" title = "Caucus"/>&nbsp; &nbsp; Support
                  </a>
                </li>

                <li>
                  <a href="#">
                    <img src = "<?php echo $back;?>images/contact.png"  width= "25" height = "25" title = "Caucus"/>&nbsp; &nbsp; Contact us
                  </a>
                </li> -->

                <li>
                  <a href="<?php echo $back;?>reportbug.php">
                    <img src = "<?php echo $back;?>images/bug.png"  width= "25" height = "25" title = "Caucus"/>&nbsp; &nbsp; Report a bug
                  </a>
                </li>

                <li>
                  <a href="<?php echo $back;?>logout.php">
                    <img src = "<?php echo $back;?>images/logout.png"  width= "25" height = "25" title = "Caucus"/>&nbsp; &nbsp; Log out
                  </a>
                </li>

              </ul>
            </li>
          </ul>
        </div>
      </div> <br>
    </div>

    <script type="text/javascript" charset="utf-8">

    function namemsg_privatequery(type,msgname){
      $('#txt_query').html(msgname);
    }

    function addmsg_privatequery(type, msg){
      $('#notification_count_query').html(msg);
    }

    function cutmsg_privatequery(type, msg1){
      $('#notification_count_query').html(msg1);
    }

    function removeNotification_privatequery(){
      $.ajax({
        type: "GET",
        url: "<?php echo $back;?>userfunctions/searchfunc/remove.php",

        async: true,
        cache: false,
        timeout:50000,
        data:{
          'remove_private': 1
        },
        success: function(data){
          cutmsg_privatequery("new", data);
          setTimeout(
            waitForMsg_privatequery,
            1000
          );
        }
      });
    }

    function waitForMsg_privatequery(){
      $.ajax({
        type: "GET",
        url: "<?php echo $back;?>userfunctions/searchfunc/select.php",

        async: true,
        cache: false,
        timeout:50000,
        data:{
          'select_private': 1
        },

        success: function(data){
          addmsg_privatequery("new", data);
          setTimeout(
            waitForMsg_privatequery,
            1000
          );

          if(data == 0){
            $('#notification_count_query').hide();
          }
          else {
            $('#notification_count_query').show();
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
          addmsg_privatequery("error", textStatus + " (" + errorThrown + ")");
          setTimeout(
            waitForMsg_privatequery,
            15000);
          }
        });
      };

      var back =  '<?php echo $back;?>'
      function nameMsg_privatequery(){
        $.ajax({
          type: "POST",
          url: "<?php echo $back;?>userfunctions/searchfunc/notifmsg.php",

          async: true,
          cache: false,
          timeout:50000,
          data: {
            'notif_privatequery': 1,
            'back': back
          },
          dataType: 'html',

          success: function(data){
            namemsg_privatequery("new", data);
            setTimeout(
              waitForMsg_privatequery,
              1000
            );
          },
          error: function(XMLHttpRequest, textStatus, errorThrown){
            addmsg_privatequery("error", textStatus + " (" + errorThrown + ")");
            setTimeout(
              waitForMsg_privatequery,
              15000);
            }
          });
        };

        function all_function_privatequery(){
          nameMsg_privatequery();
          removeNotification_privatequery();
        }

        $(document).ready(function(){
          waitForMsg_privatequery();
          nameMsg_privatequery();
        });

        </script>

        <script>

        /***********************************************************************************
        ************************************ NOTIFICATION FOR COMMENTS AND LIKES BEGGIN **********
        ************************************************************************/

        function namemsg(type, msgname){
          $('#txt').html(msgname);

        }

        function addmsg(type, msg){
          $('#notification_count').html(msg);
        }

        function cutmsg(type, msg1){
          $('#notification_count').html(msg1);
        }


        function removeNotification(){
          $.ajax({
            type: "GET",
            url: "<?php echo $back;?>userfunctions/searchfunc/remove.php",

            async: true,
            cache: false,
            timeout:50000,
            data:{
              'remove': 1
            },
            success: function(data){
              cutmsg("new", data);
              setTimeout(
                waitForMsg,
                1000
              );
            }
          });
        }


        function waitForMsg(){
          $.ajax({
            type: "GET",
            url: "<?php echo $back;?>userfunctions/searchfunc/select.php",

            async: true,
            cache: false,
            timeout:50000,
            data:{
              'select':1
            },
            success: function(data){
              addmsg("new", data);
              setTimeout(
                waitForMsg,
                1000
              );

              if(data == 0){
                $('#notification_count').hide();
                document.title = '<?php echo $pgtitle;?>';
              }
              else {
                $('#notification_count').show();
                document.title = '<?php echo $pgtitle;?> (' + data + ')';
              }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
              addmsg("error", textStatus + " (" + errorThrown + ")");
              setTimeout(
                waitForMsg,
                15000);
              }
            });
          };

          var back = '<?php echo $back;?>';

          function nameMsg(){
            $.ajax({
              type: "POST",
              url: "<?php echo $back;?>userfunctions/searchfunc/notifmsg.php",

              async: true,
              cache: false,
              timeout:50000,
              data: {
                'notif': 1,
                'back': back
              },
              dataType: 'html',

              success: function(data){
                namemsg("new", data);
                setTimeout(
                  waitForMsg,
                  1000
                );
              },

              error: function(XMLHttpRequest, textStatus, errorThrown){
                addmsg("error", textStatus + " (" + errorThrown + ")");
                setTimeout(
                  waitForMsg,
                  15000);
                }
              });
            };

            function all_func(){
              nameMsg();
              removeNotification();
            }

            $(document).ready(function(){
              waitForMsg();
              nameMsg();
            });


            </script>
          </body>
          </html>
