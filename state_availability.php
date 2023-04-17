<?php
require_once("db_conn.php");

if (!empty($_POST["state"])) {
    $result = mysqli_query($conn, "SELECT count(id) FROM `state` WHERE state_name='" . $_POST["state"] . "'  AND id!=".$_POST['get_edit_id']." ");
     
    $row = mysqli_fetch_row($result);
    $sn_count= $row[0];
    
    echo $sn_count;
}

    if (!empty($_POST["state_code"])) {
        $result = mysqli_query($conn, "SELECT count(id) FROM `state` WHERE state_code='" . $_POST["state_code"] . "' AND id!=".$_POST['get_edit_id']."");
        $row = mysqli_fetch_row($result);
        $sc_count = $row[0];
        echo $sc_count;
    }
?>