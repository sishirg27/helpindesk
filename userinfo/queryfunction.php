<?php
// IF and SWITCH to check ajax sending functions
if (isset($_GET['f'])) {
  switch ($_GET['f']) {

    case 'que':
    get_queries();
    break;

    case 'resNum':
    getResolvedQue();
    break;

    default:
    header('Location: ../home.php');
    exit;

  }
}
?>


<?php
// function for dispaying post
function get_queries(){
  include_once("../ext_functions/connect.php");
  include_once("../ext_functions/security.php");


  if(isset($_GET['user_id'], $_GET['limit'], $_GET['offset'])) {
    $user_ids = escape($_GET['user_id']);
    $limit    = escape($_GET['limit']);
    $offset   = escape($_GET['offset']);

    $get_queries = $db_conn -> query("SELECT * FROM  cau_questions WHERE user_id = '$user_ids' AND type = 0 ORDER by id DESC LIMIT {$limit} OFFSET {$offset} ");
    $num_queries = $get_queries -> num_rows;

    if($num_queries != 0){
      while($row_posts = $get_queries -> fetch_assoc()){
        $all_id = $row_posts['id'];
        ?>
      <!--  <iframe src="advoptions.php?all_id=<?php echo $all_id; ?>"   frameborder ='0' scrolling="no" > </iframe> -->

      <iframe src="advoptions.php?all_id=<?php echo $all_id; ?> "  scrolling = "no" onload="resizeIframe(this)" frameborder ='0' style = "width: 725px; max-height: 600px;">  </iframe>

        <?php
      }
      ?>
      <script>
        function resizeIframe(obj) {
          obj.style.height = obj.contentWindow.document.body.scrollHeight + 10 +'px';
        }
      </script>
      <?php
    }
  }
}



function get_queries_private(){
  include "../ext_functions/connect.php";
  include_once("../ext_functions/security.php");
  include_once("../ext_functions/prevent.php");
  include "../ext_functions/time.php";
  include "../ext_functions/select_user.php";

  $select_uPrivate= "SELECT cau_questions.id, cau_questions.question_uid, cau_questions.cate_id, cau_questions.resolve, cau_questions.question, cau_questions.type, cau_questions.date, users.user_name, users.profile_pic, caucus_class.title
  FROM cau_questions
  LEFT JOIN users
  ON users.user_id = cau_questions.user_id
  LEFT JOIN caucus_class
  ON caucus_class.cate_id = cau_questions.cate_id
  WHERE cau_questions.user_id = '$user_ids' AND cau_questions.type = 1
  ORDER BY date DESC";

  $run_uPrivate = $db_conn -> query($select_uPrivate);
  while($row_private = $run_uPrivate -> fetch_object()){

    $user_question = htmlspecialchars_decode($row_private->question);
    $date          = escape($row_private->date);
    $post_id       = escape($row_private->id);
    $tags          = escape($row_private->title);
    $profile_pic   = escape($row_private->profile_pic);
    $type          = escape($row_private->type);
    $question_uid  = escape($row_private->question_uid);
    $resolve_nu    = escape($row_private->resolve);
    date_default_timezone_set('America/Los_Angeles');

    $get_user = $db_conn->query("SELECT user_name FROM users WHERE user_id = '$question_uid'");
    $row =   $get_user-> fetch_object();
    $question_idsname = escape($row->user_name);

    if($profile_pic == "default_pic.png"){
      $update_profile_pic = "../images/default_pic.png";
    }
    else {
      $update_profile_pic = "../user_data/profile_pic/". $profile_pic;
    }

    $select_numans = $db_conn -> query("SELECT comm_id FROM cau_comments WHERE post_id = '$post_id'");
    $num_ans = $select_numans -> num_rows;

    ?>

    <div id = 'cover_posts' >
      <div id = 'diss_posts'>
        <div id = "alignuser_img" style = "position: relative">
          <img src = "<?php echo $update_profile_pic; ?>"  alt = "<?php echo $user_name;?>"  id = "comm_cover" style = "height: 40px; width: 40px;  object-fit: cover;"/>
          <a style = "color: #333;"> <b> <?php echo ucfirst($user_name); ?> </b> </a>  <i class="fa fa-arrow-right" aria-hidden="true" style = "font-size: 13px;"></i> <a> <b> <?php echo ucfirst($question_idsname);?></b> </a>   <br>
          <p style = 'margin-top: 0px;' id = 'que_extra'> &nbsp; <?php echo time_ago($date); ?>
            <span> <a> <?php echo ucfirst($tags); ?> </a>

              <img src = "../images/num_comm.png" height = "14" width = "14" style = " "/>
              <a id = "number_ans" style = "font-weight: 600; font-size: 0.9em; color: black; margin-left: 2px;"><?php echo $num_ans;?></a>
             <span style = "border-left: 1px solid lightgray;"> <i class="fa fa-user" aria-hidden="true" style = "width: 15px; margin-left: 3px;"></i> </span> </span>
           <?php if($question_uid != 0 && $resolve_nu == 1) {?>
             <a id = "resolve_btn"  title="Resolved!"  style = "position: absolute; right: 50px; top: -5px; border-radius: 100px; background: green; height: 25px; width: 25px; text-align: center;"> <i class="fa fa-check" aria-hidden="true" style = "color:white; margin-top: 6px;"></i> </a>
          <?php } ?>
          </p>
       </div>

       <div class = 'dropdown' style= 'margin-right: 20px;'>
         <?php include_once("ellipsisDdown.php");?>
       </div>

          <div id = "question"> <a  target='_parent' href = '../class/anspage.php?p_id=<?php echo $post_id;?>'> <?php echo securify($user_question); ?> </a> </div>
      </div>
    </div>

   <script type="text/javascript">
   var p_id = '.dQue<?php echo $post_id; ?>';
   var post_id = '<?php echo $post_id?>';
   var u_id = '<?php echo $user_ids; ?>';

  $(p_id).click(function () {
    $.ajax({
         type: 'POST',
         url: 'deletequery.php',

         data: {
           'post_id': post_id,
           'user_id': u_id
         },

         success:function(data){
             window.parent.location.reload();
         }
    });
  });
   </script>

<?php
 }
}



