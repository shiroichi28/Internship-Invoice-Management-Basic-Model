<?php
include("db_conn.php");
include("functions.php");
login_check();
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
  header("location:loginf.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dashboard</title>
  <meta name="description" content="Dashboard for the company">
  <?php include "./links.php" ?>


<body>
  <header>
    <?php include "./header.php" ?>
  </header>


</body>

</html>