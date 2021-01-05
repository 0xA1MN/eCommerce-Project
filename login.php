<?php 
  session_start();
  $pageTitle = "Login";
  if (isset($_SESSION["user"])) {
    header("Location: index.php");
  }
  include "init.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // LOGIN FORM
    if (isset($_POST['login'])) {
      $user = $_POST["username"];
      $pass = $_POST["password"];
      $hashedPass = sha1($pass);
  
      // check if user exist in DB
      $stmt = $con->prepare("SELECT
                                  UserID,Username, Password
                            FROM 
                                  users
                            WHERE
                                  Username = ? 
                            AND 
                                  Password = ?");
      
      $stmt -> execute(array($user, $hashedPass));
      $get = $stmt -> fetch();
      $count = $stmt->rowCount();
      // echo $count;
      if ($count > 0) {
        $_SESSION["user"] = $user;              // Session Username
        $_SESSION["userId"] = $get["UserID"];   // Session UserID
        header("Location: index.php");
        exit();
      }
    } else {

      // SIGNUP FORM
      
      $formErrors = array();
      // validate username input
      if (isset($_POST['username'])) {
        $filterUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        // error handling
        strlen($filterUser) < 4 ? $formErrors[] = "Username Must be larger than 4 Characters" : 0;
      }
      // validate password input
      if (isset($_POST['password']) && isset($_POST['password2'])) {
        // error handling
        if (empty($_POST['password'])) {
          $formErrors[] = "Sorry Password Can't be Empty";
        }
        $pass = sha1($_POST['password']);
        $pass2 = sha1($_POST['password2']);
        if ($pass !== $pass2){
          $formErrors[] = "Sorry Password doesn't Match";  
        }
      }
      // validate Email input
      if (isset($_POST['email'])) {
        $filterEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        // error handling
        if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) == false) {
          $formErrors[] = "This Email isn't valid";
        }
      }
      // End validation process
      // vars >>> $filterUser $pass $filterEmail
      
      // insert data into DB
      if (empty($formErrors)) {
        $check = checkItem("Username", "Users", $filterUser);
        if ($check == 1) {
          $formErrors[] = "Sorry This User Is Exist";
        } else {
          $stmt = $con -> prepare ("INSERT INTO 
                                        users(Username, password, Email, RegStatus, Date)
                                    VALUES 
                                        (?, ?, ?, 0, now())");
          $stmt -> execute(array($filterUser, $pass, $filterEmail));
          $SuccessMsg = "You Are Now Registered User";
        }
      }
    }
  }
?>

<!-- Start Login Form -->
<div class="container login-page">
  <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h1>
  <form class="login" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <div class="form-group">
      <label for="exampleInputEmail1">Username</label>
      <input type="text" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input type="password" name="password" class="form-control" id="exampleInputPassword1">
    </div>
    <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
  </form>
<!-- End Login Form -->
<!-- Start SignUp Form -->
  <form class="signup" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <div class="form-group">
      <label for="exampleInputEmail1">Username</label>
      <input  type="text" 
              pattern=".{4,}"
              title="Username Must be More than 4 Characters"
              name="username" 
              class="form-control" 
              required = "required" 
              id="exampleInputEmail1" 
              aria-describedby="emailHelp">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Email</label>
      <input  type="email" 
              name="email" 
              class="form-control" 
              required = "required" 
              id="exampleInputEmail1" 
              aria-describedby="emailHelp">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input  type="password" 
              minlength="4"
              name="password" 
              class="form-control" 
              required = "required" 
              id="exampleInputPassword1">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Retype Password</label>
      <input  type="password" 
              minlength="4"
              name="password2" 
              class="form-control" 
              required = "required" 
              id="exampleInputPassword1">
    </div>
    <button type="submit" name="signup" class="btn btn-success btn-block">SignUp</button>
  </form>
<!-- End SignUp Form -->
  <div class="the-error text-center"> 
    <?php 
     if (!empty($formErrors)) {
       foreach($formErrors as $error){
        echo '<div class="alert alert-danger " role="alert">' . $error .'</div>';
      }
     }
     if (isset($SuccessMsg)) {
      echo '<div class="alert alert-success " role="alert">' . $SuccessMsg .'</div>';
     }
    ?>  
  </div>
</div> 

<?php
  include $tpl . "footer.php";
?>