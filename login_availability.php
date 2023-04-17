<?php
require_once("db_conn.php");

if (isset($_POST["emailid"])) {
  
    $result = mysqli_query($conn, "SELECT count(id) FROM users WHERE ((email='" . $_POST["emailid"] . "' OR phno='" . $_POST["emailid"] . "') ) AND suspend=0  ");
    $row = mysqli_fetch_row($result);
    $email_count = $row[0];

    echo $email_count;
}
