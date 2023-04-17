<?php
include("db_conn.php");
include("functions.php");
login_check();
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
   header("location:index.php");
}
//ADD
if (isset($_POST['submit'])) {
   $l = $_SESSION["login"];
   $username = $_POST['username'];
   $email = $_POST['email'];
   $phno =  $_POST['phno'];
   $password_1 =  $_POST['password'];
   $chk = $_POST['check'];
   $ex = $_POST['edate'];
   $expiry = strtotime($ex);
   $date = (strtotime(date('Y-m-d H:i:s')));
   $password = md5($password_1);

   if (!isset($_GET['id'])) {
      $query = "INSERT INTO users (username, email,phno,password,created_by,created_on,ip,expiry) 
                VALUES('$username', '$email', '$phno','$password','$l','$date','$chk','$expiry')";
      mysqli_query($conn, $query);

      header('location:user.php');
   } else {
      $suspend = $_POST['suspend'];
      $id = $_GET['id'];
      if (empty("$password_1")) {
         mysqli_query($conn, "update `users` set username='$username', email='$email' ,phno='$phno', edited_by='$l',edited_on='$date',suspend='$suspend',ip='$chk',expiry='$expiry' where id='$id'");
         header('location:user.php');
      } else {
         mysqli_query($conn, "update `users` set username='$username', email='$email' ,phno='$phno', edited_by='$l',edited_on='$date',password='$password',suspend='$suspend',ip='$chk',expiry='$expiry' where id='$id'");
         header('location:user.php');
      }
   }
}
// DISPLAY
if (isset($_GET['id'])) {

   $id = $_GET['id'];
   $query = mysqli_query($conn, "select * from `users` where id='$id'");
   $row = mysqli_fetch_array($query);
   $time = date("Y-m-d", (int) $row['expiry']);
   $created_on = date("d-m-Y H:i", (int) $row['created_on']);
   $options = $row['ip'];

   $db = mysqli_query($conn, "select username from `users` where id=" . $row['created_by'] . " ");
   $r = mysqli_fetch_array($db);

   $dbb = mysqli_query($conn, "select username from `users` where id=" . $row['edited_by'] . " ");
   $rr = mysqli_fetch_array($dbb);
   $edited_on = date("d-m-Y H:i", (int)$row['edited_on']);
}
?>
<!-- html -->
<!DOCTYPE html>
<html lang="en">

<head>
   <?php include "./links.php" ?>

   <title>User AE</title>



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
</head>

