<?php
  session_start();
  $pageTitle = "Home";
  include "init.php";
  ?>

  <div class="container cat-page-edit">
  <h2 class="text-center cat-title my-4"><?=$pageTitle?></h2> <!--TODO: Dynamic-->
  <div class="row">
    <?php
      $items = getAll("items",1);   // items in selected Category
      if (!empty($items)) {
        foreach ($items as $item) { ?>
          <div class="card col-sm-6 col-md-4 col-lg-3">
            <img src="https://via.placeholder.com/150" class="card-img-top" alt="...">
            <span class="price"><?=$item["Price"]?></span>
            <div class="card-body">
              <h5 class="card-title"><a href="items.php?itemID=<?=$item["item_ID"]?>" class="deco-none clickable"><?=$item["Name"]?></a></h5> 
              <h6 class="card-subtitle mb-2 text-muted">Test</h6> <!--TODO : Username-->
              <p class="card-text"><?=$item["Description"]?></p>
              <p class="date"><?=$item["Add_Date"]?></p>
            </div>
          </div>
      <?php } 
      }
      ?>
  </div>
</div>


  <?php
  include $tpl . "footer.php";
?>