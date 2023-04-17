<?php include('db_conn.php');
if (isset($_POST['submit'])) {
     
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phno =  $_POST['phno'];
    $password_1 =  $_POST['password'];
    $password = md5($password_1);
    $created_on = (strtotime(date('Y-m-d H:i:s')));

    $query = "INSERT INTO users (username, email,phno,password,expiry,ip,created_on) 
                  VALUES('$username', '$email', '$phno','$password',0,1,'$created_on')";
    mysqli_query($conn, $query);
    header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Register</title>
    <script>
        function checkemailAvailability() {

            jQuery.ajax({
                url: "check_availability.php",
                data: 'emailid=' + $("#emailid").val(),
                type: "POST",
                success: function(data) {

                    if (data != 0) {
                        document.getElementById('emailid').value = "";
                        document.getElementById('email-availability-status').innerHTML = "Email Already Exist";
                    } else {
                        document.getElementById('email-availability-status').innerHTML = "";
                    }

                }
            })
        }

        function check_phno() {

            jQuery.ajax({
                url: "check_availability.php",
                data: 'phno=' + $("#phno").val(),
                type: "POST",
                success: function(data) {


                    if (data != 0) {
                        document.getElementById('phno').value = "";
                        document.getElementById('phno-availability-status').innerHTML = "Mobile Number Already Exist";
                    } else {
                        document.getElementById('phno-availability-status').innerHTML = "";
                    }


                }
            })
        }
    </script>
</head>

<body style="background-color:#f2aa4cff;">
    <section class="vh-100 bg-image">
        <div class="mask d-flex align-items-center h-100 ">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-3">
                                <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                                <form autocomplete="off" onchange="return check();" action="" method="POST">

                                    <div class="form-outline mb-4">
                                        <input oninput="this.value = this.value.toUpperCase()" placeholder="Username" type="text" id="name" class="form-control form-control-lg" name="username" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" required />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="email" name="email" id="emailid" onchange="checkemailAvailability()"  oninput="this.value = this.value.toUpperCase()" class="form-control form-control-lg" placeholder="Email" title="Enter Valid Mail ID" required pattern="[A-Z0-9]+@[A-Z]+\.[A-Z]{2,3}">
                                        <span id="email-availability-status"></span>
                                    </div>
                                    <div class="form-outline mb-4">

                                        <input placeholder="Phone Number" type="tel" id="phno" class="form-control form-control-lg" maxlength="10" value="" pattern="(\+91)?(-)?\s*?(91)?\s*?(\d{3})-?\s*?(\d{3})-?\s*?(\d{4})" name="phno" onkeypress="return(event.charCode > 47 && event.charCode <= 57)" onchange="check_phno()" required />
                                        <span class="" id="phno-availability-status"></span>
                                    </div>


                                    <div class="form-outline mb-4">
                                        <div class="input-group flex-nowrap">
                                            <input placeholder="Password" type="password" id="Password" class="form-control form-control-lg" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}" onchange="check_pass();" title="Password should contain atleast 1 Special Charcater,Number and Uppercase " name="password" />
                                            <span class="input-group-text" id="addon-wrapping" onclick="password_show_hide();">
                                                <i class="fas fa-eye" id="show_eye"></i>
                                                <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <div class="input-group flex-nowrap">
                                            <input type="password" id="ConfirmPassword" disabled="true" placeholder="Confirm Password" class="form-control form-control-lg " style="background-color: black;" required />
                                            <span class="input-group-text" id="addon-wrapping" onchange="check_pass();" onclick="password_show_hidee();">
                                                <i class="fas fa-eye" id="show_eyee"></i>
                                                <i class="fas fa-eye-slash d-none" id="hide_eyee"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <span id="message" style="color: white;"></span>
                                    </div>


                                    <div class="d-flex justify-content-center">

                                        <button type="submit" id="btnSubmit" class="btn btn-primary btn-block btn-lg mt-2 text-body" name="submit">Submit</button>
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="index.php" class="fw-bold text-white-50 text-body"><u>Login here</u></a></p>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function check_pass() {

            if ((document.getElementById('Password').value == " ") || (document.getElementById('Password').value == "") || document.getElementById('Password').value.length < 8 || !(/(?=.*[!@#$%^&*_=+-])/.test(document.getElementById('Password').value)) || !(/(?=.*[0-9])/.test(document.getElementById('Password').value)) ||
                !(/(?=.*[a-z])(?=.*[A-Z])/.test(document.getElementById('Password').value))) {
                document.getElementById('ConfirmPassword').disabled = true;
                document.getElementById('ConfirmPassword').style.backgroundColor = "black";
                document.getElementById('ConfirmPassword').value = "";

            } else {
                document.getElementById('ConfirmPassword').disabled = false;
                document.getElementById('ConfirmPassword').style.backgroundColor = "white";
            }
        }

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

        function password_show_hidee() {
            var x = document.getElementById("ConfirmPassword");
            var show_eye = document.getElementById("show_eyee");
            var hide_eye = document.getElementById("hide_eyee");
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
    <script type="text/javascript">
        $(function() {
            $("#ConfirmPassword").blur(function() {
                var password = $("#Password").val();
                var confirmPassword = $("#ConfirmPassword").val();
                if (password != confirmPassword) {
                    alert("Passwords do not match.");
                    return false;
                }
                return true;
            });
        });
    </script>

</body>

</html>