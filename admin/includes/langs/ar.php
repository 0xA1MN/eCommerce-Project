<?php 

  function lang($phrase) {
    static $lang = array (
      "message" => "مرحبا",
      "admin" => "مشرف"
    );
    return $lang[$phrase];
  }


/*
  $lang = array(
    "mo" => "ayman"
  );
  echo $lang["mo"];
*/



?>