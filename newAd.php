<?php
  ob_start();
  session_start();
  $pageTitle = "New Ad";
  include "init.php";
  if (isset($_SESSION['user'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fromErrors = array();
      // filter form vars
      $name         = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
      $description  = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
      $price        = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_INT);
      $country      = filter_var($_POST["country"], FILTER_SANITIZE_STRING);
      $status       = filter_var($_POST["status"], FILTER_SANITIZE_STRING);
      $category     = filter_var($_POST["category"], FILTER_SANITIZE_STRING);
      // validate form
      strlen($name) < 4 ? $fromErrors[] = "Item Title Must Be At Least 4 Character" : 0;
      strlen($description) < 10 ? $fromErrors[] = "Item Description Must Be At Least 10 Character" : 0;
      strlen($country) < 2 ? $fromErrors[] = "Item Country Must Be At Least 2 Character" : 0;
      empty($price) ? $fromErrors[] = "Item Price Can't be Empty" : 0;
      empty($status) ? $fromErrors[] = "Item Status Can't be Empty" : 0;
      empty($category) ? $fromErrors[] = "Item Category Can't be Empty" : 0;

      // insert into DB
      $stmt = $con -> prepare("INSERT INTO
                                items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID)
                                values(?, ?, ?, ?, ?, now(), ?, ?)
                              ");
      $stmt -> execute(array($name, $description, $price, $country, $status, $category, $_SESSION["userId"]));
      
      if($stmt) {
        $successMsg = "Item Added";
      }
    } ?>
    <h2 class="text-center my-3">Create New Ad</h2>
    <div class="create-ad block">
      <div class="container">
        <div class="card">
          <div class="card-header">
            New Ad Section
          </div>
          <div class="card-body">
            <div class="row">

              <!-- Ad Section -->
              <div class="col-md-8">
                <div class="container data">
                  <form class="form-horizontal" action="" method="POST">
                    <!-- start name -->
                    <div class="form-group">
                      <div class="row">
                        <label for="name">Name</label>
                        <div class="col-sm-12" id="name">
                          <input pattern=".{4,}" title="This field Required at least 4 character" type="text" name="name" required class="form-control live"  data-class=".live-name" placeholder="Name Of Item">
                        </div>
                      </div>
                    </div>
                    <!-- end name -->
                    <!-- start Description -->
                    <div class="form-group">
                      <div class="row">
                        <label for="description">Description</label>
                        <div class="col-sm-12" id="name">
                          <input type="text" name="description" required class="form-control live"  data-class=".live-description" placeholder="Description">
                        </div>
                      </div>
                    </div>
                    <!-- end Description -->
                    <!-- start price -->
                    <div class="form-group">
                      <div class="row">
                        <label for="price">Price</label>
                        <div class="col-sm-12" id="name">
                          <input type="number" name="price" required class="form-control live"  data-class=".live-price" placeholder="set Price">
                        </div>
                      </div>
                    </div>
                    <!-- end price -->
                    <!-- start Country_Made -->
                    <div class="form-group">
                      <div class="row">
                        <label for="country">Country Made</label>
                        <div class="col-sm-12" id="name">
                          <input type="text" name="country" required class="form-control" placeholder="Country Of Made">
                        </div>
                      </div>
                    </div>
                    <!-- end Country_Made -->
                    <!-- start Status -->
                    <div class="form-group">
                      <div class="row">
                        <label for="status">Status</label>
                        <div class="col-sm-12" id="name">
                          <select class="form-control" name="status" required>
                            <option value="">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- end status -->
                    <!-- start Categories -->
                    <div class="form-group">
                      <div class="row">
                        <label for="category">Category</label>
                        <div class="col-sm-12" id="name">
                          <select class="form-control" name="category" required="required">
                            <option value="">...</option>
                            <?php
                            $stmt = $con->prepare("SELECT * FROM categories");
                            $stmt->execute();
                            $cats = $stmt->fetchAll();
                            foreach ($cats as $cat) {
                              echo '<option value="' . $cat["ID"] . '">' . $cat["Name"] . '</option>';
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- end Categories -->
                    <!-- start button field -->
                    <div class="form-group">
                      <div class="row">
                        <button type="submit" class="m-auto btn btn-primary">Add Item</button>
                      </div>
                    </div>
                    <!-- end button field -->
                  </form>
                </div>
              </div>

              <!-- Ad Preview -->
              <div class="col-md-4">
                <div class="card preview">
                  <img src="https://via.placeholder.com/150" class="card-img-top" alt="...">
                  <span class="price live-price">price</span>
                  <div class="card-body cat-card-body-edit">
                    <h5 class="card-title live-name">Ad Name</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= ucfirst($_SESSION["user"]) ?></h6>
                    <p class="card-text live-description">Description</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Start looping through errors -->
          <?php
          if (!empty($fromErrors)) {
            foreach ($fromErrors as $error) {
              echo '<div class="alert alert-danger">' . $error . '</div>';
            }
          }
          if (isset($successMsg)) {
            echo '<div class="alert alert-success text-center">'. $successMsg .'</div>';
          }
          ?>
          <!-- End looping through errors -->
        </div>
      </div>
    </div>
  <?php
  }
  include $tpl . "footer.php";
?>