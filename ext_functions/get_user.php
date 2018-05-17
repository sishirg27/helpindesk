<?php
function users() {
  include("connect.php");
 

  $select_alluser = $db_conn -> query("SELECT * FROM users");
  while($row_users = $select_alluser -> fetch_object()){

     $user_name = escape($row_users->user_name);
     $u_id      = escape($row_users->user_id);

     ?>

       <option value = "<?php echo $u_id;?>" > <?php echo $user_name;?></option>
     <?php
  }
  //$select_alluser -> close();
}

?>
