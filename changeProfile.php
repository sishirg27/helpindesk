
<?php
  session_start();

  // IF and SWITCH to check ajax sending functions

  $testObject = new editSett();

  if (isset($_GET['key'])) {
    switch ($_GET['key']) {

      case '#a':
      $testObject->changeFullName();
      break;

      case '#b':
      $testObject->changeUserName();
      break;

      case '#c':
      $testObject->changePassword();
      break;

      case '#d':
      $testObject->changeEmail();
      break;


      default:
      header('Location: home.php');
      exit;
      break;
    }
  }




class editSett{

public function changeFullName() {

  include_once("ext_functions/security.php");
  include_once("ext_functions/connect.php");
  include_once("ext_functions/select_user.php");


  $currentValue = escape(strtolower($_GET['cVal']));
  $newValue = escape(strtolower($_GET['nVal']));
  $nnewValue = escape(strtolower($_GET['nnewVal']));

  if($currentValue !== '' && $newValue !== '' && $nnewValue !== ''){
    if($newValue === $nnewValue){
          $selectVal = $db_conn -> query("SELECT full_name FROM users WHERE user_id = '$user_ids' AND full_name = '$currentValue'");
          $resVal = $selectVal -> fetch_object();

         if($resVal) {

          $old_fName = escape(strtolower($resVal->full_name));

          if($currentValue === $old_fName){
             $update_fName = $db_conn -> query("UPDATE users SET full_name = '$newValue' WHERE user_id = '$user_ids'");
             if($update_fName){
              echo "Successfully Updated.";
              echo "<script> window.location.reload(); </script> ";
             }
          }

        }
        else {
          echo "The old full Name doesnot exist.";
        }
      }
    }
  }

  public function changeUserName() {
    include_once("ext_functions/security.php");
    include_once("ext_functions/connect.php");
    include_once("ext_functions/select_user.php");

    $currentValue = escape(strtolower($_GET['cVal']));
    $newValue = escape(strtolower($_GET['nVal']));
    $nnewValue = escape(strtolower($_GET['nnewVal']));

    if($currentValue !== '' && $newValue !== '' && $nnewValue !== ''){
      if($newValue === $nnewValue){
            $selectVal = $db_conn -> query("SELECT user_name FROM users WHERE user_id = '$user_ids' AND user_name = '$currentValue'");
            $resVal = $selectVal -> fetch_object();

           if($resVal) {

            $old_fName = escape(strtolower($resVal->user_name));

            if($currentValue === $old_fName){
               $update_fName = $db_conn -> query("UPDATE users SET user_name = '$newValue' WHERE user_id = '$user_ids'");
               if($update_fName){
                echo "Successfully Updated.";
                echo "<script> window.parent.location.reload(); </script> ";
                  $_SESSION['sess_user'] = $newValue;
               }
            }

          }
          else {
            echo "The old User Name doesnot exist.";
          }
        }
      }
    }


    public function changePassword() {

      include_once("ext_functions/security.php");
      include_once("ext_functions/connect.php");
      include_once("ext_functions/select_user.php");

      $currentValue = escape($_GET['cVal']);
      $newValue = escape($_GET['nVal']);
      $nnewValue = escape($_GET['nnewVal']);

      if($currentValue !== '' && $newValue !== '' && $nnewValue !== ''){
        if($newValue === $nnewValue){
              $selectVal = $db_conn -> query("SELECT password FROM users WHERE user_id = '$user_ids'");
              $resVal = $selectVal -> fetch_object();

             if($resVal) {
              $old_fName = escape($resVal->password);

              if(password_verify($currentValue, $old_fName)){
                $encry_pass = password_hash($nnewValue, PASSWORD_DEFAULT); // More secure password with HASHING
              //  if($currentValue === $old_fName){
                 $update_fName = $db_conn -> query("UPDATE users SET password = '$encry_pass' WHERE user_id = '$user_ids'");
                 if($update_fName){
                  echo "Successfully Updated.";
                  echo "<script> window.parent.location.reload(); </script> ";
                 }
              //}
            }
            else {
              echo "The old Password doesnot exist.";
            }
          }
            else {
              echo "The old Password doesnot exist.";
            }
          }
        }
      }



      public function changeEmail() {

        include_once("ext_functions/security.php");
        include_once("ext_functions/connect.php");
        include_once("ext_functions/select_user.php");

        $currentValue = escape(strtolower($_GET['cVal']), FILTER_SANITIZE_EMAIL);
        $newValue = escape(strtolower($_GET['nVal']), FILTER_SANITIZE_EMAIL);
        $nnewValue = escape(strtolower($_GET['nnewVal']), FILTER_SANITIZE_EMAIL);

        if($currentValue !== '' && $newValue !== '' && $nnewValue !== ''){
          if($newValue === $nnewValue){
                $selectVal = $db_conn -> query("SELECT email FROM users WHERE user_id = '$user_ids' AND email = '$currentValue'");
                $resVal = $selectVal -> fetch_object();

               if($resVal) {
                $old_fName = escape($resVal->email);

                  if($currentValue === $old_fName){
                   $update_fName = $db_conn -> query("UPDATE users SET email = '$newValue' WHERE user_id = '$user_ids'");
                   if($update_fName){
                    echo "Successfully Updated.";
                    echo "<script> window.parent.location.reload(); </script> ";
                   }
                }
              }
            }
              else {
                echo "The old Password doesnot exist.";
              }
            }
          }

}

?>
