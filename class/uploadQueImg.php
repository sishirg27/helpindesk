<?php

function compressImg($imageFileType,$imgLoc, $imgqua, $phototmp){
  if($imageFileType == 'png' || $imageFileType == 'PNG' ||
  $imageFileType == 'jpg' || $imageFileType == 'JPG' ||
  $imageFileType == 'jpeg' || $imageFileType == 'JPEG') {

    if($imageFileType == 'jpg' || $imageFileType == 'jpeg' || $imageFileType == 'JPG' || $imageFileType == 'JPEG'){
      $src = imagecreatefromjpeg($phototmp);
    }
    if($imageFileType == 'png' || $imageFileType == 'PNG'){
      $src = imagecreatefrompng($phototmp);
    }

    list($width_min, $height_min) = getImageSize($phototmp); // fetching the orifinal image width and height_min
    $newwidth_min = 500; // set compression image width

    $newheight_min = ($height_min / $width_min) * $newwidth_min; // equation forcompressed image height
    $tmp_min = imagecreatetruecolor($newwidth_min, $newheight_min); // create frame from compress image

    imagecopyresampled($tmp_min, $src, 0,0,0,0, $newwidth_min, $newheight_min, $width_min, $height_min); // compressing image
    imagejpeg($tmp_min,$imgLoc,$imgqua); // copy image in folder

  }
}

function insert_que($user_ids, $cate_id, $sub_id){
  require("../ext_functions/connect.php");

  if (empty($_FILES["fileToUpload"] ["name"])) {
    $img_name = "";
  }
  else if(isset($_FILES['fileToUpload'])){
    $post_photo = $_FILES["fileToUpload"] ["name"];
    $post_photo_tmp = $_FILES["fileToUpload"] ["tmp_name"];

    $imageFileType = pathinfo($post_photo, PATHINFO_EXTENSION);

    //  $target_file = $target_dir.basename($_FILES["fileToUpload"] ["name"]);
    $uploadOk = 1;

    // check if the image file is a actual image or fake image

    $temp = explode(".", $post_photo);
    $newfilename = round(microtime(true)).'.'.end($temp); // creates a random number based on current time and append it to the selected image
    $img_name = $newfilename;
    $imgLoc =  "../uploadImgs/".$newfilename;
    $imgqua = 100;

    compressImg($imageFileType,$imgLoc, $imgqua, $post_photo_tmp); // calling a function to compress
}
    $query   = escape($_POST['textUp']);
    $question_uid = 0;
    $type         = 0;
    $resolve_id   = 0;
    $uploadImg    = $img_name;


    if(!empty($user_ids)){
      $insert_question = $db_conn -> prepare("INSERT INTO cau_questions(cate_id, sub_id, user_id, question_uid, question, uploadimg, type, resolve, date)
      VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())");
      $insert_question -> bind_param('iiiissii', $cate_id, $sub_id, $user_ids, $question_uid, $query, $uploadImg, $type, $resolve_id);
      $insert_question -> execute();
      $insert_question -> close();

      echo("<script>window.location = 'classque.php?c_id=$cate_id&s_id=$sub_id';</script>");
      exit();
    }
}

