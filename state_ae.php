<?php include("db_conn.php");
include("functions.php");
login_check();
if (isset($_SESSION['user_name']) && $_SESSION['cid']) {
} else {
  header("location:loginf.php");
}
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $qy = mysqli_fetch_array(mysqli_query($conn, "select * from `state` where id=$id"));
  $created = $qy['created_by'];
  $create_on = date("d-m-Y H:i", (int) $qy['created_on']);
  $edited = $qy['edited_by'];
  if ($edited != 0) {
    $e = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$edited'"));
    $edit_on = date("d-m-Y H:i", (int) $qy['edited_on']);
  }
  $c = mysqli_fetch_array(mysqli_query($conn, "select username from `users` where id='$created'"));
}
//ADD
if (isset($_POST['submit'])) {

  $l = $_SESSION["login"];
  $state = $_POST['state'];
  $state_code = $_POST['state_code'];
  $date = (strtotime(date('Y-m-d H:i:s')));
  $suspend = $_POST['suspend'];
  $edited_by = $_SESSION["login"];
  $edited_on = (strtotime(date('Y-m-d H:i:s')));
  if ((!isset($_GET['id']))) {
    $query = "INSERT INTO state(state_name,state_code,created_by,created_on) 
                VALUES('$state' ,'$state_code' ,'$l','$date')";
    mysqli_query($conn, $query);

    header('location:state.php');
  } else {

    mysqli_query($conn, "update `state` set state_name='$state',state_code='$state_code',edited_by='$edited_by',edited_on='$edited_on',suspend='$suspend' where id='$id'");
    header("location:state.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
   
  <title>State</title>
  <?php include "./links.php" ?>
</head>

<body>
  <header>
    <?php include "./header.php" ?>
  </header>
  <div class="row mt-5">
    <div class="col-1">
      <a href="state.php"><button type="button" class="btn btn-warning" style="margin-left:20px;margin-top:5px "><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
    </div>
    <div class="col-2 px-5" style="margin-left:-80px;">
      <h1>state</h1>
    </div>
  </div>

  <?php if (isset($_GET['id'])) { ?>
    <div class="row justify-content-center" style="margin-top:20px;">
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
          <input type="text" id="edited_by" value="<?= $e[0] ?>" class="form-control" readonly>
        </div>
        <div class="col-2 col-xs-2">
          <label for="edited_on">Edited On</label><br>
          <input type="text" id="edited_on" value="<?= $edit_on ?>" class="form-control" readonly>
        </div> <?php } ?>

    </div><?php  } ?>
  <div class="row justify-content-center mt-5">
    <div class="col-4">


      <div class=" card " style="border: 2px solid coral;">
        <div class="card-body">
          <form autocomplete="off" action="" method="post" class="" style="margin-top:10%;">

            <div class="form-group">

              <label for="state"> State</label><br>
              <input class="form-control" type="text" value="<?= isset($_GET['id']) ? $qy['state_name'] : ""; ?>" name="state" id="state" oninput="this.value = this.value.toUpperCase()" onchange="state_name();" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123 || event.charCode==32)" required><br>
              <span id="state-availability-status"></span>
            </div>

            <div class="form-group">

              <label for="state_code">State Code</label><br>
              <input class="form-control" type="number" min="1" max="100" value="<?= isset($_GET['id']) ? $qy['state_code'] : ""; ?>" name="state_code" id="state_code" onchange="state_code_check();"> <br>
              <span id="sc-availability-status"></span>
            </div>

            <?php if (isset($_GET['id'])) { ?>
              <div class="col-4 py-2 " style="border:3px solid #ffcccb">
                <div class="form-group ">
                  <label for="r1"> Suspend</label><br>

                  <input class="form-check-input" type="radio" name="suspend" value="0" checked="checked" <?= (isset($_GET['id']) && $qy['suspend'] == 0 ? 'checked' : '') ?>id="r1">
                  <label class="form-check-label" for="r1">No</label>

                  <input class="form-check-input" type="radio" name="suspend" <?= (isset($_GET['id']) && $qy['suspend'] == 1 ? 'checked' : '') ?> value="1" id="r2" style="margin-left:20px;">
                  <label class="form-check-label" for="r2">Yes</label>
                </div>
              </div>


            <?php   } ?>


            <div class=" row justify-content-center" style="margin-top:40px;">
              <button type="submit" name="submit" class="btn btn-success">Submit</button>

            </div>

          </form>

        </div>
      </div>
    </div>
  </div>


  <script type="text/Javascript">
    function state_name() {
       
              <?php if (!isset($_GET['id'])) { ?> 
               var get_id=0;
               <?php } else { ?>
               var get_id='<?= $_GET['id'] ?>';
               <?php } ?>

         jQuery.ajax({
         url: "state_availability.php",
         data:'state='+$("#state").val()+'&get_edit_id='+get_id,
         type: "POST",
         success:function(data){
         
         if(data!=0){
         document.getElementById('state').value="";
         document.getElementById('state-availability-status').innerHTML="State Already Exist";
         }else{
         document.getElementById('state-availability-status').innerHTML="";
         }
         
         }
         })}
         function state_code_check() {
           <?php if (!isset($_GET['id'])) { ?> 
               var get_id=0;
               <?php } else { ?>
               var get_id='<?= $_GET['id'] ?>';
               <?php } ?>
         
         jQuery.ajax({
         url: "state_availability.php",
         data:'state_code='+$("#state_code").val()+'&get_edit_id='+get_id,
         type: "POST",
         success:function(data){
         
         
         if(data!=0){
         document.getElementById('state_code').value="";
         document.getElementById('sc-availability-status').innerHTML="Code Already Exist";
         }else{
         document.getElementById('sc-availability-status').innerHTML="";
         }
         
         
         }
         })
         }
</script>
</body>

</html>