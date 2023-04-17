<?php
include('db_conn.php');
include("functions.php");
login_check();
if (isset($_SESSION['user_name'])) {
} else {
    header("location:loginf.php");
}
if (isset($_GET['fy'])) {
    $financial_year = $_GET['fy'];
}
$financial_year_start_month = 4;
$current_month = date("n");

if ($current_month >= $financial_year_start_month) {
    $financial_year = date("Y");
} else {
    $financial_year = date("Y", strtotime("-1 year"));
}
$financial_year = substr($financial_year, 2);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "./links.php" ?>
    <title>Payment</title>
    <style>



    </style>

</head>

<body>
    <header>
        <?php include "./header.php" ?>
    </header>
    <div class="container-fluid">
        <div class="row justify-content-start">
            <div class="col-5" style="margin-top:2px;">
                <a href="payment.php"><button type="button" class="btn btn-warning"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
            <div class="col-2 text-center">
                <h1>Payment</h1>
            </div>

        </div>
    </div>
    <div class="container">
        <form action="" method="POST">
            <div class="card">
                <div class="card-header">
                    <label for="" style="color:red">Customer Details</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">

                            <label for="cus_name">Customer Name:</label>
                            <select name="cus_name" id="cus_name" onchange="check_Finy()" class="form-select" required>

                                <option value="">Select</option>
                                <?php
                                $query = mysqli_query($conn, "select * from `customer` where suspend=0 ORDER BY id DESC ");
                                while ($rows = mysqli_fetch_array($query)) {

                                    echo "<option cusid='$rows[cus_no]' ty='$rows[gst_type]' sc='$rows[state_code]'  cust_gst='$rows[gst]' value='$rows[customer_name]' " . (isset($_GET['id']) && $qy['cus_name'] == $rows['customer_name'] ? 'selected' : '') . ">$rows[customer_name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="inv_type">Invoice Type: </label><br>
                            <select id="inv_type" class="form-select" name="inv_type" onchange="check_Finy()" required>
                                <option value=""> - Select - </option>
                                <option value="1"> Sales Invoice </option>
                                <option value="2"> Estimate </option>
                            </select>
                        </div>
                        <div class="col-2">

                            <label for="">FY</label><br>
                            <input class="form-control " name="fy" id="fyy" readonly value="<?= $financial_year . "-" . $financial_year + 1; ?>" onchange="check_Finy();">



                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <label for="" style="color:red">Balance</label>
                </div>
                <div class="card-body">

                    <div class="row mt-3">
                        <div class="col-2">
                            <label for="">OS OP Balance</label><br>
                            <input class="form-control" type="number" id="opbal" readonly>
                        </div>
                        <div class="col-2">
                            <label for="">OS Invoice Total</label><br>
                            <input class="form-control" type="number" id="osinv" readonly>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <label for="" style="color:red">Payment</label>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-2">
                            <label for="">Date</label><br>
                            <input class="form-control" type="date" name="" id="pydate" min="" max="">
                        </div>
                        <div class="col-2">
                            <label for="">Entry For</label><br>
                            <select class="form-select" name="" id="">
                                <option value="">Invoice Payment</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="">Receiving Amount</label><br>
                            <input type="number" class="form-control" min="0" id="rec_amnt">
                        </div>

                        <div class="col-2">
                            <label for="">Difference</label><br>
                            <input type="number" name="" id="differ" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div id="inv_det">

            </div>



            <div class="row justify-content-center " style="margin-top:auto;">
                <div class="col-2">
                    <button class="btn btn-primary" type="submit" disabled>Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        //ajax for Sales inv & op Balance
        function check_Finy() {
            if ($("#cus_name").val() != "" && $("#inv_type").val() != "" && $("#fyy").val() != "") {
                jQuery.ajax({
                    url: "payment_ajax.php",
                    data: 'fin_year=' + $("#fyy").val() + '&cus_id=' + $("#cus_name").find(":selected").attr("cusid") + '&in_type=' + $("#inv_type").val(),
                    type: "POST",
                    dataType: 'JSON',
                    success: function(response) {
                        $("#opbal").val(response[0].op);
                        $("#osinv").val(response[0].inv_op);
                        $('#inv_det').html(response[0].table)

                    }
                })

            }
        }



        //set min and max date
        $(document).ready(function() {
            $("#cus_name").change(function() {

                var y = $("#fyy").val();
                var x = y.slice(0, 2);
                var z = y.slice(4);

                $("#pydate").attr("max", "20" + z + "-03-31");
                $("#pydate").attr("min", "20" + x + "-04-01");
            });
        });

        //set value in difference
        $(document).ready(function() {
            $("#rec_amnt").blur(function() {
                var amnt = $("#rec_amnt").val();
                var new_amnt = parseInt(amnt).toFixed(2);
                $("#differ").val(new_amnt);

            });
        });

        //check ckbox

        $(document).on('change', '.uncheck', function() {
            var org_id = $(this).attr("id");
            var slice_id = org_id.slice(4);
            if (this.checked) {

                $("#cur_" + slice_id + "").removeAttr("readonly");
                $("#cur_" + slice_id + "").attr("required", "true");

                //calculations
                $(document).on('blur', "#cur_" + slice_id + "", function() {
                    var cur_val = $("#cur_"+slice_id+"").val();
                    var differ = $("#differ").val();
                    $("#differ").val(differ - cur_val);
                 });

            } else {
                
                $("#cur_" + slice_id + "").val('');
                $("#cur_" + slice_id + "").attr("required", "false");
                $("#cur_" + slice_id + "").attr("readonly", "true");
                $("#pos_" + slice_id + "").val('');
            }
        });
        //calculation 
    </script>
</body>

</html>