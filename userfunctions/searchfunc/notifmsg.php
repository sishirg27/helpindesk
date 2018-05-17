<?php
session_start();

include "../../ext_functions/connect.php";
include "../../ext_functions/security.php";
include "../../ext_functions/select_user.php";
include "../../ext_functions/profile_pic_upload.php";
include "../../ext_functions/time.php";



/***************************************************************
**************** NOTIFICATION FOR ALL COMMENTS **********************
*****************************************************************/


$sql1 = "SELECT likecomm_notification.user_id, likecomm_notification.post_id, likecomm_notification.respond_uid, likecomm_notification.type, likecomm_notification.status, likecomm_notification.date, users.user_id, users.user_name, users.profile_pic, cau_questions.id, cau_questions.cate_id, caucus_class.title
FROM likecomm_notification
INNER JOIN users
ON likecomm_notification.user_id = users.user_id
INNER  JOIN cau_questions
ON likecomm_notification.post_id = cau_questions.id
INNER JOIN caucus_class
ON cau_questions.cate_id = caucus_class.cate_id
WHERE  likecomm_notification.respond_uid = '$user_ids' ORDER BY date DESC LIMIT 11";

if(isset($_POST['notif'])){
$back = $_POST['back'];

$select_num_rows = $db_conn -> query("SELECT id FROM likecomm_notification WHERE respond_uid = '$user_ids' AND (type = 'like' OR type = 'comment')");
$number_rows = $select_num_rows -> num_rows;

if($number_rows != 0){
$result = $db_conn ->query($sql1);
while($row = $result->fetch_object()){
$u_id           = escape($row->user_id);
$post_id        = escape($row->post_id);
$resp_id        = escape($row->respond_uid);
$date_notif     = escape($row->date);
$notif_type     = escape($row->type);
$subject        = escape($row->title);
$comment_status = escape($row->status);
$user_name      = escape($row->user_name);
$profile_pic    = escape($row->profile_pic);
date_default_timezone_set('America/Los_Angeles'); // Very important


if($profile_pic == "default_pic.png"){
$update_profile_pic = $back."images/default_pic.png";
}
else {
$update_profile_pic = $back."user_data/profile_pic/". $profile_pic;
}


if($comment_status == 'seen' && $u_id != $resp_id && ($notif_type == 'comment' ||  $notif_type == 'like')){
?>
<div id = "covernotifmsg"  style = "background: #ffffff;" onclick="location.href ='<?php echo $link; ?>';">
<div id = "wrapnotifmsg">
<img src = '<?php echo $update_profile_pic; ?>' alt = "userimg" width= "45" height= "45" style = "border: 2px solid #eee; margin-left: 2px; margin-top: 3px; object-fit: cover;" />

<?php
if($notif_type == 'comment'){
?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"><b> <?php echo ucfirst($user_name); ?>  </b> commented on your post. <br>
<?php
}

if($notif_type == 'like'){
?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"><b> <?php echo ucfirst($user_name); ?>  </b> liked your comment. <br>
<?php
}
?>

<span style = "font-size: 13px; margin-left: 0px;color: gray">  <?php echo time_ago($date_notif); ?> </span> &nbsp;&nbsp;<span style = "font-size: 13px;"> <?php echo ucfirst($subject); ?></span>
<span style = "float: right; border 1px solid gray;font-size: 9px; margin-right: 10px;"> &#9898; </span>
</a>
</div>
</div>
<?php
}


if($comment_status == 'unseen' && $u_id != $resp_id && ($notif_type == 'comment' ||  $notif_type == 'like')){
?>

<div id = "covernotifmsg" style = "background:#EDF2FA;" onclick="location.href ='<?php echo $link; ?>';">
<div id = "wrapnotifmsg">
<img src = '<?php echo $update_profile_pic; ?>' alt = "userimg" width= "35" height= "35" style = "border: 2px solid #eee; margin-top: 7px; margin-left: 10px; object-fit: cover;" />

<?php
if($notif_type == 'comment'){
?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"><b> <?php echo ucfirst($user_name);?>  </b> commented on your post. <br>
<?php
}

if($notif_type == 'like'){
?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"><b> <?php echo ucfirst($user_name);?>  </b> liked your comment. <br>
<?php
}
?>

<span style = "font-size: 13px; margin-left: 0px;color: gray"> <?php echo time_ago($date_notif); ?></span>&nbsp;&nbsp;<span style = "font-size: 13px;"> <?php echo ucfirst($subject); ?></span>
<span style = "float: right; border 1px solid gray;font-size: 9px; margin-right: 10px;"> &#9899; </span>
</a>
</div>
</div>

<?php
}

}
} else  {
?> <br/> <br/>
<h2 style = "text-align: center; color: lightgray; margin-left:40px;font-size: 28px;"> <b> No Notification </b></h2>
<?php
}

$db_conn->close();
}