<body>
   <header>
      <?php include "./header.php" ?>
   </header>
   <!-- Main -->
   <div class="row mt-2">
      <div class="col-2">
         <a href="user.php"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>

      </div>
      <div class="col-2 " style="margin-left: -13%;">
         <h1>Users</h1>

      </div>
   </div>
   <!-- details -->
   <?php if (isset($_GET['id'])) { ?>
      <div class="container-fluid">
         <div class="row justify-content-center">
            <div class="col-lg-2">
               <label for="created_by">Created By</label><br>
               <input type="text" id="created_by" value="<?= $r['username'] ?>" class="form-control" readonly>
            </div>
            <div class="col-lg-2">
               <label for="created_on">Created On</label><br>
               <input type="text" id="created_on" value="<?= $created_on ?>" class="form-control" readonly>
            </div>
            <?php if ($rr != 0) { ?>
               <div class="col-lg-2">
                  <label for="edited_by">Edited By</label><br>
                  <input type="text" id="edited_by" value="<?= $rr['username'] ?>" class="form-control" readonly>
               </div>
               <div class="col-lg-2">
                  <label for="edited_on">Edited On</label><br>
                  <input type="text" id="edited_on" value="<?= $edited_on ?>" class="form-control" readonly>
               </div> <?php } ?>

         </div>
      </div>

   <?php  } ?>
   <!-- aeform -->
   <div class="container mt-5 justify-content-center">
      <form name="aeform" action="" method="post" autocomplete="off">
         <div class="row justify-content-center">
            <div class="col-2">
               <label for="name">Username</label>
               <input class="form-control form-control-lg" type="text" placeholder="Username" value="<?= isset($_GET['id']) ? $row['username'] : ""; ?>" id="name" name="username" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" required>
            </div>
            <div class="col-4 ">
               <label for="emailid">Email</label>
               <input oninput="this.value = this.value.toUpperCase()" class="form-control form-control-lg" value="<?= isset($_GET['id']) ? $row['email'] : ""; ?>" onchange="checkemailAvailability()" type="email" placeholder="Email" id="emailid" name="email" pattern="[A-Z0-9]+@[A-Z]+\.[A-Z]{2,3}" required>
               <span id="email-availability-status"></span>
            </div>
            <div class="col-2">
               <label for="phno">Phone</label>
               <input class="form-control form-control-lg" type="tel" value="<?= isset($_GET['id']) ? $row['phno'] : " "; ?>" placeholder="Phone Number" id="phno" maxlength="10" pattern="(\+91)?(-)?\s*?(91)?\s*?(\d{3})-?\s*?(\d{3})-?\s*?(\d{4})" name="phno" onkeypress="return(event.charCode > 47 && event.charCode <= 57)" onchange="check_phno()" required>
               <span class="" id="phno-availability-status"></span>
            </div>
         </div>

         <div class="row mt-5 justify-content-center">
            <div class="col-2 " style="margin-left:-16%;">
               <label for="Password">Password</label>
               <div class="input-group ">
                  <input value="" class="form-control form-control-lg" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}" type="password" placeholder="Password" id="Password" name="password" <?= (!isset($_GET['id']) ? 'required' : " ") ?>>
                  <span class="input-group-text" id="addon-wrapping" onclick="password_show_hide();">
                     <i class="fas fa-eye" id="show_eye"></i>
                     <i class="fas fa-eye-slash " id="hide_eye" style="display:none;"></i>
                  </span>
               </div>

            </div>
            <div class="col-2">
               <label for="check">AnyIP: </label><br>
               <select id="check" name="check" class="form-control form-control-lg" required>
                  <option value=""> - Select - </option>
                  <option value="1" <?= (isset($_GET['id']) && $row['ip'] == 1 ? 'selected' : '') ?>> YES </option>
                  <option value="0" <?= (isset($_GET['id']) && $row['ip'] == 0 ? 'selected' : '') ?>> NO </option>
               </select>
            </div>
            <div class="col-2">
               <label for="name">Expiry Date</label>
               <input <?= (!isset($_GET['id']) ? 'required' : ' ') ?> value="<?php echo $time ?>" class="form-control form-control-lg" type="date" name="edate" min="<?php echo date("Y-m-d"); ?>" id="date">
            </div>
         </div>
         <!-- Suspend -->
         <?php if ((isset($_GET['id'])) && $_SESSION['login'] != $row['id']) { ?>
            <div class=" row mt-5 ">
               <div class="col " style="margin-left:20px;">
                  <label for="r1"> Suspend</label>
                  <div class="form-check">
                     <input class="form-check-input" type="radio" name="suspend" value="0" checked="checked" <?= (isset($_GET['id']) && $row['suspend'] == 0 ? 'checked' : '') ?>id="r1">
                     <label class="form-check-label" for="r1">No</label>
                  </div>
                  <div class="form-check">
                     <input class="form-check-input" type="radio" name="suspend" <?= (isset($_GET['id']) && $row['suspend'] == 1 ? 'checked' : '') ?> value="1" id="r2">
                     <label class="form-check-label" for="r2">Yes</label>
                  </div>
               </div>
            </div>

         <?php   } ?>

         <!-- submit -->
         <div class="row justify-content-center mt-4">
            <div class="col-2">
               <button type="submit" id="asubmit" class="btn btn-success " name="submit">Submit</button>
            </div>
         </div>
      </form>

   </div>

   <!-- script -->
   <script type="text/Javascript">


      function checkemailAvailability() {
       
              <?php if (!isset($_GET['id'])) { ?> 
               var get_id=0;
               <?php } else { ?>
               var get_id='<?= $_GET['id'] ?>';
               <?php } ?>

         jQuery.ajax({
         url: "ae_availability.php",
         data:'emailid='+$("#emailid").val()+'&get_edit_id='+get_id,
         type: "POST",
         success:function(data){
         
         if(data!=0){
         document.getElementById('emailid').value="";
         document.getElementById('email-availability-status').innerHTML="Email Already Exist";
      }else{
         document.getElementById('email-availability-status').innerHTML="";
      }
      
         }
         })}
         function check_phno() {
         
         jQuery.ajax({
         url: "ae_availability.php",
         data:'phno='+$("#phno").val()+'&get_edit_id='+get_id,
         type: "POST",
         success:function(data){
         
         
         if(data!=0){
         document.getElementById('phno').value="";
         document.getElementById('phno-availability-status').innerHTML="Mobile Number Already Exist";
         }else{
         document.getElementById('phno-availability-status').innerHTML="";
         }
         
         
         }
         })
         }

      </script>
</body>

</html>