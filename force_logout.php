<?php
include_once("db_conn.php");
include("functions.php");
$logout_time = (strtotime(date('Y-m-d H:i:s')));
if (isset($_GET['user_id'])) {
  $id = $_GET['user_id'];
  $auth = mysqli_fetch_array(mysqli_query($conn, "select session_token from `users` where id=$id"));
  $token = getToken(20);
  $sql = mysqli_query($conn, "update `users` set session_token='$token' where id=$id");

  if ($auth['session_token'] != $token) {
    session_destroy();
    session_unset();
    $e = mysqli_fetch_array(mysqli_query($conn, " SELECT id FROM `login_history` WHERE user_id='$id' ORDER BY id DESC;"));

    $log = mysqli_query($conn, "update `login_history`set status_act='0',logout_time='$logout_time', force_logout_status='1'  WHERE id='$e[id]'");
    header("location:index.php");
  }
  header("location:user.php");
}