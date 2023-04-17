<?php
include("db_conn.php");
include("functions.php");
login_check();
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
  header("location:loginf.php");
}
//DISPLAY
if (isset($_GET['user_id'])) {

  $id = $_GET['user_id'];
  $q = mysqli_query($conn, "select * from `users` where id='$id'");
  $row = mysqli_fetch_array($q);
}
//Pagination
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
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login History</title>
  <!-- LINKS -->

  <!------------------------------------------ ICONS-LINK --------------------->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
  <!------------------------------------------ jQUERY --------------------->

  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
  <!------------------------------------------ BOOTSTRAP LINK --------------------->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
  <!------------------------------------------ EXTERNAL --------------------->
  <link rel="stylesheet" href="style.css">

</head>

<body>
  <!-- NAVIGATION -->
  <header>
    <?php include "./header.php" ?>
  </header>

  <!-- MAIN -->
  <div class="row mt-2">
    <div class="col-2">
      <a href="user.php"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>

    </div>
    <div class="col-2 " style="margin-left: -13%;">
      <h1>Login History</h1>

    </div>
  </div>


  <div class="row" style="margin-top:100px">
    <div class=" col-2 col-xs-2">
      <label for="username">Username: </label>
      <input class="form-control  " type="text" id="username" value="<?= $row['username']; ?>" readonly ">
    </div>
<div class=" col-2 col-xs-2 ">
     <label for=" email">Email: </label>
      <input class="form-control " type="text" id="email" value="<?= $row['email']; ?>" readonly>
    </div>
  </div>
  <!-- TABLE -->
  <div class="table-responsive">
    <table class=" table table-bordered mt-5" style="margin-top:40px;border-top:2px solid coral ">
      <thead>
        <th>S.no</th>
        <th>DATE</th>
        <th>IP</th>
        <th>IP TYPE</th>
        <th>LOGIN BROWSER</th>
        <th>LOGIN OS</th>
        <th>LOGIN TIME</th>
        <th>LOGOUT TIME</th>
      </thead>
      <tbody>
        <?php

        $result = mysqli_query($conn, "select * from `login_history` where user_id='$id' ORDER BY id DESC LIMIT $start_from, $limit ");
        $no = $start_from + 1;
        while ($rows = mysqli_fetch_array($result)) {
          $date = date("d-m-Y ", (int)$rows['login_time']);
          $login_time = date("d-m-Y H:i", (int)$rows['login_time']);
          $logout_time = 0;
          if ($rows['logout_time'] != 0) {
            $logout_time = date("d-m-Y H:i", (int)$rows['logout_time']);
          }
        ?>

          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $date; ?></td>
            <td> <?= $rows['login_ip']; ?></td>
            <td><?= $rows['ip_type'] == 1 ? 'IPV4' : 'IPV6'; ?></td>
            <td><?= $rows['login_browser'] ?></td>
            <td><?= $rows['os'] ?></td>
            <td><?= $login_time ?></td>
            <td <?= $rows['force_logout_status'] == 1 ? 'class="table-danger"' : ($rows['out_by_in_status'] == 1 ? 'class="table-primary"' : 'class="table-success"'); ?>><?= $logout_time ?></td>

          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php

  $result_db = mysqli_query($conn, "SELECT COUNT(id) FROM `login_history` where user_id='$id'");
  $row_db = mysqli_fetch_row($result_db);
  $total_records = $row_db[0];
  $total_pages = ceil($total_records / $limit);
  $pagLink = "<ul class='pagination' style='margin-left:50%'>";
  for ($i = 1; $i <= $total_pages; $i++) {
    $pagLink .= "<li class='page-item'><a class='page-link' href='login_history.php?user_id=$id&page=" . $i . "'>" . $i . "</a></li>";
  }
  echo $pagLink . "</ul>";
  ?>

</body>

</html>