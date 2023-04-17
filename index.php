<?php
include_once("db_conn.php");
include("functions.php");
session_start();
$error = "Invalid Credentials";
$user_ip = getHostByName(getHostName());  //fetch users ip
  if (strpos($user_ip, '.') !== false) {
    $login_ip_type = 1;
  } else {
    $login_ip_type = 2;
  } //choosing user ip type
  $foros = $_SERVER['HTTP_USER_AGENT'];
  $logger = getBrowser(); // get browser name ,linked to functions php
  $os = explode(";", $foros)[1] . "";
if (isset($_POST['submit'])) {
    $user = $_POST['uname'];
    $password_1 = $_POST['password'];
    $password = md5($password_1);
    $login_time = (strtotime(date('Y-m-d H:i:s')));
    // authentication
    $auth_query = mysqli_fetch_array(mysqli_query($conn, "SELECT count(id) as cnt FROM `users` WHERE  (email='$user' OR phno='$user') AND password='$password' "));
    $exist = $auth_query['cnt'];
    //ip checking
    $ip_query = mysqli_fetch_array(mysqli_query($conn, "SELECT ip as chk ,id FROM `users` WHERE  (email='$user' OR phno='$user') "));
    $ip_check_status = $ip_query['chk'];
    $sts = $ip_query['id'];//getting user id for checking ip in db
    //checks user expiry
    $expiry_query = mysqli_fetch_array(mysqli_query($conn, "SELECT expiry FROM `users` WHERE (email='$user' OR phno='$user')  "));
    $expiry = $expiry_query['expiry'];
    $date = (strtotime(date('Y-m-d')));
    $allowed_ip = mysqli_fetch_array(mysqli_query($conn, "SELECT count(ip) as count  FROM allowed_ip WHERE (user_id='$sts' && ip) AND 'suspend'=0 ")); // getting allowed ip counts
    
    //LOGIN CONDITIONS
    $n = 1; //counter variable for ip counts
    if ($ip_check_status == "0") {
      for ($x = 0; $x < $allowed_ip['count']; $x++) {
        $ipc = mysqli_fetch_array(mysqli_query($conn, "SELECT ip FROM allowed_ip WHERE (user_id='$sts' && id='$n') AND 'suspend'=0"));
        $n++; // traversing each ip
        if ($user_ip == $ipc['ip']) {
          if ($exist > 0 && ($date != $expiry || $expiry == 0)) {
            $sessionDetails = mysqli_fetch_array(mysqli_query($conn, "SELECT username  as uname,id FROM  `users` WHERE  (email='$user' OR phno='$user')"));
            $token = getToken(20); //from function generating session auth tokens
            //session details
            $_SESSION['user_name'] = $sessionDetails['uname'];
            $_SESSION['login'] = $sessionDetails['id'];
            $_SESSION['cid'] = session_id();
            $_SESSION['token'] = $token;

            $sessionUpdate = mysqli_query($conn, "UPDATE `users` set session_id='$_SESSION[cid]', session_token='$token'  WHERE  (email='$user' OR phno='$user')");

            $getLoginHistory = mysqli_fetch_array(mysqli_query($conn, "SELECT logout_time ,id as fid FROM `login_history` WHERE user_id='$_SESSION[login]' ORDER BY id DESC"));

            if ($getLoginHistory['logout_time'] == '0') {
              $log = mysqli_query($conn, " update `login_history`set logout_time='$login_time' , out_by_in_status='1',status_act='0' WHERE id='$getLoginHistory[fid]'");
            }
            $log = "INSERT INTO login_history (user_id,status_act,login_ip,ip_type,login_browser,os,login_time) 
                VALUES('$_SESSION[login]',1,'$user_ip' , '$login_ip_type' , '$logger' ,'$os' , '$login_time')";
            mysqli_query($conn, $log);

            header('Location:user.php');
          } else if ($date == $expiry) {
             $errors['password'] = "User Acoount Has Been Expired";
          } else {
            $errors['password'] = "Invalid Credentials.";
          }
        }
      }
    } else { //for any ip

      if ($exist > 0 && ($date != $expiry || $expiry == 0)) {
        $sessionDetails = mysqli_fetch_array(mysqli_query($conn, "SELECT username  as uname,id,session_id FROM  `users` WHERE  (email='$user' OR phno='$user')"));

        $_SESSION['user_name'] = $sessionDetails['uname'];
        $_SESSION['login'] = $sessionDetails[1];
        $_SESSION['cid'] = session_id();
        $token = getToken(20);
        $_SESSION['token'] = $token;

        $sessionUpdate = mysqli_query($conn, "UPDATE `users` set session_id='$_SESSION[cid]' , session_token='$token' WHERE  (email='$user' OR phno='$user')");

        $getLoginHistory = mysqli_fetch_array(mysqli_query($conn, "SELECT logout_time ,id as fid FROM `login_history` WHERE user_id='$_SESSION[login]' ORDER BY id DESC"));
      
        if ($getLoginHistory['logout_time'] == '0') {
          $log = mysqli_query($conn, "update `login_history` set logout_time='$login_time', out_by_in_status='1',status_act='0' WHERE id='$getLoginHistory[fid]'");
        }

        $log = "INSERT INTO login_history (user_id,status_act,login_ip,ip_type,login_browser,os,login_time) 
                VALUES('$_SESSION[login]',1,'$user_ip' , '$login_ip_type' , '$logger' ,'$os' , '$login_time')";

        mysqli_query($conn, $log);


        header('Location:user.php');
      } else if ($date == $expiry) {
        $errors['password'] = "User Account Has Been Expired";
      } else {
        $errors['password'] = "Invalid Credentials.";
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
  <title>Sign In</title>
  <script>
    function checkemailAvailability() {
      jQuery.ajax({
        url: "login_availability.php",
        data: 'emailid=' + $("#uname").val(),
        type: "POST",
        success: function(data) {

          if (data == 0) {
            document.getElementById('uname').value = "";
            document.getElementById('user-availability-status').innerHTML = "User Does Not Exist";
          } else {
            document.getElementById('user-availability-status').innerHTML = "";
          }

        }
      })
    }
  </script>
</head>

<body style="background-color:#f2aa4cff;">
  <section class="vh-100 ">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-dark text-white" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

              <div class="mb-md-5 mt-md-4 pb-5">
                <form autocomplete="off" action="" method="post">

                  <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                  <p class="text-white-50 mb-5">Please enter your login and password!</p>

                  <div class="form-outline form-white mb-4">
                    <input onchange="checkemailAvailability()" type="text" id="uname" class="form-control form-control-lg" placeholder="Email" required name="uname"  oninput="this.value = this.value.toUpperCase()" />
                    <span id="user-availability-status"></span>
                  </div>

                  <div class="form-outline form-white mb-4">
                    <div class="input-group flex-nowrap">
                      <input type="password" id="Password" class="form-control form-control-lg" placeholder="Password" title="Password should contain atleast 1 Special Charcater,Number and Uppercase and Lowercase" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}" name="password" />
                      <span class="input-group-text" id="addon-wrapping" onclick="password_show_hide();">
                        <i class="fas fa-eye" id="show_eye"></i>
                        <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                      </span>
                    </div>
                    <div class="mb-4">
                      <?php if (!empty($errors['password'])) : ?>
                        <span class="form_error"><?= $errors['password'] ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <button id="submit" class="btn btn-outline-light btn-lg px-5" type="submit" name="submit">Login</button>
                </form>


              </div>

              <div>
                <p class="mb-0">Don't have an account? <a href="signup.php" class="text-white-50 fw-bold">Register</a>
                </p>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    function password_show_hide() {
      var x = document.getElementById("Password");
      var show_eye = document.getElementById("show_eye");
      var hide_eye = document.getElementById("hide_eye");
      hide_eye.classList.remove("d-none");
      if (x.type === "password") {
        x.type = "text";
        show_eye.style.display = "none";
        hide_eye.style.display = "block";
      } else {
        x.type = "password";
        show_eye.style.display = "block";
        hide_eye.style.display = "none";
      }
    }

  
  </script>
</body>

</html>
