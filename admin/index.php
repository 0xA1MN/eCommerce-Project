<?php
  session_start();
  $noNavbar = "";
  $pageTitle = "Login";

  if (isset($_SESSION["Username"])) {
    header("Location: dashboard.php"); // redirect to dashboard.php
  }
  
  include "init.php";
  include $tpl . "header.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hashedPass = sha1($password);

    // check if user exist in DB
    $stmt = $con->prepare("SELECT
                                UserID,Username, Password
                          FROM 
                                users
                          WHERE
                                Username = ? 
                          AND 
                                Password = ? 
                          AND
                                GroupID = 1
                          LIMIT 
                                1");
    
    $stmt -> execute(array($username, $hashedPass));
    $row = $stmt -> fetch(); // fetch data from DB 
    $count = $stmt->rowCount();
    // echo $count;
    if ($count > 0) {
      session_unset();
      $_SESSION["Username"] = $username; // Register Session Name Backend    
      $_SESSION["user"] = $username; // Register Session Name Frontend
      $_SESSION["ID"] = $row["UserID"]; // Register Session ID     
      header("Location: dashboard.php"); // redirect to dashboard.php
      // control data sent to the client or browser
      // by the Web server before some other output has been sent
      
      exit();
    } else {
      echo "no User";
    }
  }
?>
  <form class="login" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <div class="form-group">
      <h4 class="text-center mb-4">Admin Login</h4>
      <input type="username" class="form-control" name="username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="password" class="form-control" name="password" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-primary btn-block">Submit</button>
  </form>
<?php
  include $tpl . "footer.php";
?>