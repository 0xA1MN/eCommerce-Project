<?php 

  // Title function
  function getTitle() {
    global $pageTitle;
    if (isset($pageTitle)) {
      echo $pageTitle;
    } else {
      echo "DEFAULT";
    }
  }

  // Home Redirect function
  function redirectHome($Msg, $url = null, $sec = 3) {
    if ($url === null) {
      $url = "index.php";
    } elseif ($url === "members.php") {
      $url = "members.php";
    } elseif ($url === "category.php") {
      $url = "category.php";
    } elseif ($url === "items.php") {
      $url = "items.php";
    } else {
      if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "") {
        $url = $_SERVER['HTTP_REFERER'];
      } else {
        $url = "index.php";
      }
    }
    echo $Msg;
    echo '<div class="container alert alert-info">You will redirected after '.$sec.' Second</div></div>';
    header("refresh:$sec; url=$url");
    exit();
  }

  // Function To Check Items in DB 
  // SELECT - FROM - WHERE
  function checkItem($select, $from, $value){
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement -> execute(array($value));
    $count = $statement -> rowCount();
    return $count;
  };

  // Check Numbers Of Items
  // SELECT COUNT() - FROM 
  function countItems($item, $table){
    global $con;
    $stmt2 = $con -> prepare("SELECT COUNT($item) FROM $table");
    $stmt2 -> execute();
    return $stmt2 -> fetchColumn();
  }

  // Get Latest (DESC) Record In DB
  // SElECT - FROM - ORDER - LIMIT
  function getLatest($select, $from, $order, $limit = 5) {
    global $con;
    $getStmt = $con -> prepare("SELECT $select FROM $from ORDER BY $order DESC LIMIT $limit");
    $getStmt -> execute();
    $rows = $getStmt -> fetchAll();
    return $rows;
  }
?>