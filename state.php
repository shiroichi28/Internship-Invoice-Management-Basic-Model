<?php
include("db_conn.php");
include("functions.php");
login_check();
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
  header("location:loginf.php");
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
  <?php include "./links.php" ?>
  <title>State</title>


</head>

<body>
  <header>
    <?php include "./header.php" ?>
  </header>
  <div class="row mt-5 ">

    <h1 class="text-center">STATE</h1>


    <a href="state_ae.php"><button type="button" class="btn btn-primary btn-lg" name="add" style="margin-top:20px;margin-left:47%;">Add</button></a>
  </div>

  <!-- TABLE -->
  <div class="table-responsive">
    <table class=" table table-bordered text-center mt-5 border-2" style="border-top:2px solid coral ">
      <thead>
        <th>S.no</th>
        <th>Name</th>
        <th>Edit</th>
      </thead>

      <tbody>
        <?php

        $result = mysqli_query($conn, "SELECT * FROM `state` ORDER BY id DESC LIMIT $start_from, $limit");
        $no = $start_from + 1;

        //  $start = ($limit * ($page-1))+1;
        while ($rows = mysqli_fetch_array($result)) {

        ?>

          <tr <?= $rows['suspend'] == '1' ? 'class="table-danger"' : " "; ?>>
            <td><?php echo $no++; ?></td>
            <td><?= $rows['state_name'] . ' - ' . $rows['state_code'] ?></td>
            <td><a href="state_ae.php?user_id=<?= $_SESSION['login'] ?>&id=<?= $rows['id'] ?>"><i class="material-icons">mode_edit</i></a>

            </td>

          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>


  <!------------------------------------ Pagination------------------------------------------- -->
  <?php

  $result_db = mysqli_query($conn, "SELECT COUNT(id) FROM `state` ");
  $row_db = mysqli_fetch_row($result_db);
  $total_records = $row_db[0];
  $total_pages = ceil($total_records / $limit);
  $pagLink = "<ul class='pagination' style='margin-left:50%'>";
  for ($i = 1; $i <= $total_pages; $i++) {
    $pagLink .= "<li class='page-item'><a class='page-link' href='state.php?page=" . $i . "'>" . $i . "</a></li>";
  }
  echo $pagLink . "</ul>";
  ?>
</body>

</html>