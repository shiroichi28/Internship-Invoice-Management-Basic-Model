<?php
include('db_conn.php');
include("functions.php");
login_check();
if (isset($_SESSION['user_name'])) {
} else {
   header("location:loginf.php");
}
if (isset($_GET['id'])) {
   $id = $_GET['id'];
   $from = 'company';
   $init=creator($id,$from,$conn);
   $qy = existValues($id, $from, $conn);
}
$fetch_state = fetch_state($conn);
//AE Part//
if (isset($_POST['submit'])) {

   $l = $_SESSION["login"];
   $cname = $_POST['cname'];
   $sname = $_POST['sname'];
   $addr_1 = $_POST['addr_1'];
   $addr_2 = $_POST['addr_2'];
   $addr_3 = $_POST['addr_3'];
   $area = $_POST['area'];
   $city = $_POST['city'];
   $pincode = $_POST['pincode'];
   $state = $_POST['state'];
   $state_code = $_POST['state_code'];
   $gstn = $_POST['gst'];
   $pan = $_POST['pan'];
   $country = $_POST['country'];
   $date = (strtotime(date('Y-m-d H:i:s')));
   $edited_by = $_SESSION["login"];
   $edited_on = (strtotime(date('Y-m-d H:i:s')));
   if (!(isset($_GET['id']))) {
      $query = "INSERT INTO company(cname,short_name,addr_1,addr_2,addr_3,city,pincode,state_name,state_code,country,gst,pan,created_on,created_by,area) 
                VALUES('$cname' ,'$sname', '$addr_1','$addr_2' ,'$addr_3','$city' , '$pincode' , '$state','$state_code','$country','$gstn' , '$pan'  ,'$date','$l','$area')";
      mysqli_query($conn, $query);
      header('location:company.php');
   } else {
      mysqli_query($conn, "update `company` set cname='$cname',short_name='$sname',addr_1='$addr_1',addr_2='$addr_2',addr_3='$addr_3',city='$city',pincode='$pincode',state_name='$state',state_code='$state_code',country='$country',gst='$gstn',pan='$pan',edited_by='$edited_by',edited_on='$edited_on',area='$area' where id='$id'");
      header("location:company.php");
   }
}
?>
<!-- html -->
<!DOCTYPE html>
<html lang="en">
<head>

   <meta name="description" content="Add edit part of company profile">
   <title>Company</title>
   <?php include "./links.php" ?>
</head>

