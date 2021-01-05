<?php

  // DB Connection
  include "connect.php";

  // Routes
  $tpl = "includes/templates/"; // template dir
  $css = "layout/css/";
  $js = "layout/js/"; 
  $lang = "includes/langs/";
  $func = "includes/functions/";



  // include important file 
  include $lang ."en.php";

  include $func . "functions.php";

  include $tpl . "header.php";
  if (!isset($noNavbar)) { include $tpl . "navbar.php"; }
  // include navbar on all page except 
  // the one have $noNavbar variable


?>