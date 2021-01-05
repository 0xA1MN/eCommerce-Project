<?php 
  // Manage comment Page
  // You Can Add | Edit | Delete | approve comment From Here
  // have Sequence

  session_start();
  $pageTitle = "Comments";

  include "init.php";
  if (isset($_SESSION["Username"])) {

    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";
    
    if ( $do == "Manage") { 
    //  Start manage page
      // Fetch Data From DB Except Admin
      $stmt = $con -> prepare(" SELECT 
                                  comment.*, items.Name as item_name, users.Username as member
                                FROM 
                                  Comment
                                inner JOIN 
																	items
																	on
																	items.item_ID = comment.item_id
																inner JOIN 
																	users 
																	On 
                                  users.UserID = comment.user_id
                                  ORDER BY c_id Desc"); 
                                  
      $stmt -> execute();
      $rows = $stmt -> fetchAll()
      ?>

      <h1 class="text-center member-h1">Manage Comments</h1>
      <div class="container">
        <div class="table-responsive">
          <table class="text-center table table-bordered main-table">
            <tr>
              <th>#ID</th>
              <th>Comment</th>
              <th>Item</th>
              <th>User</th>
              <th>Date</th>
              <th>Control</th>
            </tr>
            <?php 
              foreach($rows as $row) { ?>
                <tr>
                  <td><?= $row["c_id"]?></td>
                  <td><?= $row["comment"]?></td>
                  <td><?= $row["item_name"]?></td>
                  <td><?= $row["member"]?></td>
                  <td><?= $row["comment_date"]?></td>
                  <td>
                    <a href="comments.php?do=Edit&comid=<?= $row["c_id"]?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</a>
                    <a href="comments.php?do=Delete&comid=<?= $row["c_id"]?>" class="btn btn-sm btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                    <?php if ($row["status"] == 0) {  ?>
                    <a href="comments.php?do=Approve&comid=<?= $row["c_id"]?>" class="btn btn-sm btn-info"><i class="fa fa-thumbs-o-up "></i> Approve</a>
                    <?php } ?>
                  </td>
                </tr>
              <?php
              } 
            ?>
          </table>
        </div>
      </div>  
    <?php
    //  End manage page

    } elseif ( $do == "Edit" ) { 
    // Start Edit page
      // check if GET request userid is numeric and get integer value of it
      $comid = isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0 ;
      // select all data depend on comid
      $stmt = $con->prepare("SELECT * FROM comment WHERE c_id = ?");
      // execute query
      $stmt -> execute(array($comid));
      // fetch data from DB
      $row = $stmt -> fetch(); 
      // row count
      $count = $stmt->rowCount();
      // if there is comid show form
      if ($count > 0) { ?>
        <h1 class="text-center member-h1">Edit Comment</h1>
        <div class="container">
          <form class="form-horizontal" action="?do=Update" method="POST">
            <input type="hidden" name="comid" value="<?php echo $row["c_id"] ?>">
            <!-- start comment field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="comment" class="col-sm-2 control-label">Comment</label>
                <div class="col-sm-10" id="comment">
                  <textarea name="comment" class="form-control" cols="30" rows="10"><?php echo $row["comment"] ?></textarea>
                </div>
              </div>
            </div>
            <!-- end comment field -->
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
      echo '<h1 class="text-center member-h1">Update Comment</h1>';
      echo '<div class="container">';
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get VAriables from Form
        $comid      = $_POST["comid"];
        $comment    = $_POST["comment"];
        // Validate the form
        $formError = array();
        if (empty($comment)) {
          $theMsg = '<div class="alert alert-danger">Comment can\'t be<Strong> Empty</Strong> </div>';
          redirectHome($theMsg, "back");
        } else {
          // Update DB with this info
          $stmt = $con -> prepare("UPDATE comment SET comment = ? WHERE c_id = ?");
          $stmt -> execute(array($comment, $comid));
          // echo success msg
          $theMsg = '<div class="alert alert-success">' . $stmt -> rowCount() . ' Record Update</div>,';
          redirectHome($theMsg, "back");
        }
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
        $comid = isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0 ;
        // select all data depend on userid
        $stmt = $con->prepare("SELECT * FROM comment WHERE c_id = ?");
        // execute query
        $stmt -> execute(array($comid));
        // if there is Record
        if ($stmt->rowCount() > 0) {
          $stmt = $con -> prepare("DELETE FROM comment WHERE c_id = ?");
          $stmt -> execute(array($comid));
          $theMsg = '<div class="alert alert-success">'.$stmt->rowCount() .' Record Deleted</div>';
          redirectHome($theMsg, "back");
        } else {
          $theMsg = '<div class="alert alert-danger"><strong>ID NOT EXIST</strong></div>';
          redirectHome($theMsg);
        }
      echo '</div>';
    // End Delete Page
    } elseif ($do == "Approve") {
      // Start Activate Page
        echo '<h1 class="text-center member-h1">Approve Comment</h1>';
        echo '<div class="container">';
          $comid = isset($_GET["comid"]) && is_numeric($_GET["comid"]) ? intval($_GET["comid"]) : 0 ;
          // select all data depend on userid
          $stmt = $con->prepare("SELECT * FROM comment WHERE c_id = ?");
          // execute query
          $stmt -> execute(array($comid));
          // if there is Record
          if ($stmt->rowCount() > 0) {
            $stmt = $con -> prepare("UPDATE comment SET status = 1 WHERE c_id = ?");
            $stmt -> execute(array($comid));
            $theMsg = '<div class="alert alert-success">Comment Approved</div>';
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








