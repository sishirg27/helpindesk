<?php

include ("../../ext_functions/connect.php");
include ("../../ext_functions/security.php");
include ("../../ext_functions/prevent.php");

$key= mysqli_real_escape_string($db_conn,$_GET['key']);
$array = array();

$search_query= $db_conn -> query("SELECT question FROM cau_questions WHERE question LIKE '%{$key}%' AND type = 0");

while($row= $search_query -> fetch_assoc())
{
     $anss  = html_entity_decode($row['question'], ENT_COMPAT, 'UTF-8');
     $ans = preg_replace("/\r\n|\r|\n/",'<br/>',$anss); // to remove \n issue
     $ans = preg_replace( "~\x{00a0}~siu", " ", $ans );
     $ans = preg_replace("/&nbsp;/",'',$ans);  // to remove &nbsp issue

     $array[] = strip_tags($ans, "");
}

 echo json_encode($array);
?>
