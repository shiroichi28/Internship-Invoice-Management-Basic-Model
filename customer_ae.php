<?php
include('db_conn.php');
include("functions.php");
login_check();
if (isset($_SESSION['user_name'])) {
} else {
   header("location:loginf.php");
}
//For Customer Code
$temp = mysqli_fetch_array(mysqli_query($conn, "select count(id) as cnt from `customer`"));
$nos = $temp['cnt'] + 1;
$nos = $nos - 1;
//Display
$fetch_state = fetch_state($conn);
if (isset($_GET['id'])) {
   $id = $_GET['id'];
   $from = 'customer';
   $init = creator($id, $from, $conn);
   $qy = existValues($id, $from, $conn);
}
if (isset($_GET['fy'])) {
   $financial_year = $_GET['fy'];
   
}
//AE Part
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
   $gst_type = $_POST['gst_type'];
   $party_type = $_POST['party_type'];
   $edited_by = $_SESSION["login"];
   $edited_on = (strtotime(date('Y-m-d H:i:s')));
   $suspend = $_POST['suspend'];
   $date = (strtotime(date('Y-m-d H:i:s')));
   if (!(isset($_GET['id']))) {
      $query = "INSERT INTO customer(customer_name,customer_sname,addr_1,addr_2,addr_3,city,pincode,state_name,state_code,country,gst,pan,created_on,created_by,area,cus_no,gst_type,party_type) 
                VALUES('$cname' ,'$sname', '$addr_1','$addr_2' ,'$addr_3','$city' , '$pincode' , '$state','$state_code','$country','$gstn' , '$pan'  ,'$date','$l','$area', '$numbers[$nos]','$gst_type','$party_type')";
      mysqli_query($conn, $query);

      header('location:customer.php?fy='.$financial_year.'');
   } else {
      mysqli_query($conn, "update `customer` set customer_name='$cname',customer_sname='$sname',addr_1='$addr_1',addr_2='$addr_2',addr_3='$addr_3',city='$city',pincode='$pincode',state_name='$state',state_code='$state_code',country='$country',gst='$gstn',pan='$pan',edited_by='$edited_by',edited_on='$edited_on',area='$area',gst_type='$gst_type',party_type='$party_type' ,suspend='$suspend' where id='$id'");
      header('location:customer.php?fy='.$financial_year.'');
   }
}
?>
<!-- html -->
<!DOCTYPE html>
<html lang="en">

<head>
   <meta name="description" content="Customer Add or Edit Page ">
   <title>Customer AE</title>
   <?php include "./links.php" ?>

</head>

