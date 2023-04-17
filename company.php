<?php
include('db_conn.php');
include("functions.php");
login_check();
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
  header("location:loginf.php");
}
$query_comp = mysqli_query($conn, "select * from `company`  ORDER BY id DESC ");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="description" content="Company Profile List">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Company</title>
  <?php include "./links.php" ?>

<body>
  <header>
    <?php include "./header.php" ?>
  </header>
  <h1 style="text-align: center;">COMPANY</h1>
  <?php if (empty(mysqli_fetch_array($query_comp))) { ?>
    <a href="company_ae.php"><button type="button" class="btn btn-primary btn-lg" name="add" style="margin-top:20px;margin-left:47%;">Add</button></a>
  <?php   } ?>
  <!-- TABLE -->
  <div class="table-resopnsive">
    <table class=" table table-bordered text-center border-2" style="margin-top:40px;border-top:2px solid coral ">
      <thead>
        <th>S.no</th>
        <th>Name</th>
        <th>GST No</th>
        <th>Edit</th>
      </thead>
      <tbody>
        <?php

        $query_comp = mysqli_query($conn, "select * from `company`  ORDER BY id DESC ");

        $no = '1';
        while ($rows = mysqli_fetch_array($query_comp)) {

        ?>

          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $rows['cname']; ?></td>
            <td><?= $rows['gst'] ?></td>
            <td><a href="company_ae.php?user_id=<?= $_SESSION['login'] ?>&id=<?= $rows['id'] ?>"><i class="material-icons">mode_edit</i></a>

            </td>

          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</body>

</html>