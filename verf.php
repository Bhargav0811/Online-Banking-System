<?php
ini_set('date.timezone', 'Asia/Manila');
date_default_timezone_set('Asia/Manila');
error_reporting(E_ERROR | E_PARSE);
session_start();

require_once('config.php');
require_once('DB.php');
session_start();

if(!isset($_SESSION["uid"]))
{
    header("Location: ".base_url."login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>

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


  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
  <link rel="stylesheet" href="css/style_login.css?v=<?php echo time(); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700;800&display=swap" rel="stylesheet">
</head>
<?php
function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<?php

 $otp_p = $_SESSION["otp"];
  $uid_p = $_SESSION["uid"];
  $pass_p = $_SESSION["pass"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $uid_err = $pass_err = $err = "";
  $flg = true;

 if (empty(test_input($_POST["uid"]))) {
    $uid_err = "Please enter User ID";
    $flg = false;
  }
  if (empty(test_input($_POST["pass"]))) {
    $pass_err = "Please enter password";
    $flg = false;
  }
  if (empty(test_input($_POST["otp"]))) {
    $otp_err = "Please enter OTP";
    $flg = false;
  }
  if ($flg) {
    $uid = test_input($_POST["uid"]);
    $pass = test_input($_POST["pass"]);
    $otp = test_input($_POST["otp"]);
    $db = new DBConnection;
    $conn = $db->conn;
    $sql = "SELECT account_number, first_name,last_name,`password` FROM accounts WHERE User_ID = '" . $uid . "'; ";
    if (strcmp($otp, $otp_p) == 0) {
      $_SESSION["uid"] = $uid;
      $res=mysqli_query($conn,$sql);
      $num=$res->num_rows;
      if($num!=0)
      {
        $rowdata=mysqli_fetch_assoc($res);
        $_SESSION["account_number"]=$rowdata["account_number"];
        $_SESSION["first_name"]=$rowdata["first_name"];
        $_SESSION["last_name"]=$rowdata["last_name"];
        $_SESSION["pass"]=$rowdata["password"];

      }
      session_commit();
        header("Location: ".base_url."home.php");
      } else {
        $otp_err = "Invalid OTP";
      }
  }


  
}
else if($_SERVER["REQUEST_METHOD"] == "GET")
{
  session_commit();
 
}

?>

<body>
  <div id="olb">
    <div class="heading">
    <h2 class="formTitle">Online Banking Login</h2>
    </div>
    <hr>
    <br><br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                  <div class="form-row">
                        <div class="form-group">
                        <input type="text" name="uid" value="<?php echo $uid_p; ?>" placeholder="User ID"></input>
                            <?php if (isset($uid_err))
                              echo "<span class='error'>$uid_err</span>"; ?>
                        </div>
                        
                    </div>
                    <div class="form-row">
                        
                        <div class="form-group">
                        <input type="password" name="pass" value="<?php echo $pass_p ?>" placeholder="Password"></input>
                            <?php if (isset($pass_err))
                              echo "<span class='error'>$pass_err</span>"; ?>
                        </div>
                    </div>
                  <div class="form-row">
                        <div class="form-group">
                          <label>Please Enter OTP sent to your mail</label>
                        <input type="text" name="otp" placeholder="OTP"></input>
                            <?php if (isset($otp_err))
                              echo "<span class='error'>$otp_err</span>"; ?>
                        </div>
                        
                    </div>
                    
                    <button class="submitBTN btn btn-info" type="submit" value="Login">Verify OTP</button>
    </form>
    
  </div>
</body>

</html>

<?php session_destroy(); ?>