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
}
$q = mysqli_query($conn, "select * from `users` where id='$id'");
$row = mysqli_fetch_array($q);
//Pagination
$limit = 10;
if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $limit;
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "./links.php" ?>
    <title>User Allowed</title>


</head>

<body>
    <!-- NAVIGATION BAR -->
    <header>
        <?php include "./header.php" ?>
    </header>
    <div class="row mt-2">
        <div class="col-2">
            <a href="user.php"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>

        </div>
        <div class="col-2 " style="margin-left: -13%;">
            <h1>Any IP</h1>

        </div>
    </div>
    <!-- MAIN -->
    <div class="row mt-3">
        <div class=" col-2 col-xs-2  ">
            <label for="username">Username: </label>
            <input class="form-control " type="text" id="username" value="<?= $row['username']; ?>" readonly>
        </div>
        <div class="col-2 col-xs-2 ">
            <label for="email">Email: </label>
            <input class="form-control " type="text" id="email" value="<?= $row['email']; ?>" readonly>
        </div>
        <div class="col-2 col-xs-2 ">
            <button id="submit" class="btn btn-primary btn-lg " type="button" name="submit" style="margin-top:23px;"><a href="user_allowed_ae.php?user_id=<?= $id ?>" style="text-decoration:none;color:white;">Add</a></button>

        </div>
    </div>
    <!-- TABLE -->
    <div class="table-responsive">
        <table class=" table table-bordered mt-4 border-2 " style="border-top:2px solid coral ">
            <thead>
                <th>S.no</th>
                <th>IP</th>
                <th>IP Type</th>
                <th>Created</th>
                <th>Edit</th>
            </thead>

            <tbody>
                <?php

                $result = mysqli_query($conn, "select * from `allowed_ip` where user_id='$id' ORDER BY id DESC LIMIT $start_from, $limit");
                $no = $start_from + 1;
                while ($rows = mysqli_fetch_array($result)) {
                    $cr = $rows['created_by'];
                    $s = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id=$cr"));
                ?>

                    <tr <?= $rows['suspend'] == 1 ? 'class="table-danger"' : '' ?>>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $rows['ip']; ?></td>
                        <td><?= $rows['ip_type'] == 1 ? 'IPV4' : 'IPV6'; ?></td>
                        <td><?php echo  $s['username'] ?></td>

                        <td>
                            <a href="user_allowed_ae.php?user_id=<?= $_GET['user_id'] ?>&id=<?php echo $rows['id']; ?>"><i class="material-icons">mode_edit</i></a>

                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <!----------------------------------- Pagination -------------------->
    <?php

    $result_db = mysqli_query($conn, "SELECT COUNT(id) FROM `allowed_ip` where user_id='$id'");
    $row_db = mysqli_fetch_row($result_db);
    $total_records = $row_db[0];
    $total_pages = ceil($total_records / $limit);
    $pagLink = "<ul class='pagination' style='margin-left:50%'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagLink .= "<li class='page-item'><a class='page-link' href='user_allowed.php?user_id=$id&page=" . $i . "'>" . $i . "</a></li>";
    }
    echo $pagLink . "</ul>";
    ?>
</body>

</html>