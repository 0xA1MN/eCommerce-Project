<?php
	// Categories Page

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Categories';

	if (isset($_SESSION['Username'])) {
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {
      // Start Manage Page
      $sort = "ASC";
      $sort_array = array("ASC", "DESC");
      if (isset($_GET["sort"]) && in_array($_GET["sort"], $sort_array)) {
        $sort = $_GET["sort"];
      } 
      $stmt = $con -> prepare("SELECT * FROM categories ORDER BY Ordering $sort");
      $stmt -> execute();
      $cats = $stmt->fetchAll();?>
      
      <h1 class="text-center member-h1">Manage Categories</h1>
      <div class="container categories">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-edit"></i> <span>Manage Categories</span>
            <div class="option pull-right">
              <i class="fa fa-sort"></i> Ordering : [
              <a class="<?= $sort == "ASC" ? "active" : ""?>" href="?sort=ASC">Asc</a>
               | 
              <a class="<?= $sort == "DESC" ? "active" : ""?>"href="?sort=DESC">Desc</a>
              ]
              <i class="fa fa-eye"></i> View : [
              <span class="active" data-view="full">Full</span>
              |
              <span>Classic</span>
              ]
            </div>
          </div>
          <div class="card-body">
          <?php 
            if (!empty($cats)){
              foreach($cats as $cat) {
                echo '<div class="cat">';
                  echo '<div class="hidden-buttons">';
                    echo '<a href="category.php?do=Edit&catid='.$cat["ID"].'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit </a>';
                    echo '<a href="category.php?do=Delete&catid='.$cat["ID"].'" class="confirm btn btn-danger btn-sm"><i class="fa fa-delete"></i> Delete </a>';
                  echo '</div>';
                  echo '<h3>'.$cat["Name"] . '</h3>';
                  echo '<div class="full-view">';
                    echo '<p>'; 
                      if ($cat["Description"] == ""){echo "This Category Has No Description";} else{echo $cat["Description"];} 
                    echo '</p>';
                    if($cat["Visibility"] == 0){echo '<span class="badge badge-pill badge-danger"><i class="fa fa-eye"></i> Hidden</span>';};
                    if($cat["Allow_Comment"] == 0){echo '<span class="badge badge-pill badge-info"><i class="fa fa-close"></i> No Commenting</span>';};
                    if($cat["Allow_Ads"] == 0){echo '<span class="badge badge-pill badge-dark"><i class="fa fa-close"></i> No Ads</span>';};
                  echo '</div>';  
                echo '</div>';
              }
            } else {
              echo '<div class="p-2 text-center">No Records to Show</div>';
            }
          ?>
          </div>
        </div>
        <a class="btn btn-primary add-m-btn" href="category.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
      </div>

      <?php
      // End Manage Page
    } elseif ($do == 'Add') {
      //  Start Add page ?>
      <h1 class="text-center member-h1">Add Category</h1>
      <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST">
          <!-- start name field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="name" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10" id="name">
                <input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="Name Of Category">
              </div>
            </div>
          </div>
          <!-- end name Of Category -->
          <!-- start description field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="description" class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10" id="description">
                <input type="text" name="description" class="form-control" placeholder="Description Area">
              </div>
            </div>
          </div>
          <!-- end description field -->
          <!-- start ordering field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="email" class="col-sm-2 control-label">Ordering</label>
              <div class="col-sm-10" id="email">
                <input type="text" name="ordering" class="form-control" placeholder="Order Of Category">
              </div>
            </div>
          </div>
          <!-- end ordering field -->
          <!-- start visible field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="visible" class="col-sm-2 control-label">Visible</label>
              <div class="col-sm-10" id="visible">
                <div>
                  <input id="vis-y" type="radio" name="visibility" value="1" checked>
                  <label for="vis-y">Yes</label>
                </div>
                <div>
                  <input id="vis-n" type="radio" name="visibility" value="0">
                  <label for="vis-n">No</label>
                </div>
              </div>
            </div>
          </div>
          <!-- end visible field -->
          <!-- start commenting field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="commenting" class="col-sm-2 control-label">Commenting</label>
              <div class="col-sm-10" id="commenting">
                <div>
                  <input id="com-y" type="radio" name="commenting" value="1" checked>
                  <label for="com-y">Yes</label>
                </div>
                <div>
                  <input id="com-n" type="radio" name="commenting" value="0">
                  <label for="com-n">No</label>
                </div>
              </div>
            </div>
          </div>
          <!-- end commenting field -->
          <!-- start ads field -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="ads" class="col-sm-2 control-label">Ads</label>
              <div class="col-sm-10" id="ads">
                <div>
                  <input id="ads-y" type="radio" name="ads" value="1" checked>
                  <label for="ads-y">Allow</label>
                </div>
                <div>
                  <input id="ads-n" type="radio" name="ads" value="0">
                  <label for="ads-n">Block</label>
                </div>
              </div>
            </div>
          </div>
          <!-- end ads field -->
          <!-- start button field -->
          <div class="form-group edit-members">
            <div class="row">
              <button type="submit" class="m-auto btn btn-primary">Add Category</button>
            </div>
          </div>
          <!-- end button field -->
        </form>
      </div>    

    <?php
    } elseif ($do == 'Insert') {
      // Start Insert Page
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo '<h1 class="text-center member-h1">Insert Category</h1>';
        echo '<div class="container">';
        // Get VAriables from Form
        $name         = filter_var($_POST["name"],FILTER_SANITIZE_STRING);
        $desc         = filter_var($_POST["description"],FILTER_SANITIZE_STRING);
        $order        = filter_var($_POST["ordering"],FILTER_SANITIZE_NUMBER_INT);
        $visible      = filter_var($_POST["visibility"],FILTER_SANITIZE_NUMBER_INT);
        $comment      = filter_var($_POST["commenting"],FILTER_SANITIZE_NUMBER_INT);
        $ads          = filter_var($_POST["ads"],FILTER_SANITIZE_NUMBER_INT);          
        
        // check if category exist in DB
        $check = checkItem("Name", "categories", $name);
        if ($check == 1) {
          $theMsg = '<div class="alert alert-danger">Sorry, This Category is Exist</div>';
          redirectHome($theMsg, "back");
        } else {
          // Insert Category Info DB with this info
          $stmt = $con -> prepare("INSERT INTO 
                                          categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                          VALUES(?, ?, ?, ?, ?, ?)");
          $stmt -> execute(array($name, $desc, $order, $visible, $comment, $ads));
          // Success msg
          $theMsg = '<div class="alert alert-success">'. $stmt->rowCount() .'Record Updated</div>';
          redirectHome($theMsg, "back");
        }
      } else {
        $errorMsg = '<div class="container" style="margin-top: 40px"><div class="alert alert-danger">Can\'t Browse Directly</div></div>';
        redirectHome($errorMsg, null, 1);
      };
      // End Insert Page
    } elseif ($do == 'Edit') {
      // Start Edit
      // check if GET request catID is numeric and get integer value of it
      $catid = isset($_GET["catid"]) && is_numeric($_GET["catid"]) ? intval($_GET["catid"]) : 0 ;
      // select all data depend on catid
      $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
      // execute query
      $stmt -> execute(array($catid));
      // fetch data from DB
      $cat = $stmt -> fetch(); 
      // row count
      $count = $stmt->rowCount();
      // if there is catid show form
      if ($count > 0) { ?>
        <h1 class="text-center member-h1">Edit Category</h1>
        <div class="container">
          <form class="form-horizontal" action="?do=Update" method="POST">
            <!-- hidden input to send id to next page -->
            <input type="hidden" name="catid" value="<?=$cat["ID"]?>">
            <!-- start name field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10" id="name">
                  <input type="text" name="name" required="required" value=<?=$cat["Name"]?> class="form-control"  autocomplete="off" placeholder="Edit Category Name">
                </div>
              </div>
            </div>
            <!-- end name Of Category -->
            <!-- start description field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="description" class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10" id="description">
                  <textarea name="description" class="form-control" id="description" rows="5"><?=$cat["Description"]?></textarea>
                </div>
              </div>
            </div>
            <!-- end description field -->
            <!-- start ordering field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="email" class="col-sm-2 control-label">Ordering</label>
                <div class="col-sm-10" id="email">
                  <input type="text" name="ordering" value=<?=$cat["Ordering"]?> class="form-control" placeholder="Change Order Of Category">
                </div>
              </div>
            </div>
            <!-- end ordering field -->
            <!-- start visible field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="visible" class="col-sm-2 control-label">Visible</label>
                <div class="col-sm-10" id="visible">
                  <div>
                    <input id="vis-y" type="radio" name="visibility" value="1" <?= $cat["Visibility"] == 1 ? "checked" : ""?>>
                    <label for="vis-y">Yes</label>
                  </div>
                  <div>
                    <input id="vis-n" type="radio" name="visibility" value="0"  <?= $cat["Visibility"] == 0 ? "checked" : ""?>>
                    <label for="vis-n">No</label>
                  </div>
                </div>
              </div>
            </div>
            <!-- end visible field -->
            <!-- start commenting field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="commenting" class="col-sm-2 control-label">Commenting</label>
                <div class="col-sm-10" id="commenting">
                  <div>
                    <input id="com-y" type="radio" name="commenting" value="1"  <?= $cat["Allow_Comment"] == 1 ? "checked" : ""?>>
                    <label for="com-y">Yes</label>
                  </div>
                  <div>
                    <input id="com-n" type="radio" name="commenting" value="0" <?= $cat["Allow_Comment"] == 0 ? "checked" : ""?>>
                    <label for="com-n">No</label>
                  </div>
                </div>
              </div>
            </div>
            <!-- end commenting field -->
            <!-- start ads field -->
            <div class="form-group edit-members">
              <div class="row">
                <label for="ads" class="col-sm-2 control-label">Ads</label>
                <div class="col-sm-10" id="ads">
                  <div>
                    <input id="ads-y" type="radio" name="ads" value="1" <?= $cat["Allow_Ads"] == 1 ? "checked" : ""?>>
                    <label for="ads-y">Allow</label>
                  </div>
                  <div>
                    <input id="ads-n" type="radio" name="ads" value="0" <?= $cat["Allow_Ads"] == 0 ? "checked" : ""?>>
                    <label for="ads-n">Block</label>
                  </div>
                </div>
              </div>
            </div>
            <!-- end ads field -->
            <!-- start button field -->
            <div class="form-group edit-members">
              <div class="row">
                <button type="submit" class="m-auto btn btn-primary">Update Category</button>
              </div>
            </div>
            <!-- end button field -->
          </form>
        </div>    
        <?php
      // if there is catid show form
      } else {echo "NO ID";} 
        echo '</div>'; // Container Close Of Edit page      
      // End Edit
    } elseif ($do == 'Update') {
      // Start Update Page
      // print_r($_POST); //if u wanna to se post request content 
      echo '<h1 class="text-center member-h1">Update Member</h1>';
      echo '<div class="container">';
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Get VAriables from Form
        $id             = $_POST["catid"];
        $name           = $_POST["name"];
        $description    = $_POST["description"];
        $ordering       = $_POST["ordering"];
        $visibility     = $_POST["visibility"];
        $commenting     = $_POST["commenting"];
        $ads            = $_POST["ads"];

        // Update DB with this info
        $stmt = $con -> prepare("UPDATE categories 
                                  SET Name = ?,
                                      Description = ?,
                                      Ordering = ?,
                                      Visibility = ?,
                                      Allow_Comment = ?,
                                      Allow_Ads = ?
                                  WHERE 
                                      ID = ? ");
        $stmt -> execute([$name, $description, $ordering, $visibility, $commenting, $ads, $id]);
        // echo success msg
        $theMsg = '<div class="alert alert-success">' . $stmt -> rowCount() . ' Record Update</div>,';
        redirectHome($theMsg, "category.php");
        } else {
        $theMsg = '<div class="alert alert-danger">Can\'t Browse Directly</div>';
        redirectHome($theMsg);
      }
      // End Update Page
    } elseif ($do == 'Delete') {
      // Start Delete Page
      echo '<h1 class="text-center member-h1">Delete Category</h1>';
      echo '<div class="container">';
        $catid = isset($_GET["catid"]) && is_numeric($_GET["catid"]) ? intval($_GET["catid"]) : 0 ;
        // check items in DB
        $check = checkItem("ID", "categories", $catid);
        // if there is Record
        if ($check > 0) {
          $stmt = $con -> prepare("DELETE FROM categories WHERE ID = ?");
          $stmt -> execute(array($catid));
          $theMsg = '<div class="alert alert-success">'.$stmt->rowCount() .' Record Deleted</div>';
          redirectHome($theMsg, "back");
        } else {
          $theMsg = '<div class="alert alert-danger"><strong>ID NOT EXIST</strong></div>';
          redirectHome($theMsg);
        }
      echo '</div>';
      // End Delete Page
		}

		include $tpl . 'footer.php';

	} else {
		header('Location: index.php');
		exit();
	}

	ob_end_flush(); // Release The Output

?>