<?php
include("db_conn.php");
include("functions.php");
login_check();
date_default_timezone_set("Asia/Calcutta");
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
  header("location:index.php");
}
$limit = 10;
if (isset($_GET["page"])) {
  $page  = $_GET["page"];
} else {
  $page = 1;
};
$start_from = ($page - 1) * $limit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Users</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <?php include'./links.php'?>
</head>
<body>
  <header>
    <?php include "./header.php" ?>
  </header>

  <div class="container">
    <h2 class="text-center">Users</h2>
    <div class="text-center">
      <a href="user_ae.php"><button type="button" class="btn btn-primary btn-lg" name="add">Add</button></a>
    </div>
  </div>



  <div class="table-responsive">
    <table class=" table table-bordered text-center border-2 mt-5" style="border-top:2px solid coral ">
      <thead>
        <th>S.no</th>
        <th>Username</th>
        <th>Email</th>
        <th>Ph:no</th>
        <th>AnyIP</th>
        <th>Login Status</th>
        <th>Force Logout</th>
        <th>Expiry Date</th>
        <th>Edit</th>
      </thead>
      <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM `users` ORDER BY id DESC LIMIT $start_from, $limit");
        $no = $start_from + 1;

        while ($row = mysqli_fetch_array($result)) {
          $qstatus = mysqli_fetch_array(mysqli_query($conn, "select status_act,user_id from `login_history` WHERE user_id=" . $row['id'] . " ORDER BY id DESC  "));


          if ($row['ip'] == '1') {
            $row['ip'] = "";
          } else {
            $row['ip'] = '<a href="user_allowed.php?user_id=' . $row["id"] . '"><i class="fa fa-times" aria-hidden="true"></i></a>';
          }

          $x = (int)$row['expiry'];
          $time = date("d-m-Y", $x);
          if (empty($qstatus)) {
            $fl = ' ';
          } else {
            if ($qstatus['status_act'] == 1 && $qstatus['user_id'] != $_SESSION['login']) {
              $fl = '<a href="force_logout.php?user_id= ' . $row['id'] . '" ><i class="fa fa-bolt" onClick="window.location.reload(true)"></a></i>';
            } else {
              $fl = " ";
            }
          }
        ?>

          <tr <?= $row['suspend'] == '1' ? 'class="table-danger"' : (empty($qstatus) ? "" : ($qstatus['status_act'] == 1 ? 'class="table-success"' : 'class="table-white" ')); ?> ?>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phno']; ?></td>
            <td><?php echo $row['ip']; ?></td>
            <td><a href="login_history.php?user_id=<?= $row['id'] ?>"><i class="fa fa-history"></i></a></td>
            <td><?= $fl ?></td>
            <td><?= $x == 0 ? "" : $time; ?></td>
            <td>
              <a href="user_ae.php?id=<?php echo $row['id']; ?>"><i class="material-icons">mode_edit</i></a>

            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
  <!-----------------Pagination------------------------ -->
  <?php

  $result_db = mysqli_query($conn, "SELECT COUNT(id) FROM `users` ");
  $row_db = mysqli_fetch_row($result_db);
  $total_records = $row_db[0];
  $total_pages = ceil($total_records / $limit);
  $pagLink = "<ul class='pagination' style='margin-left:50%'>";
  for ($i = 1; $i <= $total_pages; $i++) {
    $pagLink .= "<li class='page-item'><a class='page-link' href='user.php?page=" . $i . "'>" . $i . "</a></li>";
  }
  echo $pagLink . "</ul>";
  ?>
</body>

</html>