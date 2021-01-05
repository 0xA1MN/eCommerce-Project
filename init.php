<?php

  // Error Reporting
  ini_set("display_errors", "On");
  error_reporting(E_ALL);
  
  // session User
  $sessionUser = '';
  if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
  }

  // DB Connection
  include "admin/connect.php";

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


?>