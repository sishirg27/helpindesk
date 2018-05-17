<?php

   function securify($string){
    $string = strip_tags($string, '<p><i><strong><em><ul><u><a><ol><li><img>');
    $string = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $string);
    $string = preg_replace('/(?:on(blur|c(hange|lick)|dblclick|focus|keypress|(key|mouse)(down|up)|(un)?load|mouse(move|o(ut|ver))|reset|s(elect|ubmit)))/', "", $string);
    $string = preg_replace( '/href\s*=\s*\'javascript:[^i]+\'/i' , 'href=\'#\'' , $string);
    return $string;
   }
?>
