<?php
$sname = "sql111.epizy.com";
$uname = "epiz_33600269";
$password = "xI5nQr2tpXa3";
$db_name = "epiz_33600269_dbtest";
$conn = mysqli_connect($sname,$uname, $password, $db_name);
date_default_timezone_set("Asia/Kolkata");
if(!$conn){
    echo "Connection failed";
}
