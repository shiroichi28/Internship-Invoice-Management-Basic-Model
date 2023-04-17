<?php
include('db_conn.php');
include("functions.php");
login_check();
if (isset($_SESSION['user_name'])) {
} else {
  header("location:loginf.php");
}
//Display
if (isset($_GET['fy'])) {
  $financial_year = $_GET['fy'];
}
$l = $_SESSION["login"];
$date = (strtotime(date('Y-m-d H:i:s')));
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $qy = mysqli_fetch_array(mysqli_query($conn, "select * from `invoice` where id=$id"));
  $inv_daten = date("Y-m-d", (int) $qy['inv_date']);
  $created = $qy['created_by'];
  $create_on = date("d-m-Y H:i", (int) $qy['created_on']);
  $edited = $qy['edited_by'];
  $cancel_by = $qy['cancel_by'];
  if ($edited != 0) {
    $e = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$edited'"));
    $edit_on = date("d-m-Y H:i", (int) $qy['edited_on']);
  }
  $c = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$created'"));
  $cancel = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$cancel_by'"));
}

//AE Part

if (isset($_POST['submit'])) {
  $inv_type = $_POST['inv_type'];
  $cus_name = $_POST['cus_name'];
  $cus_gst = $_POST['cus_gst'];


  $inv_date = strtotime($_POST['inv_date']);
  $date_arr = explode("-", $_POST['inv_date']);

  $qty = ($_POST['qty']);
  $rate = ($_POST['rate']);
  $tax_amnt = ($_POST['tax_amnt']);
  $gst_percent = $_POST['gst_percent'];
  $gst_amount = ($_POST['gst_amnt']);
  $inv_total = ($_POST['inv_total']);
  $inv_bal = ($_POST['inv_total']);
  $cgst = ($_POST['cgst']);
  $sgst = ($_POST['sgst']);
  $igst = ($_POST['igst']);
  $cus_id = $_POST['cus_id'];
  $roff_sts = $_POST['roff_status'];
  $roff_value = $_POST['roff_val'];
  //Financial Year
  $year = substr($date_arr[0], 2);
  $month = $date_arr[1];
  if ($month < 4) {
    $prev_year = ($year - 1);
  } else {
    $prev_year = $year;
    $year = $year + 1;
  }

  $fy = $prev_year . '-' . $year;
  //Generate Invoice Code
  $cust_sn = mysqli_fetch_array(mysqli_query($conn, "SELECT short_name FROM `company`"));
  $i = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id) as cnt FROM `invoice` WHERE (fy='$fy') AND (inv_type=1)"));
  if ($inv_type == 1) {
    $inv_code = $cust_sn['short_name'] . '/' . $fy . '/' . str_pad(($i['cnt'] + 1), 4, "0", STR_PAD_LEFT);
  } else {
    $i = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(id) as cnt FROM `invoice` WHERE (fy='$fy') AND (inv_type=2) "));
    $inv_code = str_pad(($i['cnt'] + 1), 4, "0", STR_PAD_LEFT);
  }
  //Add
  if (!(isset($_GET['id']))) {
    $query = "INSERT INTO invoice(inv_id,cus_id,cus_name,inv_type,cus_gst,inv_date,d,m,y,fy,qty,rate,tax_amnt,gst_amnt,cgst,sgst,igst,inv_bal,roff_status,roff_value,created_by,created_on,gst_percent) 
                VALUES('$inv_code','$cus_id','$cus_name','$inv_type','$cus_gst','$inv_date','$date_arr[2]','$date_arr[1]','$date_arr[0]','$fy','$qty','$rate','$tax_amnt','$gst_amount','$cgst','$sgst','$igst','$inv_total','$roff_sts','$roff_value','$l','$date','$gst_percent')";
    mysqli_query($conn, $query);

    header("location:invoice.php?fy=" . $financial_year . " ");
  } else {
    $cancel = $_POST['cancel'];
    $cancel_reason = $_POST['cancelreason'];
    $id = $_GET['id'];
    if ($cancel == 0) {
      $query = "UPDATE invoice set cus_id='$cus_id', cus_name='$cus_name',inv_type='$inv_type',cus_gst='$cus_gst' ,d='$date_arr[2]',m='$date_arr[1]',y='$date_arr[0]',fy='$fy',qty='$qty',rate='$rate',tax_amnt='$tax_amnt',gst_amnt='$gst_amount',cgst='$cgst',sgst='$sgst',igst='$igst',inv_bal='$inv_total',roff_status='$roff_sts',roff_value='$roff_value',edited_by='$l',edited_on='$date',gst_percent='$gst_percent' WHERE id=$id";
      mysqli_query($conn, $query);

      header("location:invoice.php?fy=" . $financial_year . " ");
    } else {

      $query = "UPDATE invoice set cus_id='$cus_id',cus_name='$cus_name',inv_type='$inv_type',cus_gst='$cus_gst' ,d='$date_arr[2]',m='$date_arr[1]',y='$date_arr[0]',fy='$fy',qty='$qty',rate='$rate',tax_amnt='$tax_amnt',gst_amnt='$gst_amount',cgst='$cgst',sgst='$sgst',igst='$igst',inv_bal='$inv_total',roff_status='$roff_sts',roff_value='$roff_value',edited_by='$l',edited_on='$date',gst_percent='$gst_percent' ,cancel='$cancel', cancel_reason='$cancel_reason',cancel_by='$l',cancel_on='$date' WHERE id=$id";
      mysqli_query($conn, $query);

      header("location:invoice.php?fy=" . $financial_year . " ");
    }
  }
}
//For Company Details
$fetch_company = (mysqli_fetch_array(mysqli_query($conn, "select state_code from `company` ")));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "./links.php" ?>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="description" content="Add or Edit Part Of Invoice">
  <title>Invoice AE</title>
  <style>
    input[type=checkbox] {
      accent-color: red;
    }
  </style>

