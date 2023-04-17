<?php
require_once("db_conn.php");

if (!empty($_POST["emailid"])) {
    $result = mysqli_query($conn, "SELECT count(id) FROM users WHERE email='" . $_POST["emailid"] . "'  AND id!=" . $_POST['get_edit_id'] . " ");
    $row = mysqli_fetch_row($result);
    $email_count = $row[0];

    echo $email_count;
}

if (!empty($_POST["phno"])) {
    $result = mysqli_query($conn, "SELECT count(id) FROM users WHERE phno='" . $_POST["phno"] . "' AND id!=" . $_POST['get_edit_id'] . "");
    $row = mysqli_fetch_row($result);
    $phno_count = $row[0];
    echo $phno_count;
}
