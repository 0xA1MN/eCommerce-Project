<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href=index.php>Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarNavDropdown">
      <ul class="navbar-nav ml-auto mr-1">
        <li class="nav-item">
          <a class="nav-link" href="category.php"><?php echo lang("CATEGORY");?> <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php"><?php echo lang("ITEMS");?> <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php"> <?php echo lang("MEMBERS");?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php"><?php echo lang("COMMENTS");?> <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../index.php">Shop<span class="sr-only">(current)</span></a>
        </li>     
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo ucwords($_SESSION["Username"]) ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="../index.php">Visit Shop</a>
            <a class="dropdown-item" href="members.php?do=Edit&userid=<?=$_SESSION["ID"]?>">Edit Profile</a>
            <a class="dropdown-item" href="#">Sittings</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>