if(isset($_POST['notif_privatequery'])) {
$back = $_POST['back'];

/***************************************************************
**************** NOTIFICATION FOR ALL PRIVATE QUERIES **********************
*****************************************************************/
$select_num_rows = $db_conn -> query("SELECT id FROM likecomm_notification WHERE respond_uid = '$user_ids' AND (type = 'prique' OR type = 'pricomm' OR type = 'resComm')");
$number_rows = $select_num_rows -> num_rows;

if($number_rows != 0){
$result = $db_conn ->query($sql1);
while($row = $result->fetch_object()){
$u_id           = escape($row->user_id);
$post_id        = escape($row->post_id);
$resp_id        = escape($row->respond_uid);
$date_notif     = escape($row->date);
$notif_type     = escape($row->type);
$subject        = escape($row->title);
$query_status   = escape($row->status);
$user_name      = escape($row->user_name);
$profile_pic    = escape($row->profile_pic);
date_default_timezone_set('America/Los_Angeles'); // Very important


if($profile_pic == "default_pic.png"){
$update_profile_pic = $back."images/default_pic.png";
}
else {
$update_profile_pic = $back."user_data/profile_pic/". $profile_pic;
}

if($query_status == 'seen' && $u_id != $resp_id && ($notif_type == 'prique' || $notif_type == 'pricomm' || $notif_type == 'resComm')){
?>
<div id = "covernotifmsg"  style = "background: #ffffff;" onclick="location.href ='<?php echo $link; ?>';">
<div id = "wrapnotifmsg">
<img src = '<?php echo $update_profile_pic; ?>' alt = "userimg" width= "45" height= "45" style = "border: 2px solid #eee; margin-left: 2px; margin-top: 3px; object-fit: cover;"/>


<?php if ($notif_type == 'pricomm') { ?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"> <b> <?php echo ucfirst($user_name); ?> </b> responded to your question.</br>
<?php } ?>

<?php if($notif_type == 'prique') { ?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"> <b> <?php echo ucfirst($user_name); ?> </b> asked you a question. </br>
<?php } ?>

<?php if($notif_type == 'resComm') { ?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"> <b> <?php echo ucfirst($user_name); ?> </b> resolved the question that you responded on. </br>
<?php } ?>

<span style = "font-size: 13px; margin-left: 0px;color: gray">  <?php echo time_ago($date_notif); ?> </span> &nbsp;&nbsp;<span style = "font-size: 13px;"> <?php echo ucfirst($subject); ?></span>
<span style = "float: right; border 1px solid gray;font-size: 9px; margin-right: 10px;"> &#9898; </span>
</a>
</div>
</div>

<?php
}

if($query_status == 'unseen' && $u_id != $resp_id && ($notif_type == 'prique' || $notif_type == 'pricomm' || $notif_type == 'resComm')){
?>
<div id = "covernotifmsg" style = "background:#EDF2FA;" onclick="location.href ='<?php echo $link; ?>';">
<div id = "wrapnotifmsg">
<img src = '<?php echo $update_profile_pic; ?>' alt = "userimg" width= "35" height= "35" style = "border: 2px solid #eee; margin-top: 7px; margin-left: 10px; object-fit: cover;"/>

<?php if ($notif_type == 'pricomm') { ?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"> <b> <?php echo ucfirst($user_name); ?> </b> responded to your question. </br>
<?php } ?>

<?php if($notif_type == 'prique') { ?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"> <b> <?php echo ucfirst($user_name); ?> </b> asked you a question. </br>
<?php } ?>


<?php if($notif_type == 'resComm') { ?>
<a id = "notifmsg" href = "<?php echo $back;?>class/anspage.php?p_id=<?php echo $post_id?>"> <b> <?php echo ucfirst($user_name); ?> </b> resolved the question that you responded on. </br>
<?php } ?>

<span style = "font-size: 13px; margin-left: 0px;color: gray"> <?php echo time_ago($date_notif); ?></span>&nbsp;&nbsp;<span style = "font-size: 13px;"> <?php echo ucfirst($subject); ?></span>
<span style = "float: right; border 1px solid gray;font-size: 9px; margin-right: 10px;"> &#9899; </span>
</a>
</div>
</div>
<?php
}

}
}
else {
?> <br/> <br/>
<h2 style = "text-align: center; color: lightgray; margin-left:40px;font-size: 28px;"> <b> No Notification </b></h2>
<?php
}
}


?>
