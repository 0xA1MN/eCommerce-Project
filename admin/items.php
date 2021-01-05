<?php
	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Items';

	if (isset($_SESSION['Username'])) {
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    
		if ($do == 'Manage') {
			// Start Manage
			$query = "";
      if (isset($_GET["page"]) && $_GET["page"] == "Pending") {
        $query = "AND RegStatus = 0";
      }
			// Fetch Data From DB Except Admin
			$stmt = $con -> prepare(" SELECT
																	items.*, categories.Name as Category_Name, users.Username
																from 
																	items
																inner JOIN 
																	categories
																	on
																	categories.ID = items.Cat_ID
																inner JOIN 
																	users 
																	On 
																	users.UserID = items.Member_ID
																	ORDER BY item_ID Desc"); 
			$stmt -> execute();
			$items = $stmt -> fetchAll()
			?>

			<h1 class="text-center member-h1">Manage Items</h1>
			<div class="container">
				<div class="table-responsive">
					<a href="?do=Add" class="btn btn-primary add-m-btn" style="margin: 20px 30%;"><i class="fa fa-plus"></i> Add a New Item</a>
					<table class="text-center table table-bordered main-table">
						<tr>
							<th>#ID</th>
							<th>Item</th>
							<th>Description</th>
							<th>Price</th>
							<th>Adding Date</th>
							<th>Category</th>
							<th>Username</th>
							<th>Control</th>
						</tr>
						<?php 
							if (!empty($items)){
								foreach($items as $item) { ?>
									<tr>
										<td><?= $item["item_ID"]?></td>
										<td><?= $item["Name"]?></td>
										<td><?= $item["Description"]?></td>
										<td><?= $item["Price"]?></td>
										<td><?= $item["Add_Date"]?></td>
										<td><?= $item["Category_Name"]?></td>
										<td><?= $item["Username"]?></td>
										<td>
											<a href="items.php?do=Edit&itemid=<?= $item["item_ID"]?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</a>
											<a href="items.php?do=Delete&itemid=<?= $item["item_ID"]?>" class="btn btn-sm btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
											<?php if ($item["Approve"] == 0) {  ?>
												<a href="items.php?do=Approve&itemid=<?=$item["item_ID"]?>" class="btn btn-sm btn-info"><i class="fa fa-thumbs-o-up "></i> Approve</a>
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
			// End Manage
		} elseif ($do == 'Add') {
			// Start Add ?>
			<h1 class="text-center member-h1">Add Item</h1>
      <div class="container">
        <form class="form-horizontal" action="?do=Insert" method="POST">
          <!-- start name -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="name" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10" id="name">
								<input  type="text"
												name="name"
												class="form-control"
												required="required"
												placeholder="Name Of Item">
              </div>
            </div>
          </div>
          <!-- end name -->
          <!-- start Description -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="description" class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10" id="name">
								<input 	type="text"
												name="description"
												class="form-control"
												required="required"
												placeholder="Description">
              </div>
            </div>
          </div>
          <!-- end Description -->
          <!-- start price -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="price" class="col-sm-2 control-label">Price</label>
              <div class="col-sm-10" id="name">
								<input	type="text" 
												name="price"
												class="form-control"
												required="required"
												placeholder="set Price">
              </div>
            </div>
          </div>
          <!-- end price -->
          <!-- start Country_Made -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="country" class="col-sm-2 control-label">Country Made</label>
              <div class="col-sm-10" id="name">
								<input 	type="text"
												name="country"
												class="form-control"
												placeholder="Country Of Made">
              </div>
            </div>
          </div>
          <!-- end Country_Made -->
          <!-- start Status -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="status" class="col-sm-2 control-label">Status</label>
              <div class="col-sm-10" id="name">
								<select class="form-control" name="status">
									<option value="0">...</option>
									<option value="1">New</option>
									<option value="2">Like New</option>
									<option value="3">Used</option>
									<option value="4">Old</option>
								</select>
							</div>
            </div>
          </div>
          <!-- end status -->
          <!-- start Members -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="member" class="col-sm-2 control-label">Member</label>
              <div class="col-sm-10" id="name">
								<select class="form-control" name="member">
									<option value="0">...</option>
									<?php 
										$stmt = $con -> prepare("SELECT * FROM users");
										$stmt -> execute();
										$users = $stmt -> fetchAll();
										foreach($users as $user) {
											echo '<option value="' . $user["UserID"] . '">' . $user["Username"] . '</option>';
										}
									?>
								</select>
							</div>
            </div>
          </div>
          <!-- end Members -->
          <!-- start Categories -->
          <div class="form-group edit-members">
            <div class="row">
              <label for="category" class="col-sm-2 control-label">Category</label>
              <div class="col-sm-10" id="name">
								<select class="form-control" name="category">
									<option value="0">...</option>
									<?php 
										$stmt = $con -> prepare("SELECT * FROM categories");
										$stmt -> execute();
										$cats = $stmt -> fetchAll();
										foreach($cats as $cat) {
											echo '<option value="' . $cat["ID"] . '">' . $cat["Name"] . '</option>';
										}
									?>
								</select>
							</div>
            </div>
          </div>
          <!-- end Categories -->
          <!-- start button field -->
          <div class="form-group edit-members">
            <div class="row">
							<button	type="submit" class="m-auto btn-sm btn btn-primary">Add Item</button>
            </div>
          </div>
          <!-- end button field -->
        </form>
      </div>    
			<?php
			// End Add
		} elseif ($do == 'Insert') {
			// start Insert
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				echo '<h1 class="text-center member-h1">Insert Item</h1>';
				echo '<div class="container">';
				
				// Get VAriables from Form
				$name       	= filter_var($_POST["name"],FILTER_SANITIZE_STRING);
				$description	= filter_var($_POST["description"],FILTER_SANITIZE_EMAIL);
				$price				= filter_var($_POST["price"],FILTER_SANITIZE_EMAIL);
				$country      = filter_var($_POST["country"],FILTER_SANITIZE_STRING);
				$status       = filter_var($_POST["status"],FILTER_SANITIZE_STRING);
				$member       = filter_var($_POST["member"],FILTER_SANITIZE_STRING);
				$category     = filter_var($_POST["category"],FILTER_SANITIZE_STRING);

				// Validate the form
				$formError = array();
				empty($name) ? $formError[] = 'Name Can\'T Be <Strong>Empty</Strong>' : "";
				empty($description) ? $formError[] = 'Description Can\'T Be <Strong>Empty</Strong>' : "";
				empty($price) ? $formError[] = 'Price Can\'T Be <Strong>Empty</Strong>' : "";
				empty($country) ? $formError[] = 'Country Can\'T Be <Strong>Empty</Strong>' : "";
				$status == 0 ? $formError[] = 'You must choose <Strong>Status</Strong>' : "";
				$member == 0 ? $formError[] = 'You must choose <Strong>Member</Strong>' : "";
				$category == 0 ? $formError[] = 'You must choose <Strong>Category</Strong>' : "";
				// loop on error array and echo it
				foreach ($formError as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				
				// Check If No Error Proceed Update
				if (empty($formError)) {
					// Insert User Info DB with this info
					$stmt = $con -> prepare("INSERT INTO 
																		items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID)
																		VALUES (?, ?, ?, ?, ?, now(), ?, ?)");
					
					$stmt -> execute(array($name, $description, $price, $country, $status, $category, $member));
					$theMsg = '<div class="alert alert-success">' . $stmt -> rowCount() . ' Record Insert</div>'; 
					redirectHome($theMsg, "items.php");
				};
			
			} else {
				$errorMsg = '<div class="container" style="margin-top: 40px"><div class="alert alert-danger">Can\'t Browse Directly</div>';
				redirectHome($errorMsg, null);
			}
			// End Insert
		} elseif ($do == 'Edit') {
    // Start Edit page
      // check if GET request itemid is numeric and get integer value of it
      $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0 ;
      // select all data depend on itemid
      $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");
      // execute query
      $stmt -> execute(array($itemid));
      // fetch data from DB
      $item = $stmt -> fetch();
      // row count
      $count = $stmt->rowCount();
      // if there is itemid show form
			if ($count > 0) {?>

				<h1 class="text-center member-h1">Edit Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="itemid" value="<?=$itemid?>">
						<!-- start name -->
						<div class="form-group edit-members">
							<div class="row">
								<label for="name" class="col-sm-2 control-label">Name</label>
								<div class="col-sm-10" id="name">
									<input  type="text"
													name="name"
													class="form-control"
													value="<?=$item['Name']?>">
								</div>
							</div>
						</div>
						<!-- end name -->
						<!-- start Description -->
						<div class="form-group edit-members">
							<div class="row">
								<label for="description" class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10" id="name">
								<textarea name="description" class="form-control" id="description" rows="5"><?=$item['Description']?></textarea>
								</div>
							</div>
						</div>
						<!-- end Description -->
						<!-- start price -->
						<div class="form-group edit-members">
							<div class="row">
								<label for="price" class="col-sm-2 control-label">Price</label>
								<div class="col-sm-10" id="name">
									<input	type="text" 
													name="price"
													class="form-control"
													value="<?=$item['Price']?>">
								</div>
							</div>
						</div>
						<!-- end price -->
						<!-- start Country_Made -->
						<div class="form-group edit-members">
							<div class="row">
								<label for="country" class="col-sm-2 control-label">Country Made</label>
								<div class="col-sm-10" id="name">
									<input 	type="text"
													name="country"
													class="form-control"
													value="<?=$item['Country_Made']?>">
								</div>
							</div>
						</div>
						<!-- end Country_Made -->
						<!-- start Status -->
						<div class="form-group edit-members">
							<div class="row">
								<label for="status" class="col-sm-2 control-label">Status</label>
								<div class="col-sm-10" id="name">
									<select class="form-control" name="status">
										<option value="1" <?=$item['Status'] == 1 ? 'Selected' : "" ?>>New</option>
										<option value="2" <?=$item['Status'] == 2 ? 'Selected' : "" ?>>Like New</option>
										<option value="3" <?=$item['Status'] == 3 ? 'Selected' : "" ?>>Used</option>
										<option value="4" <?=$item['Status'] == 4 ? 'Selected' : "" ?>>Old</option>
									</select>
								</div>
							</div>
						</div>
						<!-- end status -->
						<!-- start Members -->
						<div class="form-group edit-members">
							<div class="row">
								<label for="member" class="col-sm-2 control-label">Member</label>
								<div class="col-sm-10" id="name">
									<select class="form-control" name="member">
										<?php 
											$stmt = $con -> prepare("SELECT * FROM users");
											$stmt -> execute();
											$users = $stmt -> fetchAll();
											if (!empty($users)){
												foreach($users as $user) { ?>
													<option value="<?=$user["UserID"]?>" <?= $item["Member_ID"] == $user["UserID"] ? "Selected" : ""?>>
														<?=$user["Username"]?>
													</option>
												<?php
												}
											} else {
												echo '<div class="p-2 text-center">No Records to Show</div>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<!-- end Members -->
						<!-- start Categories -->
						<div class="form-group edit-members">
							<div class="row">
								<label for="category" class="col-sm-2 control-label">Category</label>
								<div class="col-sm-10" id="name">
									<select class="form-control" name="category">
										<?php 
											$stmt = $con -> prepare("SELECT * FROM categories");
											$stmt -> execute();
											$cats = $stmt -> fetchAll();
											foreach($cats as $cat) { ?>
												<option value="<?=$cat["ID"]?>" <?= $cat["ID"] == $item["Cat_ID"] ? "Selected" : ""?>>
													<?=$cat["Name"]?>
												</option>';
											<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<!-- end Categories -->
						<!-- start button field -->
						<div class="form-group edit-members">
							<div class="row">
								<button	type="submit" class="m-auto btn-sm btn btn-primary">Update Item</button>
							</div>
						</div>
						<!-- end button field -->
					</form>
					<hr>
					<?php
					// Fetch Data From DB Except Admin
					$stmt = $con -> prepare(" SELECT 
																			comment.*, users.Username as member
																		FROM 
																			Comment
																		inner JOIN 
																			users 
																			On 
																			users.UserID = comment.user_id
																		WHERE 
																			item_id = ?"); 
					$stmt -> execute(array($itemid));
					$rows = $stmt -> fetchAll();
					if (empty($rows)){
						echo '<div class="text-center alert alert-info">NO Comments to Display</div>';
					} else { ?>						
						<h1 class="text-center member-h1">Manage <?= $item['Name']?> Comments</h1>
						<div class="container">
							<div class="table-responsive">
								<table class="text-center table table-bordered main-table">
									<tr>
										<th>Comment</th>
										<th>User</th>
										<th>Date</th>
										<th>Control</th>
									</tr>
									<?php 
										foreach($rows as $row) { ?>
											<tr>
												<td><?= $row["comment"]?></td>
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
					<?php } ?>

				</div>
			<?php
      // if there is itemid show form
      } else {echo "NO ID";} 
      echo '</div>'; // Container Close Of Edit page
    // End Edit page
		} elseif ($do == 'Update') {
      // Start Update Page
      // print_r($_POST); //if u wanna to se post request content 
      echo '<h1 class="text-center member-h1">Update Member</h1>';
			echo '<div class="container">';
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Get VAriables from Form
        $itemid        = $_POST["itemid"];
        $name           = $_POST["name"];
        $description    = $_POST["description"];
        $price	        = $_POST["price"];
        $country     		= $_POST["country"];
        $status    			= $_POST["status"];
        $member         = $_POST["member"];
        $category       = $_POST["category"];

				// Update DB with this info
        $stmt = $con -> prepare("UPDATE items 
                                  SET Name = ?,
                                      Description = ?,
                                      Price = ?,
                                      Country_Made = ?,
                                      Status = ?,
                                      Member_id = ?,
                                      Cat_ID = ?
                                  WHERE 
																			item_ID = ? ");
        $stmt -> execute([$name, $description, $price, $country, $status, $member, $category,$itemid]);
        // echo success msg
        $theMsg = '<div class="alert alert-success">' . $stmt -> rowCount() . ' Record Update</div>,';
        redirectHome($theMsg, "items.php");
        } else {
        $theMsg = '<div class="alert alert-danger">Can\'t Browse Directly</div>';
        redirectHome($theMsg);
      }
      // End Update Page
		} elseif ($do == 'Delete') {
			// Start Delete Page
			echo '<h1 class="text-center member-h1">Delete Category</h1>';
			echo '<div class="container">';
				$itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0 ;
				// check items in DB
				$check = checkItem("item_ID", "items", $itemid);
				// if there is Record
				if ($check > 0) {
					$stmt = $con -> prepare("DELETE FROM items WHERE item_ID = ?");
					$stmt -> execute(array($itemid));
					$theMsg = '<div class="alert alert-success">'.$stmt->rowCount() .' Record Deleted</div>';
					redirectHome($theMsg, "back");
				} else {
					$theMsg = '<div class="alert alert-danger"><strong>ID NOT EXIST</strong></div>';
					redirectHome($theMsg, "back");
				}
			echo '</div>';
			// End Delete Page			
		} elseif ( $do == 'Approve' ) {
      // Start Approve Page
			echo '<h1 class="text-center member-h1">Approve Item</h1>';
			echo '<div class="container">';
				$itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0 ;
				// check item
				$check = checkItem("item_ID", "items", $itemid);
				if ($check > 0) {
					$stmt = $con -> prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
					$stmt -> execute(array($itemid));
					$theMsg = '<div class="alert alert-success">User Activated</div>';
					redirectHome($theMsg, "back");
				} else {
					$theMsg = '<div class="alert alert-danger"><strong>ID NOT EXIST</strong></div>';
					redirectHome($theMsg);
				}
			echo '</div>';
			// End Approve Page
		}
		include $tpl . 'footer.php';
	} else {
		header('Location: index.php');
		exit();
	}
	ob_end_flush(); // Release The Output
?>