function insert_que_pri($user_ids, $cate_id, $sub_id){
  require("../ext_functions/connect.php");


  if (empty($_FILES["fileToUpload_Sec"] ["name"])) {
    $img_name_Sec = "";
  }
  else if(isset($_FILES['fileToUpload_Sec'])){
    $post_photo = $_FILES["fileToUpload_Sec"] ["name"];
    $post_photo_tmp = $_FILES["fileToUpload_Sec"] ["tmp_name"];
    $imageFileType = pathinfo($post_photo, PATHINFO_EXTENSION);

    //  $target_file = $target_dir.basename($_FILES["fileToUpload"] ["name"]);
    $uploadOk = 1;

    // check if the image file is a actual image or fake image

    $temp = explode(".", $post_photo);
    $newfilename = round(microtime(true)).'.'.end($temp); // creates a random number based on current time and append it to the selected image
    $img_name_Sec = $newfilename;

    $imgLoc =  "../uploadImgs/asksomeimg/".$newfilename;
    $imgqua = 100;

    compressImg($imageFileType,$imgLoc, $imgqua, $post_photo_tmp); // calling a function to compress
  }

  $send_question[] = $_POST['secret_select']; // array because we are passing question to more than one user
  $query         =  escape($_POST['editor1']);
  $user_id       = $user_ids;
  $cate_id       = $cate_id;
  $sub_id        = $sub_id;
  $type_que = 1;
  $comment_id = 0;
  $resolve_id = 0;
  $status = 'unseen';
  $type_notif = 'prique';
  $uploadImg = $img_name_Sec;

if($user_id){
//  if(!empty($send_question) && !empty($query)) {
    if (is_array($send_question) || is_object($send_question)){
    foreach ($send_question as $key => $value) {
      $filter_value = mysqli_escape_string($db_conn, $value);

      // inserting the query
      if($user_id != $filter_value ){
        $insert_post = $db_conn -> prepare("INSERT INTO cau_questions(cate_id, sub_id, user_id, question_uid, question, uploadImg, type, resolve, date)
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $insert_post -> bind_param("iiiissii", $cate_id, $sub_id, $user_id, $filter_value, $query, $uploadImg,  $type_que, $resolve_id);
        $insert_post -> execute();
        $insert_post -> close();

        // To get the post_id
        $get_postid = $db_conn -> query("SELECT id FROM cau_questions ORDER BY id DESC LIMIT 1");
        $row_result_commid = $get_postid-> fetch_object();
        $post_id = $row_result_commid->id;

       // insertin the qyery into notification
        $insert_post_private = $db_conn -> prepare("INSERT INTO likecomm_notification(post_id, comm_id, user_id, respond_uid, status, type, date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $insert_post_private -> bind_param('iiiiss', $post_id, $comment_id, $user_id, $filter_value, $status, $type_notif);
        $insert_post_private -> execute();
        $insert_post_private -> close();


              echo("<script>window.location = 'classque.php?c_id=$cate_id&s_id=$sub_id';</script>");
              exit();

      }

    }
  }
//}
}


}

function uploadImgComm($post_id, $type_id) {
  require("../ext_functions/connect.php");
  require("../ext_functions/select_user.php");

    if (empty($_FILES["fileToUpload"] ["name"])) {
      $img_name = "";
    }

    else if(isset($_FILES['fileToUpload'])){
      $post_photo = $_FILES["fileToUpload"] ["name"];
      $post_photo_tmp = $_FILES["fileToUpload"] ["tmp_name"];

      $imageFileType = pathinfo($post_photo, PATHINFO_EXTENSION);

      //  $target_file = $target_dir.basename($_FILES["fileToUpload"] ["name"]);
      $uploadOk = 1;

      // check if the image file is a actual image or fake image

      $temp = explode(".", $post_photo);
      $newfilename = round(microtime(true)).'.'.end($temp); // creates a random number based on current time and append it to the selected image
      $img_name = $newfilename;
      $imgLoc =  "../uploadImgs/commentimgs/".$newfilename;
      $imgqua = 100;

      compressImg($imageFileType,$imgLoc, $imgqua, $post_photo_tmp); // calling a function to compress
    }

    if($_SESSION['sess_user']){
      $select_query = $db_conn -> query("SELECT user_id,question_uid,id FROM cau_questions WHERE id = $post_id");
      $row_post = $select_query -> fetch_object();

      $u_id = $user_ids; // session user
      $comment =  escape($_POST['editor']);
      $user_id  = escape($row_post->user_id); // This is the user who ask the question
      $ques_uid = escape($row_post->question_uid);
      $post_id  = escape($row_post->id);
      $uploadimg_comm    = $img_name;
      $totallike = 0;
      $status = "unseen";

      $insert_comm = $db_conn -> prepare("INSERT INTO cau_comments(post_id, user_id, question_uid,comment, uploadimg_comm, likes, date)
      VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $insert_comm -> bind_param("iiissi", $post_id,  $u_id, $user_id, $comment, $uploadimg_comm, $totallike) ; // user_ids here is the user who write the comment
      $insert_comm -> execute();
      $insert_comm -> close();

      // to get the comment_id
      $selectcommid = $db_conn -> query("SELECT comm_id FROM cau_comments ORDER BY comm_id DESC LIMIT 1");
      $row_result_commid = $selectcommid-> fetch_object();
      $comment_id = $row_result_commid->comm_id;
      $selectcommid->close();

      // Making sure if the post comment is in a private post or public post
      if($type_id == 1){
        $type = 'pricomm';
      }

      if($type_id == 0){
        $type = 'comment';
      }

      if($u_id == $user_id){ // A very important if statememtn which lets the private question to be commented by same person
        $user_id = $ques_uid;
      }

      $insert_notif_comm = $db_conn -> prepare("INSERT INTO likecomm_notification(post_id, comm_id, user_id, respond_uid,status,type,date)
      VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $insert_notif_comm -> bind_param('iiiiss', $post_id, $comment_id, $u_id, $user_id, $status, $type);
      $insert_notif_comm -> execute();
      $insert_notif_comm -> close();

      echo("<script>window.location = 'anspage.php?p_id=$post_id';</script>");
      exit();

    }
  }



?>