<body onload="getStateCode()">
   <header>
      <?php include "./header.php" ?>
   </header>

   <div class="container-fluid mt-2">
      <div class="row">
         <div class="col-2">
            <a href="company.php"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
         </div>
         <div class="col-2" style="margin-left:-120px;">
            <h1>Company</h1>
         </div>
      </div>
   </div>

   <?php if (isset($_GET['id'])) { ?>
      <div class="container-fluid">
         <div class="row ">
            <div class="col-3">
               <label for="created_by">Created By</label><br>
               <input type="text" id="created_by" value="<?= $init['created_by'] ?>" class="form-control" readonly>
            </div>
            <div class="col-3">
               <label for="created_on">Created On</label><br>
               <input type="text" id="created_on" value="<?= $init['created_on']?>" class="form-control" readonly>
            </div>
            <?php if (isset($init['edited_on'])) { ?>
               <div class="col-3">
                  <label for="edited_by">Edited By</label><br>
                  <input type="text" id="edited_by" value="<?= $init['edited_by'] ?>" class="form-control" readonly>
               </div>
               <div class="col-3">
                  <label for="edited_on">Edited On</label><br>
                  <input type="text" id="edited_on" value="<?= $init['edited_on'] ?>" class="form-control" readonly>
               </div> <?php } ?>

         </div>
      </div>
   <?php  } ?>
   <!-- form -->
   <div class="container mt-4">
      <form action="" method="post" autocomplete="off" >
         <div class="row">
            <div class="col-3">
               <label for="cname">Company Name</label><br>
               <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control" value="<?= isset($_GET['id']) ? $qy['cname'] : ""; ?>" name="cname" id="cname" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" required /> <br>
            </div>
            <div class="col-3">
               <label for="sname">Short Name</label><br>
               <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control" name="sname" value="<?= isset($_GET['id']) ? $qy['short_name'] : ""; ?>" id="sname" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" required />
            </div>
         </div>
         <!-- address -->
         <div class="row mt-3">
            <div class="col-3 ">
               <label for="addr_1">Address 1</label><br>
               <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" value="<?= isset($_GET['id']) ? $qy['addr_1'] : ""; ?>" name="addr_1" id="addr_1 " required /> <br>
            </div>
            <div class="col-3">
               <label for="addr_2">Address 2</label><br>
               <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" name="addr_2" id="addr_2" value="<?= isset($_GET['id']) ? $qy['addr_2'] : ""; ?>" />
            </div>
            <div class="col-3">
               <label for="addr_3">Address 3</label><br>
               <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" name="addr_3" id="addr_3" value="<?= isset($_GET['id']) ? $qy['addr_3'] : ""; ?>" />
            </div>
         </div>
         <!-- code -->
         <div class="row mt-3">
            <div class="col-2">
               <label for="area">Area</label><br>
               <input type="text" class="form-control" name="area" id="area " oninput="this.value = this.value.toUpperCase()" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" value="<?= isset($_GET['id']) ? $qy['area'] : ""; ?>" required /> <br>
            </div>
            <div class="col-2">
               <label for="city">City</label><br>
               <input type="text" class="form-control" name="city" id="city" oninput="this.value = this.value.toUpperCase()" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" value="<?= isset($_GET['id']) ? $qy['city'] : ""; ?>" required />
            </div>
            <div class="col-2">

               <label for="pincode">Pincode</label><br>
               <input type="number" class="form-control" max="999999" min="0" name="pincode" id="pincode" onkeypress="return(event.charCode > 47 && event.charCode <= 57)" required value="<?= isset($_GET['id']) ? $qy['pincode'] : ""; ?>" />
            </div>

            <div class="col-2">
               <label for="state">State</label><br>
               <select class="form-select" name="state" id='state' onchange="getStateCode();validateGst()" required>
                  <option value="">Select</option>
                  <?php
                  
                  while ($rows = mysqli_fetch_array($fetch_state)) {

                     echo "<option     value='" . $rows['id'] . "' " . (isset($_GET['id']) && $qy['state_name'] == $rows['id'] ? 'selected' : '') . " code='" . $rows['state_code'] . "' >$rows[state_name]</option>";
                  }
                  ?>
               </select>
            </div>
            <div class="col-2 ">
               <label for="state_code">StateCode</label><br>
               <input class="form-control" type="text" readonly name="state_code" id="state_code" />
            </div>
            <div class="col-2  ">
               <label for="country">Country</label><br>
               <input oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" name="country" id="country" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" value="<?= isset($_GET['id']) ? $qy['country'] : ""; ?>" required />
            </div>
         </div>

         <!-- gstpan -->

         <div class="row mt-2">
            <div class="col-2">
               <label for="gst">GST No</label><br>
               <input class="form-control" type="text" maxlength="15" onchange="validateGst()" name="gst" id="gstn" required pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" onchange="validategst();" value="<?= isset($_GET['id']) ? $qy['gst'] : ""; ?>" /> <br>
               <span id="gsterror"></span>
            </div>
            <div class="col-2">
               <label for="pan">PAN No</label><br>
               <input class="form-control" type="text" name="pan" id="pan" value="<?= isset($_GET['id']) ? $qy['pan'] : ""; ?>" maxlength="10" readonly />
            </div>
         </div>

         <!-- submit -->
         <div class="row justify-content-center">
            <div class="col-2">
               <button id="submit" class="btn btn btn-success" type="submit" name="submit">Submit</button>
            </div>

      </form>
   </div>


</body>

<script>

   function getStateCode() {
      var state = document.querySelector("#state");
      var code = state.options[state.selectedIndex].getAttribute('code');

      document.getElementById("state_code").value = code;

   }

   function validateGst() {
      var gst = document.getElementById('gstn').value;
      var code = document.getElementById("state_code").value;
      if (gst.slice(0, 2) == code) {
         document.getElementById("pan").value = gst.slice(2, 12);
         document.getElementById("gsterror").innerHTML = "";
      } else {
         if (gst) {
            document.getElementById("gsterror").innerHTML = "Invalid";
         }

         document.getElementById("gstn").value = "";
         document.getElementById("pan").value = "";
      }
   }
</script>

</html>