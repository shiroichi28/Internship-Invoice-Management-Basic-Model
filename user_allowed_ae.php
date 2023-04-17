<?php
include("functions.php");
login_check();
include("db_conn.php");
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
    header("location:loginf.php");
}
if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
}
//DISPLAY
if (isset($_GET['id'])) {

    $ide = $_GET['id'];
    $query = mysqli_query($conn, "select * from `allowed_ip` where id='$ide'");
    $row = mysqli_fetch_array($query);
    $created = $row['created_by'];
    $create_on = date("d-m-Y H:i", (int) $row['created_on']);
    $edited = $row['edited_by'];


    if ($row['ip_type'] == 1) {
        $str = explode(".", $row['ip']);
    } else {
        $arr = explode(":", $row['ip']);
    }
    if ($edited != 0) {
        $e = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$edited'"));
        $edit_on = date("d-m-Y H:i", (int) $row['edited_on']);
    }
    $c = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$created'"));
}
$q = mysqli_fetch_array(mysqli_query($conn, "select * from `users` where id='$id'"));


//ADD
if (isset($_POST['submit'])) {
    //     echo "<pre>";
    //   print_r($_POST);
    //   echo "</pre>";
    //   exit;
    $user_id = $id;
    $b = '.';
    $c = ':';
    $ip_type = $_POST['check'];
    $created_by = $_SESSION["login"];
    $created_on = (strtotime(date('Y-m-d H:i:s')));

    if ($ip_type == '1') {
        $ip = $_POST['i1'] . $b . $_POST['i2'] . $b . $_POST['i3'] . $b . $_POST['i4'];
    } else if ($ip_type == '2') {
        $ip = $_POST['i1'] . $c . $_POST['i2'] . $c . $_POST['i3'] . $c . $_POST['i4'] . $c . $c . $_POST['i5'];
    }
    if (!(isset($_GET['id']))) {
        $query = "INSERT INTO allowed_ip (user_id, ip_type,ip,created_by,created_on) 
                VALUES('$user_id','$ip_type','$ip',$created_by,'$created_on')";
        mysqli_query($conn, $query);

        header("location:user_allowed.php?user_id=$id");
    } else {
        $edited_by = $_SESSION["login"];
        $edited_on = (strtotime(date('Y-m-d H:i:s')));
        $suspend = $_POST['suspend'];

        mysqli_query($conn, "update `allowed_ip` set ip_type='$ip_type',ip='$ip',edited_by='$edited_by',edited_on='$edited_on',suspend='$suspend' where id='$ide'");
        header("location:user_allowed.php?user_id=$id");
    }
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "./links.php" ?>
    <title>User Allowed AE</title>

</head>

<body onload="chktype()">
    <!-- NAVIGATION BAR -->
    <header>
        <?php include "./header.php" ?>
    </header>
    <!-- MAIN -->
    <div class="row mt-2">
        <div class="col-2">


            <a href="user_allowed.php?user_id=<?= $_GET['user_id'] ?>" style=" text-decoration: none;color:#ddd;"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
        </div>
        <div class="col-2 " style="margin-left: -13%;">
            <h1>Any IP</h1>

        </div>
    </div>

    <div class="container-fluid">

        <?php if (isset($_GET['id'])) { ?>
            <div class="row " style="margin-top:20px;margin-left:30%">
                <div class="col-lg-2">
                    <label for="created_by">Created By</label><br>
                    <input type="text" id="created_by" value="<?= $c['username'] ?>" class="form-control" readonly>
                </div>
                <div class="col-lg-2">
                    <label for="created_on">Created On</label><br>
                    <input type="text" id="created_on" value="<?= $create_on ?>" class="form-control" readonly>
                </div>
                <?php if ($edited != 0) { ?>
                    <div class="col-lg-2">
                        <label for="edited_by">Edited By</label><br>
                        <input type="text" id="edited_by" value="<?= $e['username'] ?>" class="form-control" readonly>
                    </div>
                    <div class="col-lg-2">
                        <label for="edited_on">Edited On</label><br>
                        <input type="text" id="edited_on" value="<?= $edit_on ?>" class="form-control" readonly>
                    </div> <?php } ?>

            </div><?php  } ?>
    </div>

    <div class="row" style="margin-top:20px;margin-left:34px">
        <div class="col-2">
            <label for="username">Username:</label>
            <input class="form-control" type="text" id="username" value="<?= $q['username']; ?>" readonly />
        </div>
        <div class="col-2 ">
            <label for="email">Email:</label>
            <input class="form-control" type="text" id="email" value="<?= $q['email']; ?>" readonly />
        </div>
    </div>


    <!-- ipform -->
    <div class="container">
        <form action="" autocomplete="off" method="post" style="margin-top:18px;margin-left:30%">
            <div class="row">
                <div class="col-2" style="margin-left:-38%;">
                    <label for="check">IP TYPE: </label><br>
                    <select id="check" name="check" onchange="chktype();" class="form-control" required>
                        <option value=""> - Select - </option>
                        <option value="1" id="v4" <?= (isset($_GET['id']) && $row['ip_type'] == '1' ? 'selected' : '') ?>> IPV4 </option>
                        <option value="2" id="v6" <?= (isset($_GET['id']) && $row['ip_type'] == '2' ? 'selected' : '') ?>> IPV6 </option>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-left:-40%;margin-top:20px">
                <div class="col-lg-2">
                    <input type="text" class="iv6in form-control" name="i1" min=0 max=255 maxlength="4" required value="<?= isset($_GET['id']) ? ($row['ip_type'] == 2 ? $arr[0] : $str[0]) : ""; ?>">
                </div>
                <div class="col-lg-2">
                    <input type="text" class="iv6in form-control" name="i2" min=0 max=255 maxlength="4" required value="<?= isset($_GET['id']) ? ($row['ip_type'] == 2 ? $arr[1] : $str[1]) : ""; ?>">
                </div>
                <div class="col-lg-2">
                    <input type="text" class="iv6in form-control" name="i3" min=0 max=255 maxlength="4" required value="<?= isset($_GET['id']) ? ($row['ip_type'] == 2 ? $arr[2] : $str[2]) : ""; ?>">
                </div>
                <div class="col-lg-2">

                    <input type="text" class="iv6in form-control" name="i4" min=0 max=255 maxlength="4" required value="<?= isset($_GET['id']) ? ($row['ip_type'] == 2 ? $arr[3] : $str[3]) : "" ?>">
                </div>
                <div class="col-lg-2">
                    <input type="text" required class="form-control" name="i5" maxlength="4" id="inputs" style="visibility:hidden;margin-left:2px;" value="<?= isset($_GET['id']) ? ($row['ip_type'] == 2 ? $arr[5] : '') : "" ?>">
                </div>
            </div>
            <?php if ((isset($_GET['id']))) { ?>
                <div class=" row mt-5 ">
                    <div class="col " style="margin-left:20px;">
                        <label for="r1"> Suspend</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="suspend" value="0" checked="checked" <?= (isset($_GET['id']) && $row['suspend'] == 0 ? 'checked' : '') ?>id="r1">
                            <label class="form-check-label" for="r1">No</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="suspend" <?= (isset($_GET['id']) && $row['suspend'] == 1 ? 'checked' : '') ?> value="1" id="r2">
                            <label class="form-check-label" for="r2">Yes</label>
                        </div>
                    </div>
                </div>

            <?php   } ?>
            <!-- submit -->
            <div class="row" style="margin-top:20px;margin-left:20%;">
                <div class="col-lg-2">
                    <button id="submit" class="btn btn-success btn-block btn-lg mt-2 text-body" style="margin-left:50px;" name="submit">
                        <a style=" text-decoration: none;color:#ddd;">Submit</a></button>
                </div>
            </div>
        </form>

    </div>



    <script>
        function chktype() {
            var ty = document.getElementById("check").value;
            const a = document.getElementsByClassName("iv6in");
            console.log(ty);
            if (ty == 1) {
                a[0].setAttribute('type', 'number')
                a[1].setAttribute('type', 'number');
                a[2].setAttribute('type', 'number')
                a[3].setAttribute('type', 'number')

                document.getElementById('inputs').style.visibility = 'hidden';
                document.getElementById('inputs').required = false;

            } else if (ty == 2) {

                a[0].setAttribute('type', 'text')
                a[1].setAttribute('type', 'text');
                a[2].setAttribute('type', 'text')
                a[3].setAttribute('type', 'text')
                document.getElementById('inputs').style.visibility = 'visible';

            }
        }
    </script>
</body>

</html>