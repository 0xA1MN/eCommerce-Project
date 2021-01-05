<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getTitle(); ?></title>
    <link rel="stylesheet" href="<?= $css?>bootstrap.min.css">
    <link rel="stylesheet" href="<?= $css?>font-awesome.min.css">
    <link rel="stylesheet" href="<?= $css?>frontend.css">
  </head>
  <body>
    <!-- Start Upper bar -->
    <div class="container">
      <div class="upper-bar">
        <div class="align-center">
        <?php 
          if (isset($_SESSION["user"])) {?>
            <div class="dropdown">
              <button class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?=$sessionUser?>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="profile.php">Profile</a>
                <a class="dropdown-item" href="newAd.php">New Item</a>
                <a class="dropdown-item" href="logout.php">logout</a>
              </div>
              <?php 
                if (!empty(getAvatar($sessionUser)[0])) {
                  echo '<img class="avatar" src="admin/uploads/avatars/'.getAvatar($sessionUser)[0] .'">';
                } else {
                  echo '<img class="avatar" src="admin/uploads/avatars/Av_hold.png">';
                }
              // Admin Or Inactive User 
              if (isAdmin($sessionUser)) {
                echo '<span class="badge badge-danger ml-3 clickable p-1" onclick="location.href=\'admin/dashboard.php\'">You Are A Admin Click To Admin Page</span>';
              } 
              if (!checkUserStatus($sessionUser)) {
                echo '<span class="badge badge-info ml-3 clickable p-1">Wait To Activate</span>';
              } ?>
            </div>
          <?php
          } else {
            echo '<a href="login.php" class="float-right" style="line-height: 40px">';
            echo '<span>Login / SingUp</span>';
            echo '</a>';
          }?>
        </div>
      </div>
    </div>
    <div style="clear:both"></div>
    <!-- End Upper bar -->

    <!-- Start Nav bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="Index.php">HomePage</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav ml-auto mr-1">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Categories
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <?php 
                  $cats = getCat();
                  foreach ($cats as $cat){
                    echo '<a class="dropdown-item" href="categories.php?pageid=' . $cat['ID'] .'">' . $cat['Name'] . '</a>';
                  }
                ?>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Nav bar -->