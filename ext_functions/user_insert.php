<?php

require_once "connect.php";
require_once "security.php";
require_once "datafilter.php";

if(isset($_POST['insert_user'])){
if(isset($_POST['fName'], $_POST['uName'], $_POST['uPass'], $_POST['uEmail'])) { // waiting for the submit button to be clicked i.e. send message to the server

  $color = randomColor();
  $filter_flname = escape(strtolower($_POST['fName']));
  $filter_user  = escape(strtolower($_POST['uName']));
  $filter_pass  = escape($_POST['uPass']);
  $filter_email = escape(strtolower($_POST['uEmail']), FILTER_VALIDATE_EMAIL);
	$filter_userpic  = escape("default_pic.png");
  $filter_hash     = '';
	$filter_color    = escape($color);

	if(!empty($filter_flname) && !empty($filter_user) && !empty($filter_pass) && !empty($filter_email) && !empty($filter_userpic) && !empty($filter_color))  { // checks weather the textboxes are empty or not

		$uChecker = $db_conn -> query("SELECT user_name, email FROM users WHERE user_name = '$filter_user' OR email = '$filter_email'");

    $checkRepeat = $uChecker -> fetch_object();

    $alreadyEmail = $checkRepeat->email;
    $alreadyUsername = escape($checkRepeat->user_name);

		if($uChecker -> num_rows == 0) {

			if(!filter_var($filter_email, FILTER_VALIDATE_EMAIL)) {
				echo "<script> alert('Invalid email format') </script>";
			}


			else {
				$encry_pass = password_hash($filter_pass, PASSWORD_DEFAULT); // More secure password with HASHING
				$insert_user = $db_conn -> prepare("INSERT INTO users(full_name,user_name,password,	recoveryemail_enc,email, profile_pic,color,date)  VALUES (?, ?, ?, ?, ?, ?, ?,NOW())");	 // question mark (?) substitutes in an integer, string, double or blob value

				$insert_user -> bind_param("sssssss",$filter_flname, $filter_user,$encry_pass,$filter_hash,$filter_email,$filter_userpic,$filter_color); // The 'sss' argument tells that there are string parameters

				if($insert_user -> execute()) {
          	echo "<script> alert ('Account Successfully Created.') </script> ";
            echo("<script>window.location = 'login.php';</script>");
					exit();
				}

				else {
					echo "<script> alert ('Error occoured while creating account.') </script> ";
				}

				$insert_user -> close();
				$db_conn     -> close();

			}
		}
		else {
      if($filter_email == $alreadyEmail)
      	echo "<script> alert ('Registration failed. Please enter a different Email Address.') </script> ";

      else if($filter_user == $alreadyUsername)
      echo "<script> alert ('Registration failed. Please enter a different User Name.') </script> ";
		}
	}
	else {
		echo "<script> alert('Please Fill in all the information.') </script> ";
	}
 }
}

function randomColor() {
    $str = '#';
    for($i = 0 ; $i < 3 ; $i++) {
        $str .= dechex( rand(170 , 255) );
    }
    return $str;
}

?>
