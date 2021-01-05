<?php
  ob_start(); //output buffering
  session_start();
  if (isset($_SESSION["Username"])) {
    $pageTitle = "Dashboard";
    include "init.php";
    // Start Dashboard page?>
      <div class="container text-center home-stat">
        <h1 class="member-h1">Dashboard</h1>
        <div class="row">
          <div class="col-md-3">
            <div class="stat st-members clickable" onclick="location.href='members.php'">
              <i class="fa fa-users st-i"></i>
              <div class="info">
                <div>Total Members</div>
                <span><?=countItems("UserID", "users")?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat st-pending clickable" onclick="location.href='members.php?page=Pending'">
              <i class="fa fa-user-plus st-i"></i>
              <div class="info">
                <div>Pending Members</div>
                <span><?=checkItem("RegStatus", "users", 0)?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat st-items clickable" onclick="location.href='items.php'">
              <i class="fa fa-tag st-i"></i>
              <div class="info">
                <div>Total Items</div>
                <span><?=countItems("item_ID", "items")?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat st-comments clickable" onclick="location.href='comments.php'">
            <i class="fa fa-comments st-i"></i>
              <div class="info">
                <div>Total Comments</div>
                <span><?=countItems("c_id", "comment")?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="container">
        <div class="latest">
          <div class="row">
            <div class="col-sm-6">
              <div class="card">
                <?php $latestUsers = 5; ?>
                <div class="card-header">
                  <i class="fa fa-users l-t"></i> 
                  Latest <?= $latestUsers; ?> Users
                  <span class="pull-right toggle-info">
                    <i class="fa fa-minus fa-lg"></i>
                  </span>
                </div>
                <div class="card-body">
                <ul class="list-group latest-list">
                  <?php 
                    $theLatestUsers = getLatest("*", "users", "UserID", $latestUsers);
                    if (!empty($theLatestUsers)){
                      foreach ($theLatestUsers as $user) {
                        echo '<li class="list-group-item">' . $user["Username"];
                        echo '<a href="members.php?do=Edit&userid='.$user["UserID"];
                        echo '"><span class="btn btn-success btn-sm pull-right"><i class="fa fa-edit"></i> Edit</span></a>';
                        echo $user["RegStatus"] == 0 ? '<a href="members.php?do=Activate&userid='. $user["UserID"] .'"><span class="btn btn-sm pull-right btn-info"><i class="fa fa-check"></i> Activate</a><span></li>' : '';
                      }
                    } else {
                      echo "No Records to Show";
                    }
                  ?>
                </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card">
                <?php $latestItems = 5; ?>
                <div class="card-header">
                  <i class="fa fa-tag l-t"></i> 
                  Latest <?= $latestItems ?> Items
                  <span class="pull-right toggle-info">
                    <i class="fa fa-minus fa-lg"></i>
                  </span>
                </div>
                <div class="card-body">
                  <ul class="list-group latest-list">
                    <?php 
                      $theLatestItems = getLatest("*", "items", "item_ID", $latestItems);
                      if (!empty($theLatestItems)){
                        foreach ($theLatestItems as $item) {
                          echo '<li class="list-group-item">' . $item["Name"];
                          echo '<a href="items.php?do=Edit&itemid='.$item["item_ID"];
                          echo '"><span class="btn btn-success btn-sm pull-right"><i class="fa fa-edit"></i> Edit</span></a>';
                          echo $item["Approve"] == 0 ? '<a href="items.php?do=Approve&itemid='. $item["item_ID"] .'"><span class="btn btn-sm pull-right btn-info"><i class="fa fa-check"></i> Approve</a><span></li>' : '';
                        }
                      } else {
                        echo "No Records to Show";
                      }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- Start latest Comments -->
          <div class="row">
            <div class="col-sm-12">
              <div class="card my-3">
                <?php $latestComment = 5; ?>
                <div class="card-header">
                  <i class="fa fa-tag l-t"></i> 
                  Latest <?= $latestItems ?> Comments
                  <span class="pull-right toggle-info">
                    <i class="fa fa-minus fa-lg"></i>
                  </span>
                </div>
                <div class="card-body">
                  <?php 
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
                                              ORDER BY date DESC 
                                              LIMIT $latestComment");
                    $stmt -> execute();
                    $rows = $stmt -> fetchAll();
                    if (!empty($rows)) {
                      foreach ($rows as $row) {
                        echo '<div class="row">';
                        echo   '<div class="col-sm-2">'. $row["member"] .'</div>';
                        echo   '<div class="col-sm-10 c_bc">'. $row["comment"] .'</div>';?>
                          <div class="showOnHover mx-auto mt-2">
                            <a href="comments.php?do=Edit&comid=<?= $row["c_id"]?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="comments.php?do=Delete&comid=<?= $row["c_id"]?>" class="btn btn-sm btn-danger confirm"><i class="fa fa-close"></i> Delete</a>
                            <?php if ($row["status"] == 0) { ?>
                            <a href="comments.php?do=Approve&comid=<?= $row["c_id"]?>" class="btn btn-sm btn-info"><i class="fa fa-thumbs-o-up "></i> Approve</a>
                            <?php } ?>  
                          </div>
                        </div> <!-- row close--> 
                        <?php
                        echo '<hr>';
                      }
                    } else {
                      echo "No Records to Show";
                    }
                  ?>
                </div>
              </div>
            </div>                
          </div>
        </div>
      </div>
    <?php // End Dashboard page   
    include $tpl . "footer.php";

  } else {
    // echo "U ARE NOT AUTHORIZED TO VIEW THIS PAGE :)";
    header("location: index.php");
    exit();
  }
  ob_end_flush();
?>