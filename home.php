<?php
ini_set('date.timezone', 'Asia/Manila');
date_default_timezone_set('Asia/Manila');
error_reporting(E_ERROR | E_PARSE);
session_start();

require_once('config.php');
require_once('DB.php');
$db = new DBConnection;
$conn = $db->conn;

// $uid = "";

// if(!isset($_SESSION["uid"]))
// {
//     header("Location: ".base_url."login.php");
// }

$uid = $_SESSION["uid"];
$account_number = $_SESSION["account_number"];
$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];
$pass = $_SESSION["pass"];

$balance = 0;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    session_commit();
    // $uid = $_SESSION["uid"];
    // $account_number = $_SESSION["account_number"];
    // $first_name = $_SESSION["first_name"];
    // $last_name = $_SESSION["last_name"];
    // $pass = $_SESSION["pass"];
    // echo "Password : $pass ";



    // echo $uid . $account_number . $first_name . $last_name;
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $uid = $_SESSION["uid"];
    // $account_number = $_SESSION["account_number"];
    // $first_name = $_SESSION["first_name"];
    // $last_name = $_SESSION["last_name"];
    // $pass = $_SESSION["pass"];
    if (empty($_POST)) {
        // include 'otp.php';
        $code = "524152";
        // $code = "1";
        $_SESSION["otp_p"] = $code;


    } else {

        if ($_POST["form_name"] == "Remove_b") {
            //After Remove Button Pressed
            $b_number = $_POST["b_number"];
            // - User entered 

            $sql = "SELECT  *FROM `beneficiary` WHERE `to_ID` = '" . $b_number . "'; ";
            $res = mysqli_query($conn, $sql);
            $num = $res->num_rows;

            if ($num != 0) {
                // $rowdata = mysqli_fetch_assoc($res);
                $sql1 = "DELETE FROM `beneficiary` WHERE owner_ID= '" . $account_number . "' AND to_ID= '" . $b_number . "';";
                mysqli_query($conn, $sql1);
                // echo "Beneficiary Deleted";
                echo '<div class="alert success">
                <input type="checkbox" id="alert2"/>
                <label class="close" title="close" for="alert2">
                <i class="icon-remove"></i>
                </label>
                <p class="inner">Beneficiary Deleted Successfully
                </p>
                </div>';
            } else {
                $b_add_err = "Beneficiary account number not found";
                // echo "Beneficiary account number not found";
                echo '<div class="alert error">
                <input type="checkbox" id="alert1"/>
                <label class="close" title="close" for="alert1">
                <i class="icon-remove"></i>
                </label>
                <p class="inner"> ' . $b_add_err . '
                </p>
                </div>';
            }
            //$b_remove_err - error String

        } else if ($_POST["form_name"] == "Add_b") {
            //After Add Button Pressed
            $b_number = $_POST["b_number"];

            // - User entered 
            $sql = "SELECT `User_ID` FROM `accounts` WHERE `account_number` = '" . $b_number . "'; ";
            $res = mysqli_query($conn, $sql);
            $num = $res->num_rows;

            if ($num != 0) {
                // $rowdata = mysqli_fetch_assoc($res);
                $sql1 = "INSERT INTO `beneficiary` (`owner_ID`, `to_ID`) VALUES ('" . $account_number . "','" . $b_number . "');";
                mysqli_query($conn, $sql1);
                // echo "Beneficiary Added";
                echo '<div class="alert success">
                <input type="checkbox" id="alert2"/>
                <label class="close" title="close" for="alert2">
                <i class="icon-remove"></i>
                </label>
                <p class="inner">
                Beneficiary Added
                </p>
                </div>';
            } else {
                $b_add_err = "Account number not found";
                // echo "Account number not found";
                echo '<div class="alert error">
                <input type="checkbox" id="alert1"/>
                <label class="close" title="close" for="alert1">
              <i class="icon-remove"></i>
                </label>
                <p class="inner"> ' . $b_add_err . '
                </p>
              </div>';
            }
            //$b_add_err - error String

        } else if ($_POST["form_name"] == "transaction-form") {


            $curr_pass = $_POST["pass"];
            $curr_otp = $_POST["otp"];
            $org_otp = $_SESSION["otp_p"];
            // echo "C-$curr_pass  O-$pass C-$curr_otp  O-$org_otp";
            if ($curr_pass == $pass && $curr_otp == $org_otp) {
                $transfer_account = $_POST["transfer_acc_number"];
                $transfer_balance = $_POST["transfer_balance"];
                $sql = "SELECT balance from accounts where account_number='" . $account_number . "'; ";
                $res = mysqli_query($conn, $sql);
                $num = $res->num_rows;
                if ($num != 0) {
                    $rowdata = mysqli_fetch_assoc($res);
                    $balance = $rowdata["balance"];
                }
                $balance = $balance - $transfer_balance;
                $reciver_balance = 0;
                $sql = "UPDATE `accounts` SET `balance` = '" . $balance . "' WHERE `accounts`.`account_number` = '" . $account_number . "'; ";
                mysqli_query($conn, $sql);
                $sql = "SELECT balance from accounts where account_number='" . $transfer_account . "'; ";
                $res = mysqli_query($conn, $sql);
                $num = $res->num_rows;
                if ($num != 0) {
                    $rowdata = mysqli_fetch_assoc($res);
                    $reciver_balance = $rowdata["balance"];
                }
                $reciver_balance = $reciver_balance + $transfer_balance;
                $sql = "UPDATE `accounts` SET `balance` = '" . $reciver_balance . "' WHERE `accounts`.`account_number` = '" . $transfer_account . "'; ";
                mysqli_query($conn, $sql);


                $query = "SELECT * FROM transaction;";

                $result = mysqli_query($conn, $query);
                $userst = "TID";

                if ($result->num_rows == 0) {
                    $TransID = $userst . "0000001";
                } else {
                    $tid_array = array();
                    while ($row = $result->fetch_assoc()) {
                        array_push($tid_array, $row["transaction_ID"]);
                    }
                    rsort($tid_array);
                    $temp_tid = (int) substr($tid_array[0], 3) + 1;
                    $temp_tid = sprintf("%07d", $temp_tid);
                    $TransID = $userst . $temp_tid;
                }



                $sql = "INSERT INTO `transaction`(`transaction_ID`, `account_number`, `neighbour_account_number`, `type`, `amount`) VALUES ('" . $TransID . "','" . $account_number . "','" . $transfer_account . "','1','" . $transfer_balance . "')";
                mysqli_query($conn, $sql);
                $sql = "INSERT INTO `transaction`(`transaction_ID`, `account_number`, `neighbour_account_number`, `type`, `amount`) VALUES ('" . $TransID . "','" . $transfer_account . "','" . $account_number . "','0','" . $transfer_balance . "')";
                mysqli_query($conn, $sql);

                // echo "Transfered Success";
                echo '<div class="alert success">
                <input type="checkbox" id="alert2"/>
                <label class="close" title="close" for="alert2">
                <i class="icon-remove"></i>
                </label>
                <p class="inner">
                Transaction Successfull!
                </p>
                </div>';

            }

            // account_number: 12345
            // account_id: 4
            // current: 49000
            // balance: 50000





            //After Data is verfied 



        } else if ($_POST["form_name"] == "account-form") {
            //deposite balance
            $curr_pass = $_POST["pass"];
            $curr_otp = $_POST["otp"];
            $org_otp = $_SESSION["otp_p"];

            if ($curr_pass == $pass && $curr_otp == $org_otp) {
                //After Password and OTP verified

                $deposit_balance = $_POST["deposit_balance"];

                $sql = "SELECT balance from accounts where account_number='" . $account_number . "'; ";
                $res = mysqli_query($conn, $sql);
                $num = $res->num_rows;
                if ($num != 0) {
                    $rowdata = mysqli_fetch_assoc($res);
                    $balance = $rowdata["balance"];

                    $balance=$balance+$deposit_balance;

                    $sql1 = "UPDATE `accounts` SET `balance` = '" . $balance . "' WHERE `accounts`.`account_number` = '" . $account_number . "'; ";
                    mysqli_query($conn, $sql1);

                    echo '<div class="alert success">
                    <input type="checkbox" id="alert2"/>
                    <label class="close" title="close" for="alert2">
                    <i class="icon-remove"></i>
                    </label>
                    <p class="inner">Balance Updated
                    </p>
                    </div>';
                }
            }
            // account_id: 4
            // current: 49000
            // transfer_number: 1425
            // balance: 13999


        }
    }

}
?>


