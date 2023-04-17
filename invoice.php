<?php
include('db_conn.php');
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
if (isset($_GET['fy'])) {
 
  $financial_year = $_GET['fy'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "./links.php" ?>
  <title>Invoice</title>


</head>

<body>
  <header>
    <?php include "./header.php" ?>
  </header>
  <div class="row justify-content-center">
    <div class="col-6">
      <h1 style="text-align: center;">Invoice</h1>
      <a href="invoice_ae.php?fy=<?= $financial_year ?>"><button type="button" class="btn btn-primary btn-lg" name="add" style="margin-top:20px;margin-left:47%;">Add</button></a>
    </div>
    <div class="col-1 mt-5 ">
      <form method="get" style="margin-top:20px;">

        <select class="form-select " name="fy" id="fyy" onchange="this.form.submit()">
          <?php
          $y = substr(date("Y"), 2);
          $dates = range($y, $y - 3);
          foreach ($dates as $date) {

            if (date('m', strtotime($date)) < 4) {
              $year = ($date - 1) . '-' . $date;
            } else {
              $year = $date . '-' . ($date + 1);
            }

            echo " <option value='" . $year . "' " . (isset($_GET['fy']) && $financial_year == $year ? 'selected' : '') . " > $year</option>";
          }
          ?>
        </select>

      </form>
    </div>
  </div>

  <div class="table-responsive">
    <table class=" table table-bordered text-center border-2 mt-5" style="border-top:2px solid coral ">
      <thead>
        <th>S.no</th>
        <th>Invoice No</th>
        <th>Customer Name</th>
        <th>Invoice Date</th>
        <th>Invoice Amount</th>
        <th>Invoice Bal</th>
        <th>Edit</th>
      </thead>
      <tbody>
        <?php

        $result = mysqli_query($conn, "SELECT * FROM `invoice` WHERE fy='$financial_year' ORDER BY id DESC LIMIT $start_from, $limit");
        $no = $start_from + 1;
        while ($rows = mysqli_fetch_array($result)) {

        ?>

          <tr <?= $rows['cancel'] == '1' ? 'class="table-danger"' : "" ?>>
            <td><?php echo $no++; ?></td>
            <td><?= $rows['inv_id'] ?></td>
            <td><?php echo $rows['cus_name']; ?></td>
            <td><?= date("d-m-Y ", (int) $rows['inv_date']) ?></td>
            <td><?= number_format($rows['inv_bal'], 2, '.', ',') ?></td>
            <td><?= number_format($rows['inv_bal'], 2, '.', ',') ?></td>
            <td><a href="invoice_ae.php?user_id=<?= $_SESSION['login'] ?>&id=<?= $rows['id'] ?>&fy=<?= $financial_year?>"><i class="material-icons">mode_edit</i></a>

            </td>

          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
  <!-------------------------- Pagination ------------------------------------->
  <footer>
    <?php
    $result_db = mysqli_query($conn, "SELECT COUNT(id) FROM `invoice` ");
    $row_db = mysqli_fetch_row($result_db);
    $total_records = $row_db[0];
    $total_pages = ceil($total_records / $limit);
    $pagLink = "<ul class='pagination' style='margin-left:50%'>";
    for ($i = 1; $i <= $total_pages; $i++) {
      $pagLink .= "<li class='page-item'><a class='page-link' href='invoice.php?fy=".$financial_year."&page=" . $i . " '>" . $i . "</a></li>";
    }
    echo $pagLink . "</ul>";
    ?>
  </footer>

</body>

</html>