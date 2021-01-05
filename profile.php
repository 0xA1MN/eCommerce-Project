<?php
  ob_start();
  session_start();
  // $sessionUser comes from init.php
  $pageTitle = "Profile";
  include "init.php";
  if (isset($_SESSION["user"])) {
    $getUser = $con -> prepare("SELECT UserID, Username, password, Email, FullName, date, avatar FROM users WHERE Username = ?");
    $getUser -> execute(array($sessionUser)); // var from init
    $user = $getUser -> fetch();
    $do = isset($_GET["do"]) ? $_GET["do"] : "Display";
    // start page main structure
    if($do == "Display") {
      // Display?>
      <div class="container">
        <h1 class="text-center my-3">My Profile</h1>
        <div class="information block">
          <div class="card">
            <div class="card-header">
              My Information
            </div>
            <div class="card-body">
              <p class="card-text">
                <i class="fa fa-unlock-alt fa-fw"></i>
                <span>login Name : </span><?=ucfirst($user["Username"])?>
              </p>
              <p class="card-text">
                <i class="fa fa-envelope-o fa-fw"></i>
                <span>Email : </span><?=ucfirst($user["Email"])?>
              </p>
              <p class="card-text">
                <i class="fa fa-user fa-fw"></i>
                <span>Full Name : </span><?=ucfirst($user["FullName"])?>
              </p>
              <p class="card-text">
                <i class="fa fa-calendar fa-fw"></i>
                <span>Register Date : </span><?=ucfirst($user["date"])?>
              </p>
              <p class="card-text">
                <i class="fa fa-tags fa-fw"></i>
                <span>Favorite Category :</span>
              </p>
              <div class="float-right mx-auto">
                <a href="?do=Edit" class="btn btn-info mt-3">Edit Info</a>
                <a href="?do=Delete" class="btn btn-danger mt-3 confirm">Delete Account</a>
              </div>
            </div>
          </div>
        </div>
        <!-- start Ads Block -->
        <div class="ads block">
          <div class="card">
            <div class="card-header">
              My Ads
            </div>
            <?php
                $items = getItems("Member_ID", $user['UserID'], "all");
                if (!empty($items)) {
                  echo '<div class="card-body">';
                  echo '<div class="row">';
                  foreach ($items as $item) {?>
                    <div class="card cat-card-edit col-sm-6 col-md-4 col-lg-3 clickable" onclick="location.href='items.php?itemID=<?=$item["item_ID"]?>'">
                      <img src="https://via.placeholder.com/150" class="card-img-top" alt="...">
                      <span class="price"><?=$item["Price"]?></span>
                      <div class="card-body cat-card-body-edit">
                      <h5 class="card-title deco-none clickable"><?=$item["Name"]?></h5> 
                        <?= $item["Approve"] == 0 ? '<div class="waiting-approve">Waiting Approve</div>' : "" ?>
                        <p class="card-text"><?=$item["Description"]?></p>
                        <p class="date"><?=$item["Add_Date"]?></p>
                      </div>
                    </div>
                  <?php } 
                  echo '</div>'; // card-body close
                  echo '</div>'; // row close
                } else {
                  echo '<div class="p-3 text-center"><a href="newAd.php">Create new Ad</a></div>';
                }?>
          </div>
        </div>
        <!-- start comments block -->
        <div class="comment block">
          <div class="card">
            <div class="card-header">
              My Latest Comments
            </div>
              <?php 
              // Fetch Data From DB Except Admin
              $stmt = $con -> prepare("SELECT comment FROM Comment WHERE user_id = ?"); 
              $stmt -> execute(array($user["UserID"]));
              $comments = $stmt -> fetchAll();

              if (!empty($comments)) {
                echo '<ul class="list-group list-group-flush">';
                foreach ($comments as $comment) {
                  echo '<li class="list-group-item">' . $comment["comment"]. '</li>';
                }
                echo '</ul>';
              } else {
                echo '<div class="p-3 text-center">No Records to Show</div>';
              }
              ?>
          </div>
        </div>
      </div>
    <?php
    } elseif($_GET["do"] == "Edit") {
      // Edit ?>
      <h2 class="text-center my-3">Edit Info</h2>
      <div class="container">
        <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="UserID" value="<?=$user["UserID"]?>">
          <!-- start username field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="username" class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10" id="username">
                <input type="text" name="username" value="<?=$user["Username"]?>" class="form-control"  autocomplete="false">
              </div>
            </div>
          </div>
          <!-- end username field -->
          <!-- start password field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="password" class="col-sm-2 control-label" autocomplete="new-password">Password</label>
              <div class="col-sm-10" id="password">
                <input type="hidden" name="old_password" value="<?=$user["password"]?>">
                <input type="password" name="new_password" class="form-control" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" placeholder="Click If You Want To Change">
              </div>
            </div>
          </div>
          <!-- end password field -->
          <!-- start email field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="email" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10" id="email">
                <input type="email" name="email" value="<?=$user["Email"]?>" class="form-control">
              </div>
            </div>
          </div>
          <!-- end email field -->
          <!-- start full name field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="full name" class="col-sm-2 control-label">Full Name</label>
              <div class="col-sm-10" id="full name">
                <input type="text" name="full" value="<?=$user["FullName"]?>" class="form-control">
              </div>
            </div>
          </div>
          <!-- end full name field -->
          <!-- start avatar field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="full name" class="col-sm-2 control-label">User Avatar</label>
              <div class="col-sm-10" id="full name">
                <input type="file" name="avatar" value="<?=$user["avatar"]?>">
                <span>only accept : png , jpeg , gif , jpg</span>
              </div>
            </div>
          </div>
          <!-- end avatar field -->
          <!-- start button field -->
          <div class="form-group edit-members">
            <div class="row">
              <button type="submit" value="submit" class="m-auto btn btn-primary">Save</button>
            </div>
          </div>
          <!-- end button field -->
        </form>
      </div>    
    <?php
    } elseif($_GET["do"] == "Update") {
      // Update
      echo '<h2 class="text-center my-3">Update Member</h2>';
      echo '<div class="container">';
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // upload vars
        $avatar       = $_FILES['avatar'];
        $avatarName   = $_FILES['avatar']['name'];
        $avatarTmp    = $_FILES['avatar']['tmp_name'];
        $avatarSize   = $_FILES['avatar']['size'];
        // allowed file type to Upload
        $avatarExtensions = array("png", "gif", "jpg", "jpeg");
        // get avatar extension
        $fileExtension = pathinfo($avatarName, PATHINFO_EXTENSION);
        // Get VAriables from Form
        $userID = $_POST["UserID"];
        $user   = $_POST["username"];
        $email  = $_POST["email"];
        $name   = $_POST["full"];
        $pass = "";
        // password trick
        $pass =  empty($_POST["new_password"]) ? $_POST["old_password"] : sha1($_POST["new_password"]);
        // Validate the form
        $formError = array();
        if (strlen($user) < 4) {$formError[] = 'Username Can\'T Be Less Than <Strong>4 Characters</Strong>';}
        if (strlen($user) > 20) {$formError[] = 'Username Can\'T Be More Than <Strong>20 Characters</Strong>';}
        if (empty($user)) {$formError[] = 'Username Can\'T Be <Strong>Empty</Strong>';}
        if (empty($email)) {$formError[] = 'Email Can\'T Be <Strong>Empty</Strong>';}
        if (empty($name)) {$formError[] = 'Full Name Can\'T Be <Strong>Empty</Strong>';}
        if (!empty($avatarName)) {
          if (!empty($avatarName) && !in_array($fileExtension, $avatarExtensions)) {$formError[] = "Avatar Only accept : png , jpeg , gif , jpg";}
          if ($avatarSize > 4194304) {$formError[] = 'Avatar Can\'t be larger than 4Mb';}
        }
        // loop on error array and echo it
        foreach ($formError as $error) {echo '<div class="alert alert-danger">' . $error . '</div>';}
        // Check If No Error Proceed Update
        if (empty($formError)) {
          // prepare avatar
          $avatar = rand(0, 1000000) . '_' . $avatarName;
          move_uploaded_file($avatarTmp, "admin\uploads\avatars\\" . $avatar);
          // check if Username used before Update
          $stmt2 = $con -> prepare("SELECT * FROM users WHERE Username = ?");
          $stmt2 -> execute(array($user));
          $count = $stmt2 -> rowCount();
          if ($count == 1) {
            // Update DB with all data except Username
            $stmt = $con -> prepare("UPDATE users SET Email = ?, FullName = ?, password = ?, avatar = ? WHERE UserID = ?");
            $stmt -> execute(array($email, $name, $pass, $avatar, $userID));
            // echo success msg
            $theMsg = '<div class="alert alert-info">Username Already Used Rest Data Updated</div>,';
            redirectHome($theMsg, "back");
          } else {
            // Update DB with this info
              $stmt = $con -> prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, password = ?, avatar = ? WHERE UserID = ?");
              $stmt -> execute(array($user, $email, $name, $pass, $avatar, $userID));
              // edit session username
              $_SESSION['user'] = $user;
              // echo success msg
              $theMsg = '<div class="alert alert-success">' . $stmt -> rowCount() . ' Record Update</div>,';
              redirectHome($theMsg, "back");
          }
        };
      }    
      else {
        $theMsg = '<div class="alert alert-danger">Can\'t Browse Directly</div>';
        redirectHome($theMsg);
      }
    } elseif($_GET["do"] == "Delete") {
      // Delete
      echo '<h2 class="text-center my-3">Delete Member</h2>';
      echo '<div class="container">';
      $stmt = $con -> prepare("DELETE FROM users WHERE UserID = ?");
      $stmt -> execute(array($user['UserID']));
      session_unset();
      session_destroy();    
      echo '<div class="alert alert-success">Sorry not To see u Again</div>';
      header("refresh:5;url=login.php" );
    }
    else {header("location:profile.php");}
  } else {
    header("Location:login.php");
    exit();
  }
  include $tpl . "footer.php";
  ob_flush();
?>