<html>
<title>Online Banking System</title>

<head>
    <script src="https://kit.fontawesome.com/6bf4d6296c.js" crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/style_alert.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="Image/chase.jpg">
</head>

<body>
    <?php include 'header.php' ?>
    <div id="home_div" class="home_container">
        <div class="slider">
            <div class="slideimg">
                <img src="Image/i1.jpg">
                <img src="Image/i2.jpg">

            </div>
        </div>
        <br>
        <div id="aboutus" class="about"><span>About Us</span><br><br>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
                into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
                release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
        </div>

        <div class="disclaimer">
            <span>Disclaimer !!</span><br><br>
            <p>Our bank does not ask for the details of your account/PIN/password. Therefore any one pretending to be
                asking you for information from the bank/technical team may be fraudulent entities, so please beware.
            </p>
            <p>You should know how to operate net transactions and if you are not familiar you may refrain from doing
                so. You may seek bank's guidance in this regard. Bank is not responsible for online transactions going
                wrong.</p>
            <p>We shall also not be responsible for wrong transactions and wanton disclosure of details by you. Viewing
                option and transaction option on the net are different. You may exercise your option diligently.</p>
        </div>
        <br>
        <div id="aboutus" class="about"><span>About Us</span><br><br>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
                into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
                release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>

        </div>

        
    </div>
    <div class="func" id="deposit_div">
    <section class="content  text-dark">
          <div class="container-fluid">
            <div class="card card-outline card-primary">
    <div class="card-header">
    <h3 class="card-title">Deposit</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="account-form" action="<?php echo base_url; ?>home.php"  method="post">
                <div class="form-group">
                    <label class="control-label">Account Number</label>
                    <input type="text" class="form-control col-sm-6" name="account_number" value="<?php echo $account_number; ?>" readonly="" autocomplete="off">
                    <input type="hidden" value="4" name="account_id">
                    <input type="hidden" value="49000" name="current">
                </div>
                <div class="form-group">
                <?php
                // $db = new DBConnection;
                // $conn = $db->conn;
                $sql = "SELECT balance from accounts where account_number='" . $account_number . "'; ";
                $res = mysqli_query($conn, $sql);
                $num = $res->num_rows;
                if ($num != 0) {
                    $rowdata = mysqli_fetch_assoc($res);
                    $balance = $rowdata["balance"];
                }
                ?>
                    <h4><b>Current Balance: <?php echo $balance; ?></b></h4>
                </div>
                <hr>
                <div class="form-group">
                    <label class="control-label">Deposit Amount</label>
                    <input type="number" step="any" min="0" class="form-control col-sm-6 text-right" name="deposit_balance" value="0" required="">
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex w-100">
            
            <a id="verf_display_link" value="account-form" form="account-form" class="verification_link dropdown-item btn btn-primary mr-2">Submit</a>
            <!-- <button  id="verf_display_link" form="account-form" class="btn btn-primary mr-2">Submit</button> -->
            <a href="<?php echo base_url; ?>home.php" class="btn btn-default">Cancel</a>
        </div>
    </div>
