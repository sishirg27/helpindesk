<?php
session_start();
// all the comments in the class
error_reporting(0); // If you do not want the user to see any error
//error_reporting(E_ALL); // to display all the error in a elegant fashion
//ini_set('display_errors', 'On');

include_once("../ext_functions/connect.php");
include_once("../ext_functions/security.php");
include_once("../ext_functions/select_user.php");

if(!isset($_SESSION['sess_user'])) {
  header("location:../login.php");
}
else {

if(isset($_GET['p_id'])){
    $post_id = escape($_GET['p_id']);
    $u_id = escape($user_ids);

    if(!empty($post_id)){
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="../css/home_main.css"> <!-- css for textbox and button and number of ans -->
    <script type="text/javascript" src = "../js/jquery.min.js"></script>
  </head>
  <body style = "background: #eee;">

    <div style = "margin-top: 10px;" id = "total_numans">
     <img src = "../images/num_comm.png" height = "17" width = "17" />

     <div style = "height: auto; overflow: hidden; vertical-align: middle; margin-left: 23px;">
       <a id = "number_ans" style = "float: left;"></a>
       <span style = "margin-left: 3px;">ans </span>
     </div>

    </div>

    <div id = 'send' >
      <div id = 'send_txtbox' >
        <div id = 'comm_form' style = "width: 100%">
          &nbsp; <input type='text'  placeholder = 'Write your answer...'  id = 'text_que' style = "height: 17px; font-size: 13px; padding: 7px 10px; width: 85%;"  required>
          <button value="Post" id = "grab"> <img src = "../images/send.png"/> </button>
        </div>
      </div>
    </div>



    <script type="text/javascript">
  // WE NEED JS RIGHT HERE
    function addmsg(type, msg){
      $('#number_ans').html(msg);
    }

    function waitForMsg(){
      $.ajax({
        type: "GET",
        url: "numans.php?p_id=<?php echo $post_id;?>",

        async: true,
        cache: false,
        timeout:50000,

        success: function(data){
          addmsg("new", data);
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

      $(document).ready(function(){
        waitForMsg();
      });


    function submit_comm(){
      var comment_co = $('#text_que').val();
      var post_id = '<?php echo $post_id; ?>';
      var user_id = '<?php echo $u_id; ?>';

     $.ajax({
        type: 'POST',
        url: 'classfunctions.php?f=comm',
        data:{
          'comm_post':1,
          'post_id': post_id,
          'user_id': user_id,
          'comment_comm':comment_co
        },
        success:function(data){
           $('#text_que').val("");
        //  window.location.reload();
        }
      });
    }

    $('#text_que').keypress(function(e) {
      if(e.which == '13'){
        submit_comm();
      }
    });

    $('#grab').click(function() {
  submit_comm();
    });

    </script>
  </body>
</html>

<?php
    }
  }
}
?>
