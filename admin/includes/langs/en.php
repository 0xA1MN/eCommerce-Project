<?php 
  function lang($phrase) {
    static $lang = array (
      // navbar links
      "HOME"          => "Home",
      "ITEMS"         => "Items",
      "MEMBERS"       => "Members",
      "STATISTICS"    => "Statistics",
      "LOGS"          => "Logs",
      "CATEGORY"      => "Category",
      "COMMENTS"      => "Comments"
      
    );

    return $lang[$phrase]; //input equivalent value from array
  }
?>