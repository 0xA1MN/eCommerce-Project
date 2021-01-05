<?php
  ob_start();
  session_start();
  $pageTitle = "Show Item";
  include "init.php";

  $itemID = isset($_GET["itemID"]) && is_numeric($_GET["itemID"]) ? intval($_GET["itemID"]) : 0 ;
  $stmt = $con->prepare("SELECT 
                            items.*, categories.Name as Cat_Name, users.Username as User_Name
                          FROM
                            items
                            INNER JOIN
                            categories
                          on
                            categories.ID = items.Cat_ID
                          INNER JOIN
                            users
                          on
                            users.UserID = items.Member_ID
                          WHERE 
                            item_ID = ?
                          AND
                            Approve = 1");
  $stmt -> execute(array($itemID));
  $count = $stmt -> rowCount();
  
  if ($count > 0) {
    $item = $stmt -> fetch();
?>
<h1 class="text-center my-4 "><a href="categories.php?pageid=<?=$item["Cat_ID"]?>&pagename=<?=$item["Cat_Name"]?>" class="deco-none clickable"><?=ucfirst($item["Cat_Name"])?></a></h1>
<div class="container item-info">
  <div class="row">
    <div class="col-md-3">
      <img src="https://via.placeholder.com/255" class="img-thumbnail mx-auto d-block">
    </div>
    <div class="col-md-9">
      <h5 class="mb-3"><?= $item["Name"]?></h5>
      <p><span>Description : </span><?= ucfirst($item["Description"])?></p>
      <ul class="list-unstyled">
        <li>
          <i class="fa fa-calendar fa-fw"></i>
          <span>Add Date : </span><?= $item["Add_Date"]?></li>
        <li>
          <i class="fa fa-money fa-fw"></i>
          <span>Price : </span><?= $item["Price"]?> $</li>
        <li>
          <i class="fa fa-building fa-fw"></i>
          <span>Made In : </span><?= ucfirst($item["Country_Made"])?></li>
        <li>
          <i class="fa fa-user fa-fw"></i>
          <span>Added By : </span><a href="#" class="deco-none clickable"><?= ucfirst($item["User_Name"])?></a></li>
      </ul>
    </div>
  </div>
  <hr>
  <!-- Start Add Comment -->
  <?php 
    if (isset($_SESSION['user'])) {
  ?>
  <div class="row">
    <div class="offset-md-3 col-md-9">
      <h5 class="text-center my-2">Add your comment</h5>
      <form action="<?=$_SERVER['PHP_SELF'] . '?itemID=' . $item['item_ID']?>" method="POST">
        <div class="form-group">
          <textarea name="comment" required placeholder="Comment Here" cols="30" rows="4" class="form-control block"></textarea>
          <input type="Submit" value="Add Comment" class="form-control btn btn-primary">
        </div>
        <?php
          // server side of comment
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $insertedComment = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
            $stmt = $con -> prepare("INSERT INTO 
            comment(comment, status, comment_date, item_id, user_id)
            VALUES (?, ?, now(), ?, ?)");
            $stmt -> execute(array($insertedComment, 0, $item["item_ID"], $_SESSION["userId"]));
          }
        ?>
      </form>
    </div>
  </div>
  <?php 
    } else {
      echo '<div class="text-center"><a href="login.php">Login</a> Or <a href="login.php">Register</a> To Add Comment</div>';
    }
  ?>
  <hr>
  <!-- End Add Comment -->
  <!-- Start posted Comments area -->
  <?php 
    $comments = getComment("Status", 1);

    foreach ($comments as $comment) { ?>
      <div class="media posted-comments">
        <img src="https://via.placeholder.com/100" class="my-auto"  width="100" height="100">
        <div class="media-body">
          <h5 class="mt-0"><?=$comment["Username"]?></h5>
          <?=$comment["comment"]?>
          <span><?=$comment["comment_date"]?></span>
        </div>
      </div>
    <?php  
    }
    ?>
  <!-- End posted Comments area -->
</div>
<?php
  } else {
    echo '<div class="text-center my-5">There Is No Such ID Or This Item Waiting Approval</div>';
    header('refresh:3; url=profile.php');
  }
  include $tpl . "footer.php";
  ob_flush();
?>