
<?php

function user_profile($no_profilepic, $yesprofile_pic){
  include "connect.php";
  include "select_user.php";

  global $profile_pic;

  // Check whether the user has posted a profile or not
  $check_pic = $db_conn -> query("SELECT profile_pic FROM users WHERE user_name = '$user' AND user_id = '$user_ids'");

  $get_pic_row = $check_pic -> fetch_assoc();
  $profile_pic_db = $get_pic_row['profile_pic'];

  if($profile_pic_db == "default_pic.png"){
    $profile_pic = $no_profilepic;

  }
  else {
    $profile_pic = $yesprofile_pic .$profile_pic_db;
  }


  if(isset($_FILES['file'])){

    $target_file = basename($_FILES["file"]["name"]); // the tyoe and name  of the image
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); // checking the file type and .extension of an image

    // create a random folder for every user profile picture
    $chars = "abcdefghijklmnopqrstuvwxyaABCDEFGHIJKLMNOPQURSTUVWXYZ123456789";
    $ran_dir_name = substr(str_shuffle($chars), 0 , 15); // str_suffle shuffles the chars

    // Check if image file is a actual image or fake image
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $check = getimagesize($_FILES["file"]["tmp_name"]); // php gives a tmp_name automatically
      if($check !== false) {
        //  echo "<script> alert('File is an image - " . $check["mime"] . ".') </script>";
        $uploadOk = 1;
        mkdir("user_data/profile_pic/$ran_dir_name");
      }
      else {
        $uploadOk = 0;
        header('location: home.php');
      }
    }

    $file_store_folder = "user_data/profile_pic/$ran_dir_name"; // storing the random name in a variable

    // Check if file already exists
    if (file_exists($file_store_folder.$target_file)) {
      //echo "<script> alert('Sorry, file already exists.') </script>";
      $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["file"]["size"] > 4048576) {
      //echo "<script> alert('Sorry, your file is too large.') </script> ";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      //  echo "<script> alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.') </script> ";
      $uploadOk = 0;
    }


    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      //  echo "<script> alert('Sorry, your file was not uploaded.') </script> ";

      // if everything is ok, try to upload file
    }
    else if($uploadOk == 1){
      $file_path = $_FILES["file"]["tmp_name"];

      if (move_uploaded_file($file_path, $file_store_folder."/".$target_file)) { // moving the picture to a certain folder
        //  echo "The file  has been uploaded in and as: $file_store_folder/$target_file";

        echo "<script> alert('DONE') </script>";
        $profile_pic_name = $target_file;

        // insert the file path of the user profile picture in the database
        $profile_pic_query = $db_conn -> query("UPDATE users SET profile_pic ='$ran_dir_name/$profile_pic_name' WHERE user_name = '$user' && user_id = '$user_ids'");


        header('Location: home.php');


      } else {
        //  echo "<script> alert('Sorry, there was an error uploading your file.') </script>";
      }
    }
  }
}



?>
