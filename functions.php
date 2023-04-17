<?php
function login_check()
{
    include("db_conn.php"); 
    session_start();
    $cid = $_SESSION['cid'];
    $login = $_SESSION['login'];
    $auth_token = $_SESSION['token'];
    $fet = mysqli_fetch_array(mysqli_query($conn, "SELECT session_id,session_token FROM `users` where id='$login' "));
    if ($cid != $fet['session_id'] ||$auth_token!=$fet['session_token']) {
        session_destroy();
        header("location:index.php");
    }
}
function getToken($length){
  $token = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet.= "0123456789";
  $max = strlen($codeAlphabet); 

  for ($i=0; $i < $length; $i++) {
    $token .= $codeAlphabet[random_int(0, $max-1)];
  }

  return $token;
}

function getBrowser()
 {
   $user_agent = $_SERVER['HTTP_USER_AGENT'];
   $browser = "N/A";

   $browsers = [
     '/msie/i' => 'Internet explorer',
     '/firefox/i' => 'Firefox',
     '/safari/i' => 'Safari',
     '/chrome/i' => 'Chrome',
     '/edge/i' => 'Edge',
     '/opera/i' => 'Opera',
     '/mobile/i' => 'Mobile browser',
   ];

   foreach ($browsers as $regex => $value) {
     if (preg_match($regex, $user_agent)) {
       $browser = $value;
     }
   }

   return $browser;
 }




$numbers = array();
for ($i = 1; $i <= 999; $i++) {
    
    $number = "CUS" . str_pad($i, 3, "0", STR_PAD_LEFT);
    $numbers[] = $number;
}
function fetch_state($conn){

  $fetch_state= mysqli_query($conn, "select * from `state` WHERE suspend=0  ORDER BY id DESC ");
  return $fetch_state;
}

function creator($id,$from,$conn){

  $fetcher = mysqli_fetch_array(mysqli_query($conn, "select * from `$from` where id=$id"));
  $created= $fetcher['created_by'];
  $edited = $fetcher['edited_by'];
  $c = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$created'"));
  $author['created_on'] = date("d-m-Y H:i", (int) $fetcher['created_on']);
  $author['created_by'] = $c['username'];
  $e = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$edited'"));
  if($edited!=0){
    $author['edited_by'] = $e['username'];
    $author['edited_on'] = date("d-m-Y H:i", (int) $fetcher['edited_on']);
  }


  return $author;
  
}
function existValues($id,$from,$conn){
  $fetcher = mysqli_fetch_array(mysqli_query($conn, "select * from `$from` where id=$id"));
  return $fetcher;
}