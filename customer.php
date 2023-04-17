<?php
include('db_conn.php');
include("functions.php");
login_check();
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
  header("location:index.php");
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
  $fetch_os = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(inv_bal) as bal FROM invoice WHERE (fy='$financial_year') AND cancel=0 "));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "./links.php" ?>
  <title>Customer</title>

</head>

<body>
  <header>
    <?php include "./header.php" ?>
  </header>
  <div class="row justify-content-center">
    <div class="col-8">
      <h1 style="text-align: center;">CUSTOMER</h1>
      <a href="customer_ae.php?fy=<?= $financial_year ?>"><button type="button" class="btn btn-primary btn-lg" name="add" style="margin-top:20px;margin-left:47%;">Add</button></a>
    </div>
    <div class="col-2 mt-5">
      <label for="osrec">
        OS Receivable
      </label>
      <input type="text" class="form-control" name="osrec" id="osrec" readonly>
    </div>
    <div class="col-1 mt-5">
      <form method="get" style="margin-top:20px;">
        <!-- <label for="fyy">Financial Year</label><br> -->
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

  <!-- TABLE -->
  <div class="table-responsive"></div>
  <table class=" table text-center table-bordered border-2" border="1" style="margin-top:40px;border-top:2px solid coral ">
    <thead>
      <tr>
        <th>S.no</th>
        <th>Name</th>
        <th>OP Bal</th>
        <th colspan="3">OS Receivable</th>
        <th>Edit</th>
      </tr>
      <tr>
        <th></th>
        <th></th>
        <th></th>
        <th>Estimate</th>
        <th>Invoice</th>
        <th>Total</th>
      </tr>

    </thead>
    <tbody>


      <?php
      $result = mysqli_query($conn, "SELECT * FROM `customer` ORDER BY id DESC LIMIT $start_from, $limit");
      $no = $start_from + 1;
      while ($rows = mysqli_fetch_array($result)) {


        if (isset($_GET['fy'])) {

          $fetch_estimate = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(inv_bal) as bal FROM invoice WHERE (inv_type='2' AND cus_id='$rows[cus_no]' ) AND fy='$_GET[fy]' AND cancel=0"));
          $fetch_invoice = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(inv_bal) as bal FROM invoice WHERE (inv_type='1' AND cus_id='$rows[cus_no]' ) AND fy='$_GET[fy]' AND cancel=0"));
          $fetch_op = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(inv_bal) as bal FROM `invoice` WHERE (fy<'$_GET[fy]')  && (cus_id='" . $rows['cus_no'] . "' && cancel=0)    "));
        }
      ?>

        <tr <?= $rows['suspend'] == '1' ? 'class="table-danger"' : 'class="norm"'; ?>>
          <td><?php echo $no++; ?></td>
          <td><?php echo $rows['customer_name']; ?></td>
          <td><?= number_format($fetch_op['bal'],2,'.',',')?></td>
          <td><?= number_format($fetch_estimate['bal'], 2, '.', ',') ?></td>
          <td><?= number_format($fetch_invoice['bal'], 2, '.', ',') ?></td>
          <td><?= number_format($fetch_estimate['bal'] + $fetch_invoice['bal']+$fetch_op['bal'], 2, '.', ',') ?></td>

          <td><a href="customer_ae.php?user_id=<?= $_SESSION['login'] ?>&id=<?= $rows['id'] ?> &fy=<?= $financial_year ?>"><i class="material-icons">mode_edit</i></a>

          </td>

        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

  <!-- ---------------------------------------PAGINATION------------------------------------------- -->
  <footer>

    <?php
    $result_db = mysqli_query($conn, "SELECT COUNT(id) FROM `customer` ");
    $row_db = mysqli_fetch_row($result_db);
    $total_records = $row_db[0];
    $total_pages = ceil($total_records / $limit);
    $pagLink = "<ul class='pagination' style='margin-left:50%'>";
    for ($i = 1; $i <= $total_pages; $i++) {
      $pagLink .= "<li class='page-item'><a class='page-link' href='customer.php?page=" . $i . "'>" . $i . "</a></li>";
    }
    echo $pagLink . "</ul>";
    ?>
  </footer>
  <script>
    document.getElementById('osrec').value = "<?= number_format($fetch_os['bal'], 2, '.', ',') ?>";
  </script>
</body>

</html>