</head>

<body <?= isset($_GET['id']) ? 'onload="cancel_inv();getGst();invo_type();"' : "" ?>>

  <header>
    <?php include "./header.php" ?>
  </header>
  <div class="container-fluid mt-2">
    <div class="row">
      <div class="col-2">
        <a href="invoice.php?fy=<?= $financial_year ?>"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
      </div>
      <div class="col" style="margin-left:-14%;">
        <h1>Invoice</h1>
      </div>
    </div>
  </div>


  <?php if (isset($_GET['id'])) { ?>
    <div class="row " style="margin-top:-10px;margin-left:30%">
      <div class="col-2 col-xs-2">
        <label for="created_by">Created By</label><br>
        <input type="text" id="created_by" value="<?= $c['username'] ?>" class="form-control" readonly>
      </div>
      <div class="col-2 col-xs-2">
        <label for="created_on">Created On</label><br>
        <input type="text" id="created_on" value="<?= $create_on ?>" class="form-control" readonly>
      </div>
      <?php if ($edited != 0) { ?>
        <div class="col-2 col-xs-2">
          <label for="edited_by">Edited By</label><br>
          <input type="text" id="edited_by" value="<?= $e['username'] ?>" class="form-control" readonly>
        </div>
        <div class="col-2 col-xs-2">
          <label for="edited_on">Edited On</label><br>
          <input type="text" id="edited_on" value="<?= $edit_on ?>" class="form-control" readonly>
        </div> <?php } ?>

    </div>
  <?php  } ?>
  <div class="container">
    <form action="" method="post" autocomplete="off">

      <div class="row mt-2">
        <div class="col-2 ">
          <label for="inv_type">Invoice Type: </label><br>
          <select id="inv_type" class="form-control" name="inv_type" onchange="invo_type()" required>
            <option value=""> - Select - </option>
            <option value="1" <?= (isset($_GET['id']) && $qy['inv_type'] == 1 ? 'selected' : '') ?>> Sales Invoice </option>
            <option value="2" <?= (isset($_GET['id']) && $qy['inv_type'] == 2 ? 'selected' : '') ?>> Estimate </option>
          </select>
        </div>

      </div>

      <div class="row mt-2">
        <div class=" col-2 col-xs-2">

          <label for="cus_name">Customer Name:</label>
          <select name="cus_name" id="cus_name" class="form-control" onchange="getGst();" required>
            <option value="">Select</option>
            <?php
            $query = mysqli_query($conn, "select * from `customer` where suspend=0 ORDER BY id DESC ");
            while ($rows = mysqli_fetch_array($query)) {

              echo "<option cusid='$rows[cus_no]' ty='$rows[gst_type]' sc='$rows[state_code]'  cust_gst='$rows[gst]' value='$rows[customer_name]' " . (isset($_GET['id']) && $qy['cus_name'] == $rows['customer_name'] ? 'selected' : '') . ">$rows[customer_name]</option>";
            }
            ?>
          </select>
          <input type="text" id="odd" name="cus_id" style="display:none;">
        </div>
        <div class="col-2 col-xs-2">
          <label for="cus_gst">GST NO:</label>
          <input type="text" class="form-control" name="cus_gst" id="cus_gst" value="<?= isset($_GET['id']) ? $qy['cus_gst'] : ""; ?>" required readonly>
        </div>
        <div class="col-2 col-xs-2">
          <label for="inv_date">Invoice Date</label> <br>
          <input type="date" name="inv_date" max="<?= date("Y-m-d"); ?>" class="form-control" id="inv_date" required value="<?= isset($_GET['id']) ? $inv_daten : ""; ?>" <?= isset($_GET['id']) ? 'readonly' : '';     ?>>
        </div>

      </div>


      <div class="row mt-2">
        <div class="col-2 col-xs-2">
          <label for="qty">Quantity</label> <br>
          <input type="number" min="0" name="qty" class="form-control" id="qty" onblur="Taxable();" required step="any" value="<?= isset($_GET['id']) ? $qy['qty'] : ""; ?>">
        </div>
        <div class="col-2 col-xs-2">
          <label for="rate">Rate</label> <br>
          <input type="number" min="0" class="form-control" name="rate" onblur="Taxable();" id="rate" required value="<?= isset($_GET['id']) ? $qy['rate'] : ""; ?>">
        </div>

        <div class="col-2 col-xs-2">
          <label for="tax_amnt">Taxable Amount</label> <br>
          <input type="number" min="0" name="tax_amnt" id="tax_amnt" class="form-control" readonly value="<?= isset($_GET['id']) ? $qy['tax_amnt'] : ""; ?>">
        </div>
      </div>
      <div id=gststuff>
        <div class=" row mt-2 ">
          <div class="col-2 col-xs-2">
            <label for="gst_percent">GST%</label> <br>
            <input type="number" min="0" max="100" name="gst_percent" id="gst_percent" onblur="Taxable()" class="form-control" value="<?= isset($_GET['id']) ? $qy['gst_percent'] : ""; ?>" required>
          </div>

          <div class="col-1 col-xs-2">
            <label for="">CGST</label><br><input id="cgst" type="text" class="form-control" value="<?= isset($_GET['id']) ? $qy['cgst'] : ""; ?>" readonly name="cgst">
          </div>

          <div class=" col-1 col-xs-2"><label for="">SGST</label><br><input id="sgst" type="text" class="form-control" value="<?= isset($_GET['id']) ? $qy['sgst'] : ""; ?>" readonly name="sgst"></div>

          <div class="col-1 col-xs-2"><label for="">IGST</label><br><input id="igst" type="text" class="form-control" value="<?= isset($_GET['id']) ? $qy['igst'] : ""; ?>" readonly name="igst"></div>
          <div class="col-2 col-xs-2">
            <label for="gst_amnt">GST Amount</label> <br>
            <input type="number" name="gst_amnt" id="gst_amnt" readonly class="form-control" value="<?= isset($_GET['id']) ? $qy['gst_amnt'] : ""; ?>">
          </div>

          <div class="col-2 col-xs-2">
            <label for="inv_total">Invoice Total</label> <br>
            <input type="text" name="inv_total" id="inv_total" readonly class="form-control" value="<?= isset($_GET['id']) ? $qy['inv_bal'] : ""; ?>" required>
          </div>

          <div class="col-2">
            <div class="row">
              <div class="col-1">
                <label class="form-check-label" for="roff">ROF
                </label>
              </div>
              <div class="col-1">
                <input style="margin-left:30px;" type="checkbox" name="roff_status" value="1" id="roff" onclick="rad();" <?= ((isset($_GET['id'])) && ($qy['roff_status'] == 1)) ? 'checked' : ''; ?>>

              </div>

              <input type="text" class="form-control " value="<?= isset($_GET['id']) ? $qy['roff_value'] : ""; ?>" name="roff_val" id="ofv" readonly>
            </div>
          </div>

        </div>

      </div>

      <?php if (isset($_GET['id'])) { ?>
        <div class="row" style="margin-top: 20px;">
          <div class="col-2 col-xs-2">

            <label for="cancel">Cancel: </label><br>
            <select id="cancel" class="form-control" name="cancel" onchange="cancel_inv()" required>
              <option value=""> - Select - </option>
              <option value="0" <?= (isset($_GET['id']) && $qy['cancel'] == 0 ? 'selected' : '') ?>> No</option>
              <option value="1" <?= (isset($_GET['id']) && $qy['cancel'] == 1 ? 'selected' : '') ?>> Yes </option>
            </select>
          </div>

          <div class="col-2 col-sm-2">
            <div id="cancelinfo" style="visibility: hidden;">
              <label> Cancel Reason</label><br>
              <textarea name="cancelreason" id="txt" cols="30" rows="5"><?= isset($_GET['id']) ? $qy['cancel_reason'] : ''; ?></textarea>
            </div>
          </div>
        </div>

        <?php if ($qy['cancel'] != 0) { ?>
          <div class="row">

            <div class="col-2 col-xs-2">
              <label for="edited_by">Cancel By</label><br>
              <input type="text" id="cancel_by" value="<?= $cancel['username'] ?>" class="form-control" readonly>
            </div>
            <div class="col-2 col-xs-2">
              <label for="edited_on">Cancel On</label><br>
              <input type="text" id="cancel_on" value="<?= date("d-m-Y H:i", (int) $qy['cancel_on']); ?>" class="form-control" readonly>
            </div> <?php } ?>
          </div>
        <?php } ?>

        <!-- submit -->
        <div class="row  justify-content-center mt-3">
          <div class="col-2 col-xs-2">
            <button id="submit" class="btn btn btn-success" type="submit" name="submit">Submit</button>
          </div>
        </div>
    </form>
  </div>


  <script type="text/Javascript">

    function getGst(){
     var name = document.querySelector("#cus_name");
var code = name.options[name.selectedIndex].getAttribute('cust_gst');
var type=name.options[name.selectedIndex].getAttribute('ty');
document.getElementById('cus_gst').value=code;
var cusid=name.options[name.selectedIndex].getAttribute('cusid');
document.getElementById('odd').value=cusid;
}


