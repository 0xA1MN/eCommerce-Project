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

  // Get Categories Function 
  // Function to get AllCategories from DB
  function getCat() {
    global $con;
    $getCat = $con -> prepare("SELECT * FROM categories ORDER BY ID ASC");
    $getCat -> execute();
    $cats = $getCat -> fetchAll();
    if (!empty($cats)) {
      return $cats;
    } else {
      echo '<div class="p-2 text-center">No Records to Show</div>';
    }
  }

  // Get Items Function 
  // Function to get Categories from DB
  function getItems($where, $value, $approve = NULL) {
    global $con;
    if ($approve == NULL) {
      $sql = "AND Approve = 1";
    } else {
      $sql = NULL;
    }
    $getItems = $con -> prepare("SELECT * FROM items WHERE $where = ?  $sql ORDER BY item_ID DESC");
    $getItems -> execute(array($value));
    $items = $getItems -> fetchAll();
    if (!empty($items)) {
      return $items;
    } else {
      echo '<div class="p-3 mx-auto">No Records to Show</div>';
    }
  }

  // Get All Function 
  // Function to get all table content from DB
  /**
   * Add $approve = 1 in case of print approved Items
   */
  function getAll($from, $approve = NULL) {
    global $con;
    if ($approve == 1) {
      $sql = "WHERE Approve = 1";
    } else {
      $sql = "";
    }
    $getAll = $con -> prepare("SELECT * FROM $from $sql");
    $getAll -> execute();
    $all = $getAll -> fetchAll();
    if (!empty($all)) {
      return $all;
    } else {
      echo '<div class="p-3 mx-auto">No Records to Show</div>';
    }
  }

  // Get Comment Function 
  // Function to get Categories from DB
  function getComment($where, $value) {
    global $con;
    $getComment = $con -> prepare(" SELECT comment.comment, comment.comment_date, users.Username FROM comment
                                    INNER JOIN
                                    users
                                    ON
                                    comment.user_id = users.UserID
                                    WHERE $where = ?
                                    ORDER BY c_id DESC
                                    ");
    $getComment -> execute(array($value));
    $comment = $getComment -> fetchAll();
    if (!empty($comment)) {
      return $comment;
    } else {
      echo '<div class="p-3 mx-auto">No Records to Show</div>';
    }
  }

  // Check if user Activated
  // Check RegStatus of user 
  function checkUserStatus($user) {
  global $con;
  $stmtX = $con->prepare(" SELECT regStatus FROM users WHERE Username = ?  AND regStatus = 1");
    $stmtX -> execute(array($user));
    $status = $stmtX->rowCount();
    return $status;
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

  // Get Avatar with username
  function getAvatar($username){
    global $con;
    $statement = $con->prepare("SELECT avatar FROM users WHERE Username = ?");
    $statement -> execute(array($username));
    $path = $statement -> fetch();
    return $path;
  };

  // check if user is a Admin
  function isAdmin($username){
    global $con;
    $admin = $con->prepare("SELECT * FROM users WHERE Username = ? AND GroupId = 1");
    $admin -> execute(array($username));
    $check = $admin -> rowCount();
    if ($check) {
      $_SESSION["Username"] = $username;
      return $check;
    }
  }

// ******************************************************
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