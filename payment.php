<?php
include('db_conn.php');
include("functions.php");
login_check();
if (isset($_SESSION['user_name'])) {
} else {
    header("location:loginf.php");
}
if (isset($_GET['fy'])) {
    $financial_year = $_GET['fy'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "./links.php" ?>
    <title>Payment</title>
</head>

<body>
    <header>
        <?php include "./header.php" ?>
    </header>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-2">
                <h1>Payment</h1>
                <a href="payment_ae.php"><button type="button" class="btn btn-primary btn-lg" name="add" style="margin-top:20px;margin-left:20px;">Add</button></a>

            </div>

        </div>
    </div>
</body>

</html>