<body <?= isset($_GET['id']) ? 'onload="getStateCode();select_type();"' : ''; ?>>
   <header>
      <?php include "./header.php" ?>
   </header>
   <div class="container-fluid mt-2">
      <div class="row">
         <div class="col-2">
            <a href="customer.php?fy=<?= $financial_year ?>"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
         </div>
         <div class="col-2" style="margin-left:-120px;">
            <h1>Customer</h1>
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
               <input type="text" id="created_on" value="<?= $init['created_on'] ?>" class="form-control" readonly>
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
   <div class="container mt-5 ">
      <form action="" method="post" autocomplete="off">
         <div class="row">
            <?php if (isset($_GET['id'])) { ?>
               <div class="col-4 col-xs-2">
                  <label for="cus_no">Customer No</label><br>
                  <input type="text" value="<?= isset($_GET['id']) ? $qy['cus_no'] : ""; ?>" name="cusno" id="cus_no" readonly class="form-control" /> <br>
               </div>
            <?php } ?>

            <div class="col-4 col-xs-2">
               <label for="cname">Customer Name</label><br>
               <input oninput="this.value = this.value.toUpperCase()" class="form-control" type="text" value="<?= isset($_GET['id']) ? $qy['customer_name'] : ""; ?>" name="cname" id="cname " onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" required /> <br>
            </div>
            <div class="col-4 col-xs-2">
               <label for="sname">Short Name</label><br>
               <input class="form-control" type="text" name="sname" oninput="this.value = this.value.toUpperCase()" value="<?= isset($_GET['id']) ? $qy['customer_sname'] : ""; ?>" id="sname" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" required />
            </div>

         </div>
         <!-- address -->
         <div class="row ">
            <div class="col-4 col-xs-2">
               <label for="addr_1">Address 1</label><br>
               <input type="text" class="form-control" value="<?= isset($_GET['id']) ? $qy['addr_1'] : ""; ?>" oninput="this.value = this.value.toUpperCase()" name="addr_1" id="addr_1 " required /> <br>
            </div>
            <div class="col-4 col-xs-2">
               <label for="addr_2">Address 2</label><br>
               <input type="text" class="form-control" name="addr_2" id="addr_2" value="<?= isset($_GET['id']) ? $qy['addr_2'] : ""; ?>" oninput="this.value = this.value.toUpperCase()" />
            </div>
            <div class="col-4 col-xs-2">
               <label for="addr_3">Address 3</label><br>
               <input type="text" class="form-control" name="addr_3" id="addr_3" value="<?= isset($_GET['id']) ? $qy['addr_3'] : ""; ?>" oninput="this.value = this.value.toUpperCase()" />
            </div>
         </div>
         <!-- code -->
         <div class="row mt-2">
            <div class="col-2 col-xs-2 ">
               <label for="area">Area</label><br>
               <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control" name="area" id="area " onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" value="<?= isset($_GET['id']) ? $qy['area'] : ""; ?>" required /> <br>
            </div>
            <div class="col-2 col-xs-2">
               <label for="city">City</label><br>
               <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control" name="city" id="city" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" value="<?= isset($_GET['id']) ? $qy['city'] : ""; ?>" required />
            </div>
            <div class="col-2 col-xs-2">
               <label for="pincode">Pincode</label><br>
               <input type="number" class="form-control" max="999999" min="0" name="pincode" pattern="^[0-9]{6,6}$" id="pincode" onkeypress="return(event.charCode > 47 && event.charCode <= 57)" required value="<?= isset($_GET['id']) ? $qy['pincode'] : ""; ?>" />
            </div>

            <div class="col-2 col-xs-2">
               <label for="state">State</label><br>
               <select name="state" id="state" class="form-select" onchange="getStateCode();validateGst();" required>
                  <option value="">Select</option>
                  <?php

                  while ($rows = mysqli_fetch_array($fetch_state)) {

                     echo "<option value='$rows[id]' " . (isset($_GET['id']) && $qy['state_name'] == $rows['id'] ? 'selected' : '') . "  code='" . $rows['state_code'] . "'>$rows[state_name]</option>";
                  }
                  ?>
               </select>
            </div>
            <div class="col-2 col-xs-1">
               <label for="state_code">StateCode</label><br>
               <input type="text" class="form-control" readonly value="<?= isset($_GET['id']) ? $qy['state_code'] : ""; ?>" name="state_code" id="state_code" />
            </div>
            <div class="col-2 col-xs-2">
               <label for="country">Country</label><br>
               <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control" name="country" id="country" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" value="<?= isset($_GET['id']) ? $qy['country'] : ""; ?>" required />
            </div>
         </div>
         <!-- gstpan -->

         <div class="row mt-2">

            <div class="col-3">
               <label for="gst">GST Type</label><br>
               <select name="gst_type" onchange="select_type();" id="gst_type" class="form-select" required>
                  <option value="">Select</option>
                  <option value="1" <?= (isset($_GET['id']) && $qy['gst_type'] == 1 ? 'selected' : '') ?>>Regular</option>
                  <option value="2" <?= (isset($_GET['id']) && $qy['gst_type'] == 2 ? 'selected' : '') ?>>Unregister</option>
                  <option value="3" <?= (isset($_GET['id']) && $qy['gst_type'] == 3 ? 'selected' : '') ?>>Consumer</option>
                  <option value="4" <?= (isset($_GET['id']) && $qy['gst_type'] == 4 ? 'selected' : '') ?>>Composition</option>
               </select>
            </div>

            <div class="col-3 col-xs-1 " id="party_type" style="visibility: hidden;">
               <label for="part_type">Party Type</label><br>
               <select name="party_type" id="part_type" class="form-select">
                  <option value="">Select</option>
                  <option value="1" <?= (isset($_GET['id']) && $qy['party_type'] == 1 ? 'selected' : '') ?>>Regular</option>
                  <option value="2" <?= (isset($_GET['id']) && $qy['party_type'] == 2 ? 'selected' : '') ?>>Deemed Export</option>
                  <option value="3" <?= (isset($_GET['id']) && $qy['party_type'] == 3 ? 'selected' : '') ?>>Embassy/Unbody</option>
                  <option value="4" <?= (isset($_GET['id']) && $qy['party_type'] == 4 ? 'selected' : '') ?>>SEZ</option>
               </select>
            </div>

            <div class="col-3 col-xs-2">
               <label for="gst">GST No</label><br>
               <input type="text" maxlength="15" oninput="this.value = this.value.toUpperCase()" name="gst" class="form-control" onblur="validateGst();" id="gstn" required pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" value="<?= isset($_GET['id']) ? $qy['gst'] : ""; ?>" /> <br>
               <span id="gsterror"></span>
               <span id="gst_avail"></span>
            </div>
            <div class="col-3 col-xs-2">
               <label for="pan">PAN No</label><br>
               <input type="text" name="pan" id="pan" oninput="this.value = this.value.toUpperCase()" class="form-control" value="<?= isset($_GET['id']) ? $qy['pan'] : ""; ?>" maxlength="10" readonly />
            </div>
         </div>
         <!-- Suspend -->
         <?php if (isset($_GET['id'])) { ?>
            <div class="row">
               <label for="r1" class=""> Suspend</label><br>

               <label class="radio-inline" for="r1"><input type="radio" name="suspend" value="0" checked="checked" <?= (isset($_GET['id']) && $qy['suspend'] == 0 ? 'checked' : '') ?>id="r1">No</label>
               <label class="radio-inline" for="r2"><input class="form-check-input" type="radio" name="suspend" <?= (isset($_GET['id']) && $qy['suspend'] == 1 ? 'checked' : '') ?> value="1" id="r2">Yes</label>
            </div>
         <?php   } ?>
         <!-- submit -->
         <div class="row justify-content-center mt-5">
            <div class="col-2">
               <button id="submit" class="btn btn btn-success" type="submit" name="submit">Submit</button>
            </div>
         </div>
      </form>
   </div>


   <script type="text/Javascript">
      function getStateCode(){
     var state = document.querySelector("#state");
var code = state.options[state.selectedIndex].getAttribute('code');

document.getElementById("state_code").value=code;

}
function validateGst(){
    <?php if (!isset($_GET['id'])) { ?> 
               var get_id=0;
               <?php } else { ?>
               var get_id='<?= $_GET['id'] ?>';
               <?php } ?>
               var empty_gst = document.getElementById('gstn').value;
   if(empty_gst){
     jQuery.ajax({
         url: "is_gst_exist.php",
         data:'gst='+$("#gstn").val()+'&get_edit_id='+get_id,
         type: "POST",
         success:function(data){
         console.log(data)
         
         if(data!=0){
         document.getElementById('gsterror').innerHTML="Already Exist";
         document.getElementById('gstn').value="";
         document.getElementById('pan').value="";
          
         }else{
       document.getElementById('gsterror').innerHTML="";
          var gst=document.getElementById('gstn').value;
         var code=document.getElementById("state_code").value;
           if(gst.slice(0, 2)==code){
         document.getElementById("pan").value=gst.slice(2,12);
           document.getElementById("gsterror").innerHTML = "";
         }else{
        document.getElementById("gsterror").innerHTML = "Invalid Gst";
         document.getElementById("gstn").value="";
         document.getElementById("pan").value="";
          }
         }
         
         
         }
         })
      }
  
}

function select_type(){
   var gst_type=document.getElementById('gst_type').value;
   if(gst_type==1){
      document.getElementById('party_type').style.visibility="visible";
      document.getElementById('part_type').required=true;
   }else{
       document.getElementById('party_type').style.visibility="hidden";
        document.getElementById('part_type').required=false;
   }
   if(gst_type==2){
      document.getElementById('gstn').required=false;
   }else{
      document.getElementById('gstn').required=true;
   }
}
</script>
</body>

</html>