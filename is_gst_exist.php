<?php
require_once("db_conn.php");
if (!empty($_POST["gst"])) {
    $result = mysqli_query($conn, "SELECT count(id) FROM customer WHERE gst='" . $_POST["gst"] . "'  AND id!=" . $_POST['get_edit_id'] . "   ");
    $row = mysqli_fetch_row($result);
    $gst_count = $row[0];

    echo $gst_count;
}