</div>
</div>
        </section>
    </div>

    <div class="func" id="verf_display_div">
    <section class="content  text-dark">
          <div class="container-fluid">
            <div class="card card-outline card-primary">
    <div class="card-header">
    <h3 class="card-title">Verification</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="verification-form" action="<?php echo base_url; ?>home.php" method="post">
                    <div class="form-row">
                        
                        <div class="form-group">
                        <input type="password" name="pass" placeholder="Password"></input>
                            <?php if (isset($pass_err))
                                echo "<span class='error'>$pass_err</span>"; ?>
                        </div>
                    </div>
                  <div class="form-row">
                        <div class="form-group">
                          
                        <input type="text" name="otp" placeholder="OTP"></input>
                            <?php if (isset($otp_err))
                                echo "<span class='error'>$otp_err</span>"; ?>
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <p>  </p>
                    </div>
                    <button class="submitBTN btn btn-info" type="submit">Verify</button>
            </form>
        </div>
    </div>
</div>
</div>
        </section>
    </div>

    <div class="func" id="transact_div">
    <section class="content  text-dark">
          <div class="container-fluid">
            <div class="card card-outline card-primary">
    <div class="card-header">
    <h3 class="card-title">Transact</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="transaction-form" method="post" action="#"  >
                <input type="hidden" name="id" value="">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="form-group">
                        <label class="control-label">Account Number</label>
                        <input type="text" class="form-control col-sm-6" name="account_number" value="<?php echo $account_number; ?>" readonly="" autocomplete="off">
                        <input type="hidden" value="4" name="account_id">
                        <input type="hidden" value="49000" name="current">
                    </div>
                    <div class="form-group">
                    <?php
                    // $db = new DBConnection;
                    // $conn = $db->conn;
                    $sql = "SELECT balance from accounts where account_number='" . $account_number . "'; ";
                    $res = mysqli_query($conn, $sql);
                    $num = $res->num_rows;
                    if ($num != 0) {
                        $rowdata = mysqli_fetch_assoc($res);
                        $balance = $rowdata["balance"];
                    }
                    ?>
                        <h4><b>Current Balance: <?php echo $balance; ?></b></h4>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Transfer To</label>
                            <!-- <input type="text" class="form-control col-sm-6" name="transfer_acc_number" value="" required="" autocomplete="off"> -->
                            <select name="transfer_acc_number">
                                <!-- <option></option> -->
                                <?php
                                $sql = "SELECT CONCAT(first_name,' ',last_name,' - ',account_number) as data from accounts where account_number IN ( SELECT to_ID FROM `beneficiary` b INNER JOIN accounts a WHERE a.account_number = b.owner_id AND a.account_number = '" . $account_number . "'); ";
                                $res = mysqli_query($conn, $sql);
                                $num = $res->num_rows;
                                if ($num != 0) {
                                    while ($rowdata = mysqli_fetch_assoc($res)) {
                                        $accn = substr($rowdata["data"], -10);
                                        echo "<option value='" . $accn . "'>" . $rowdata["data"] . "</option>";
                                    }
                                } else {
                                    echo "No Beneficary please add beneficrary to continue";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <hr>
                <div class="form-group">
                    <label class="control-label">Transfer Amount</label>
                    <input type="number" step="any" min="0" class="form-control col-sm-6 text-right" name="transfer_balance" value="0" required="true" min="100" max="<?php min($balance, 100000); ?>">
                </div>
            </form>
            
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex w-100">
            <!-- <button form="account-form" class="btn btn-primary mr-2">Submit</button>
         -->
            <!-- <a href="./?page=transaction" class="btn btn-default">Cancel</a> -->
            <a id="verf_display_link" value="transaction-form" form="account-form" class="verification_link dropdown-item btn btn-primary mr-2">Submit</a>
            <!-- <button  id="verf_display_link" form="account-form" class="btn btn-primary mr-2">Submit</button> -->
            <a href="<?php echo base_url; ?>home.php" class="btn btn-default">Cancel</a>
        </div>
    </div>
</div>
</div>
        </section>
    </div>





    <div class="func" id="graph_display_div">
    <section class="content  text-dark">
    <?php include 'Transaction/graph.php' ?>
        </section>
    </div>


    <div class="func" id="table_display_div">
        <section class="content  text-dark">
            <?php include 'Transaction/transaction_table.php' ?>
        </section>

    </div>

    <div class="func" id="manage_cards_div">
        <section class="content  text-dark">
            <?php include 'card_details.php' ?>
        </section>
    </div>
    
    <div class="func Binf_func" id="addB_div">
    <section class="content  text-dark">
          <div class="container-fluid">
            <div class="card card-outline card-primary">
    <div class="card-header">
    <h3 class="card-title">Add Beneficiary</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="#" method="post">
                    <input type = "hidden" name="form_name" value="Add_b">
                    <div class="form-row">
                        
                        <div class="form-group">
                        
                        <input type="text" name="b_number" placeholder="Account number of Beneficiary" required="required"></input>
                            
                        </div>
                        </div>
                    </div>
                 
                    <button class="submitBTN btn btn-info" type="submit">Add</button>
            </form>
        </div>
    </div>
</div>
</div>
        </section>
    </div>

    <div class="func Binf_func" id="removeB_div">
    <section class="content  text-dark">
          <div class="container-fluid">
            <div class="card card-outline card-primary">
    <div class="card-header">
    <h3 class="card-title">Remove Beneficiary</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="#" method="post">
                    <input type = "hidden" name="form_name" value="Remove_b">
                    <div class="form-row">
                        
                        <div class="form-group">
                        
                        <input type="text" name="b_number" placeholder="Account number of Beneficiary" required="required"></input>
                            
                        </div>
                        </div>
                    </div>
                 
                    <button class="submitBTN btn btn-info" type="submit">Remove</button>
            </form>
        </div>
    </div>
</div>
</div>
        </section>
    </div>
    
    <?php include 'footer.php'; ?>
    <script>
        $(".func").hide();
        // var active_div="#home.php";
        var active_div="#home_div";
        $('.dropdown-item').click(function () {
            if(active_div!=null)$(active_div).hide();
            var curr_id = $(this).attr("id");
            active_div = "#"+curr_id.substring(0, curr_id.lastIndexOf("_"))+"_div";
            console.log(active_div);
            $(active_div).show();
        });
        

        var account_form = $("#account-form");
        
        
        

        $('.verification_link').click(function () {
            const xhr = new XMLHttpRequest();
            const url = "<?php echo base_url; ?>home.php";
            const data = { "verification": 'start' };
            const json = JSON.stringify(data);
            console.log(json);
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            xhr.send(json);
            xhr.onload = function() {
            console.log(xhr.responseText);
            };
            console.log("dAta sent");

            var verf_form = $("#verification-form");
            var verf_form_html = verf_form.html();        
            var curr_form_data = new FormData(document.getElementById($(this).attr("value")))
            var curr_html = "<input type='hidden' name='form_name' value='"+$(this).attr("value")+"'>";
            
            for (const [key, value] of curr_form_data.entries()) {
                curr_html+="<input type='hidden' name='"+key+"' value='"+value+"'>";
            }
            verf_form.html(curr_html+verf_form_html);
        });

        



    </script>
</body>

</html>