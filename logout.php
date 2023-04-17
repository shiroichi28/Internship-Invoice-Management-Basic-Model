<?php
include_once("db_conn.php"); 
$logout_time = (strtotime(date('Y-m-d H:i:s')));
$e = mysqli_fetch_array(mysqli_query($conn, " SELECT id FROM `login_history` WHERE user_id='$_SESSION[login]' ORDER BY id DESC;"));
$log = mysqli_query($conn, "update `login_history`set logout_time='$logout_time' , status_act=0 WHERE id='$e[id]'");
session_destroy();
session_unset();
header("Location:index.php");
?>