function get_asked_queries_private(){
 include("../ext_functions/connect.php");
 include_once("../ext_functions/security.php");
 include_once("../ext_functions/prevent.php");
 include("../ext_functions/select_user.php");

  $select_uPrivate= "SELECT cau_questions.id, cau_questions.question_uid, cau_questions.user_id,cau_questions.resolve, cau_questions.question, cau_questions.type ,cau_questions.date, users.user_name, users.profile_pic, caucus_class.title
  FROM cau_questions
  LEFT JOIN users
  ON users.user_id = cau_questions.user_id
  LEFT JOIN caucus_class
  ON caucus_class.cate_id = cau_questions.cate_id
  WHERE cau_questions.question_uid = '$user_ids' AND cau_questions.type = 1
  ORDER BY date DESC";

  $run_uPrivate = $db_conn -> query($select_uPrivate);
  while($row_private = $run_uPrivate -> fetch_object()){

    $user_question =  htmlspecialchars_decode($row_private->question);
    $date         = escape($row_private->date);
    $user_id      = escape($row_private->user_id);
    $post_id      = escape($row_private->id);
    $tags         = escape($row_private->title);
    $profile_pic  = escape($row_private->profile_pic);
    $type         = escape($row_private->type);
    $question_uid = escape($row_private->question_uid);
    $resolve_nu   = escape($row_private->resolve);
    date_default_timezone_set('America/Los_Angeles');

    $get_user = $db_conn->query("SELECT user_name FROM users WHERE user_id = '$user_id'");
    $row =   $get_user->fetch_object();
    $question_idsname = escape($row->user_name);

    if($profile_pic == "default_pic.png"){
      $update_profile_pic = "../images/default_pic.png";
    }
    else {
      $update_profile_pic = "../user_data/profile_pic/". $profile_pic;
    }

    $select_numans = $db_conn -> query("SELECT comm_id FROM cau_comments WHERE post_id = '$post_id'");
    $num_ans = $select_numans -> num_rows;

    ?>

    <div id = 'cover_posts' >
      <div id = 'diss_posts'>
        <div id = "alignuser_img" style = " position: relative">
          <img src = "<?php echo $update_profile_pic; ?>"  alt = "<?php echo $user_name;?>"  id = "comm_cover" style = "height: 40px; width: 40px;  object-fit: cover;"/>
          <a style = "color: #333;"> <b> <?php echo ucfirst($question_idsname); ?> </b> </a>  <i class="fa fa-arrow-right" aria-hidden="true" style = "font-size: 13px;"></i> <a> <b> <?php echo ucfirst($user_name);?></b> </a> <br>

          <p style = 'margin-top: 0px; ' id = 'que_extra'> &nbsp; <?php echo time_ago($date); ?>
            <span> <a> <?php echo ucfirst($tags); ?> </a>
              <img src = "../images/num_comm.png" height = "14" width = "14" style = " "/>
              <a id = "number_ans" style = "font-weight: 600; font-size: 0.9em; color: black; margin-left: 2px;"><?php echo $num_ans;?></a>
             <span style = "border-left: 1px solid lightgray;"> <i class="fa fa-user" aria-hidden="true" style = "width: 15px; margin-left: 3px;"></i> </span> </span>
            <?php if($question_uid != 0 && $resolve_nu == 1){?>
               <a id = "resolve_btn"  title="Resolved!"  style = "position: absolute; right: 30px; top: 0px; border-radius: 100px; background: green; height: 25px; width: 25px; text-align: center;"> <i class="fa fa-check" aria-hidden="true" style = "color:white; margin-top: 6px;"></i> </a>
            <?php }?>
               </p>
           </div>

        <div id = "question"> <a  target='_parent' href = '../class/anspage.php?p_id=<?php echo $post_id;?>' style = "color: #333;"> <?php echo securify($user_question); ?> </a> </div>
      </div>
    </div>

    <?php
  }
}

function getResolvedQue(){
  include("../ext_functions/connect.php");

  if(isset($_GET['user_id'])) {
  $user_ids = $_GET['user_id'];
  $select_userQue = $db_conn -> query("SELECT id FROM cau_questions WHERE question_uid = $user_ids AND resolve = 1");
  $num_res = $select_userQue -> num_rows;
  echo $num_res;
 }
}