function Taxable(){
// alert('k')
var quantity=document.getElementById('qty').value;
var rate=document.getElementById('rate').value;

if(quantity!=0 && rate!=0){
  //to Display in fixed format
document.getElementById('qty').value=Number(quantity).toFixed(3);
document.getElementById('rate').value=Number(rate).toFixed(2);
}
document.getElementById('tax_amnt').value=(quantity*rate).toFixed(2);
if(document.getElementById('inv_type').value==2){
document.getElementById('inv_total').value=(quantity*rate).toFixed(2);
}else{
var gstper=document.getElementById('gst_percent').value;
document.getElementById('gst_amnt').value=(((quantity*rate)*gstper)/100).toFixed(2);
var name = document.querySelector("#cus_name");
var code = name.options[name.selectedIndex].getAttribute('sc');
var type=name.options[name.selectedIndex].getAttribute('ty');
var gst_amnt=document.getElementById('gst_amnt').value;
var tax=document.getElementById('tax_amnt').value;
if(type==2){
  
  document.getElementById('cgst').value=Number(gst_amnt/2).toFixed(2);
  document.getElementById('sgst').value=Number(gst_amnt/2).toFixed(2);
  document.getElementById('igst').value=0.00;
  document.getElementById('inv_total').value=(Number(tax)+Number(gst_amnt)).toFixed(2);
}else{
  if(code==<?= $fetch_company['state_code'] ?>){
  document.getElementById('cgst').value=Number(gst_amnt/2).toFixed(2);
  document.getElementById('sgst').value=Number(gst_amnt/2).toFixed(2);
  document.getElementById('igst').value=0.00;
  document.getElementById('inv_total').value=(Number(tax)+Number(gst_amnt)).toFixed(2);
  }
  else{
  document.getElementById('cgst').value=0.00;
  document.getElementById('sgst').value=0.00;
  document.getElementById('igst').value=Number(gst_amnt).toFixed(2);
  document.getElementById('inv_total').value=(Number(tax)+Number(gst_amnt)).toFixed(2);

  }

}
}
rad();
}
function invo_type(){
  var sel=document.getElementById('inv_type').value;
  console.log(sel);
  if(sel==1){
document.getElementById('gststuff').style.visibility="visible";
    document.getElementById('gst_percent').required=true;
  }else{
    document.getElementById('gststuff').style.visibility="hidden";
    document.getElementById('gst_percent').required=false;
     document.getElementById('cgst').value="";
  document.getElementById('sgst').value="";
  document.getElementById('igst').value="";
  document.getElementById('inv_total').value=document.getElementById('tax_amnt').value;
   

  }
}
 
function cancel_inv() {
var select=document.getElementById('cancel').value;
if(select==1){
document.getElementById('cancelinfo').style.visibility="visible";
document.getElementById('txt').required=true;
}else{
document.getElementById('cancelinfo').style.visibility="hidden";
document.getElementById('txt').required=false;
}
}

function rad(){  
//  alert('k')
const init_inv=document.getElementById('inv_total').value;
if(document.getElementById('roff').checked==true){

  document.getElementById('inv_total').value=(Math.round(init_inv)).toFixed(2);
  temp=(document.getElementById('inv_total').value-init_inv).toFixed(2);
   
  document.getElementById('ofv').setAttribute('temp',temp);
 if(temp>0){
  document.getElementById('ofv').value='+'+document.getElementById('ofv').getAttribute('temp');
 }else{
  document.getElementById('ofv').value=document.getElementById('ofv').getAttribute('temp');
 }
}else{

  document.getElementById('inv_total').value=(document.getElementById('inv_total').value-document.getElementById('ofv').value).toFixed(2);
  document.getElementById('ofv').value='';
}
// Taxable();
}
 
</script>
</body>

</html>