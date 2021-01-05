<?php 
  // Manage Members Page
  // You Can Add | Edit | Delete Members From Here
  // have Sequence

  session_start();
  $pageTitle = "Members";

  include "init.php";
  if (isset($_SESSION["Username"])) {

    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";
    
    if ( $do == "Manage") { 
    //  Start manage page
      // this code modify query to display only pending members 
      $query = "";
      if (isset($_GET["page"]) && $_GET["page"] == "Pending") {
        $query = "AND RegStatus = 0";
      }
      // Fetch Data From DB Except Admin
      $stmt = $con -> prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID Desc");
      $stmt -> execute();
      $rows = $stmt -> fetchAll()
      ?>

      <h1 class="text-center member-h1">Manage Member</h1>
      <div class="container">
        <div class="mx-auto">
          <a href="members.php?page=Pending" class="btn btn-info add-m-btn"><i class="fa fa-check"></i> Show Pending Members</a>
          <a href="?do=Add" class="btn btn-success add-m-btn"><i class="fa fa-plus"></i> Add a New Member</a>
        </div>
        <div class="table-responsive">
          <table class="text-center table table-bordered main-table">
            <tr>
              <th>#ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Full Name</th>
              <th>Registered Date</th>
              <th>Control</th>
            </tr>
            <?php 
              if (!empty($rows)){
                foreach($rows as $row) { ?>
                  <tr>
                    <td><?= $row["UserID"]?></td>
                    <td><?= $row["Username"]?></td>
                    <td><?= $row["Email"]?></td>
                    <td><?= $row["FullName"]?></td>
                    <td><?= $row["date"]?></td>
                    <td>
                      <a href="members.php?do=Edit&userid=<?= $row["UserID"]?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</a>
                      <a href="members.php?do=Delete&userid=<?= $row["UserID"]?>" class="btn btn-sm btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                      <?php if ($row["RegStatus"] == 0) {  ?>
                      <a href="members.php?do=Activate&userid=<?= $row["UserID"]?>" class="btn btn-sm btn-info"><i class="fa fa-thumbs-o-up "></i> Activate</a>
                      <?php } ?>
                    </td>
                  </tr>
                <?php
                } 
              } else {
                echo '<div class="p-2 text-center">No Records to Show</div>';
              }
            ?>
          </table>
        </div>
      </div>  
    <?php
    //  End manage page

    } elseif ($do == "Add"){ 
    //  Start Add page?>
      <h1 class="text-center member-h1">Add Member</h1>
      <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
          <!-- start username field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="username" class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10" id="username">
                <input type="text" name="username" class="form-control"  autocomplete="false" required="required" placeholder="Username to Login into Shop">
              </div>
            </div>
          </div>
          <!-- end username field -->
          <!-- start password field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="password" class="col-sm-2 control-label" autocomplete="new-password">Password</label>
              <div class="col-sm-10" id="password">
                <input type="password" name="password" class="form-control show-pass-filed" autocomplete="off" required="required" placeholder="Password Must be Complex">
                <i class="show-pass fa fa-eye"></i>
              </div>
            </div>
          </div>
          <!-- end password field -->
          <!-- start email field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="email" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10" id="email">
                <input type="email" name="email" class="form-control" required="required" placeholder="Must be Valid">
              </div>
            </div>
          </div>
          <!-- end email field -->
          <!-- start full name field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="full name" class="col-sm-2 control-label">Full Name</label>
              <div class="col-sm-10" id="full name">
                <input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile">
              </div>
            </div>
          </div>
          <!-- end full name field -->
          <!-- start avatar field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="avatar" class="col-sm-2 control-label">User Avatar</label>
              <div class="col-sm-10" id="avatar">
                <input type="file" name="avatar" required="required">
                <span>only accept : png , jpeg , gif , jpg</span>
              </div>
            </div>
          </div>
          <!-- end avatar field -->
          <!-- start button field -->
          <div class="form-group edit-members">
            <div class="row">
              <button type="submit" class="m-auto btn btn-primary">Submit</button>
            </div>
          </div>
          <!-- end button field -->
        </form>
      </div>    
    <?php
    //  End Add page
    } elseif ( $do == "Insert" ) { 
    // Start Insert Page
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo '<h1 class="text-center member-h1">Insert Member</h1>';
        echo '<div class="container">';
        // upload vars
        $avatar = $_FILES['avatar'];
        $avatarName   = $_FILES['avatar']['name'];
        $avatarTmp    = $_FILES['avatar']['tmp_name'];
        $avatarSize   = $_FILES['avatar']['size'];
        // allowed file type to Upload
        $avatarExtensions = array("png", "gif", "jpg", "jpeg");
        // get avatar extension
        $fileExtension = pathinfo($avatarName, PATHINFO_EXTENSION);
        // Get VAriables from Form
        $user       = filter_var($_POST["username"],FILTER_SANITIZE_STRING);
        $email      = filter_var($_POST["email"],FILTER_SANITIZE_EMAIL);
        $name       = filter_var($_POST["full"],FILTER_SANITIZE_STRING);
        $pass       = $_POST["password"];
        $hashPass   = sha1($_POST["password"]);
        // Validate the form
        $formError = array();
        if (strlen($user) < 4) { $formError[] = 'Username Can\'T Be Less Than <Strong>4 Characters</Strong>';}
        if (strlen($user) > 20) { $formError[] = 'Username Can\'T Be More Than <Strong>20 Characters</Strong>' ;}
        if (empty($user)) { $formError[] = 'Username Can\'T Be <Strong>Empty</Strong>';}
        if (empty($pass)) { $formError[] = 'Username Can\'T Be <Strong>Empty</Strong>';}
        if (empty($email)) { $formError[] = 'Email Can\'T Be <Strong>Empty</Strong>';}
        if (empty($name)) { $formError[] = 'Full Name Can\'T Be <Strong>Empty</Strong>';}
        if (empty($avatarName)) {$formError[] = 'Avatar is Required';}
        if (!empty($avatarName) && !in_array($fileExtension, $avatarExtensions)) {$formError[] = "Avatar Only accept : png , jpeg , gif , jpg";}
        if ($avatarSize > 4194304) {$formError[] = 'Avatar Can\'t be larger than 4Mb';}
        // loop on error array and echo it
        foreach ($formError as $error) {
          echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        
        // Check If No Error Proceed Update
        if (empty($formError)) {
          // prepare avatar
          $avatar = rand(0, 1000000) . '_' . $avatarName;
          move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
          // Insert User Info DB with this info
          $stmt = $con -> prepare("INSERT INTO 
                                    users(Username, password, Email, FullName, RegStatus, date, avatar)
                                    VALUES(?, ?, ?, ?, 1, now(), ?)");
          $stmt -> execute(array($user, $hashPass, $email, $name, $avatar));
          // echo success msg
          $theMsg = '<div class="alert alert-success" style="margin-bottom: 0" >' . $stmt -> rowCount() . ' Record Insert</div>,'; 
          redirectHome($theMsg, "members.php");          
        } else {
          // an error other than duplicate entry occurred
          $err = '<div class="alert alert-danger">Username or Email Already <strong>Exist</strong></div>';
          redirectHome($err);          
        }
      } else {
        $errorMsg = '< class="container" style="margin-top: 40px"><div class="alert alert-danger">Can\'t Browse Directly</div>';
        redirectHome($errorMsg, null, 1);
      }
      // End Insert Page
    } elseif ( $do == "Edit" ) { 
    // Start Edit page
      // check if GET request userid is numeric and get integer value of it
      $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0 ;
      // select all data depend on userid
      $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
      // execute query
      $stmt -> execute(array($userid));
      // fetch data from DB
      $row = $stmt -> fetch(); 
      // row count
      $count = $stmt->rowCount();
      // if there is userid show form
      if ($count > 0) { ?>
        <h1 class="text-center member-h1">Edit Member</h1>
        <div class="container">
          <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
           <input type="hidden" name="userid" value="<?php echo $row["UserID"] ?>">
            <!-- start username field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="username" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10" id="username">
                  <input type="text" name="username" value="<?php echo $row["Username"] ?>" class="form-control"  autocomplete="false" required="required">
                </div>
              </div>
            </div>
            <!-- end username field -->
            <!-- start password field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="password" class="col-sm-2 control-label" autocomplete="new-password">Password</label>
                <div class="col-sm-10" id="password">
                  <input type="hidden" name="old_password" value="<?php echo $row["password"]?>">
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
                  <input type="email" name="email" value="<?php echo $row["Email"] ?>" class="form-control" required="required">
                </div>
              </div>
            </div>
            <!-- end email field -->
            <!-- start full name field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="full name" class="col-sm-2 control-label">Full Name</label>
                <div class="col-sm-10" id="full name">
                  <input type="text" name="full" value="<?php echo $row["FullName"] ?>" class="form-control" required="required">
                </div>
              </div>
            </div>
            <!-- end full name field -->
            <!-- start avatar field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="full name" class="col-sm-2 control-label">User Avatar</label>
                <div class="col-sm-10" id="full name">
                  <input type="file" name="avatar" required="required">
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
      // if there is userid show form
      } else {echo "NO ID";} 
      echo '</div>'; // Container Close Of Edit page
    // End Edit page
    } elseif ($do == "Update") {
    // Start Update Page
      // print_r($_POST); //if u wanna to se post request content 
      echo '<h1 class="text-center member-h1">Update Member</h1>';
      echo '<div class="container">';
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // upload vars
        $avatar = $_FILES['avatar'];
        $avatarName   = $_FILES['avatar']['name'];
        $avatarTmp    = $_FILES['avatar']['tmp_name'];
        $avatarSize   = $_FILES['avatar']['size'];
        // allowed file type to Upload
        $avatarExtensions = array("png", "gif", "jpg", "jpeg");
        // get avatar extension
        $fileExtension = pathinfo($avatarName, PATHINFO_EXTENSION);
        // Get VAriables from Form
        $id     = $_POST["userid"];
        $user   = $_POST["username"];
        $email  = $_POST["email"];
        $name     = $_POST["full"];
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
        if (empty($avatarName)) {$formError[] = 'Avatar is Required';}
        if (!empty($avatarName) && !in_array($fileExtension, $avatarExtensions)) {$formError[] = "Avatar Only accept : png , jpeg , gif , jpg";}
        if ($avatarSize > 4194304) {$formError[] = 'Avatar Can\'t be larger than 4Mb';}
        // loop on error array and echo it
        foreach ($formError as $error) {echo '<div class="alert alert-danger">' . $error . '</div>';}
        // Check If No Error Proceed Update
        if (empty($formError)) {
          // prepare avatar
          $avatar = rand(0, 1000000) . '_' . $avatarName;
          move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
          // check if Username used before Update
          $stmt2 = $con -> prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
          $stmt2 -> execute(array($user, $id));
          $count = $stmt2 -> rowCount();
          if ($count == 1) {
            $theMsg = '<div class="alert alert-danger">Sorry, This Username <strong>Already Used</strong></div>';
            redirectHome($theMsg, "back");
          } else {
            // Update DB with this info
              $stmt = $con -> prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, password = ?, avatar = ? WHERE UserID = ?");
              $stmt -> execute(array($user, $email, $name, $pass, $avatar, $id));
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
    // End Update Page
    } elseif ($do == "Delete") {
    // Start Delete Page
      echo '<h1 class="text-center member-h1">Delete Member</h1>';
      echo '<div class="container">';
        $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0 ;
        // select all data depend on userid
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        // execute query
        $stmt -> execute(array($userid));
        // if there is Record
        if ($stmt->rowCount() > 0) {
          $stmt = $con -> prepare("DELETE FROM users WHERE UserID = ?");
          $stmt -> execute(array($userid));
          $theMsg = '<div class="alert alert-success">'.$stmt->rowCount() .' Record Deleted</div>';
          redirectHome($theMsg, "back");
        } else {
          $theMsg = '<div class="alert alert-danger"><strong>ID NOT EXIST</strong></div>';
          redirectHome($theMsg);
        }
      echo '</div>';
    // End Delete Page
    } elseif ($do == "Activate") {
      // Start Activate Page
        echo '<h1 class="text-center member-h1">Activate Member</h1>';
        echo '<div class="container">';
          $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0 ;
          // select all data depend on userid
          $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
          // execute query
          $stmt -> execute(array($userid));
          // if there is Record
          if ($stmt->rowCount() > 0) {
            $stmt = $con -> prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt -> execute(array($userid));
            $theMsg = '<div class="alert alert-success">User Activated</div>';
            redirectHome($theMsg, "back");
          } else {
            $theMsg = '<div class="alert alert-danger"><strong>ID NOT EXIST</strong></div>';
            redirectHome($theMsg);
          }
        echo '</div>';
      // End Activate Page
    }
  


  
    include $tpl . "footer.php";  
  } else { // No Session Username
    // echo "U ARE NOT AUTHORIZED TO VIEW THIS PAGE :)";
    header("location: index.php");
    exit();
  }
?>