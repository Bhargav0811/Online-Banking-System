<?php
ini_set('date.timezone', 'Asia/Manila');
date_default_timezone_set('Asia/Manila');
error_reporting(E_ERROR | E_PARSE);
echo "destroyed";
if (session_status() !== PHP_SESSION_NONE) {
  echo "destroyed";
}
require_once('config.php');
require_once('DB.php');
session_start();
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
$uid = $pass = "";
$uid_err = $pass_err = $err = "";
$flg = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // var_dump($_POST["uid"]);
  // var_dump($_POST["pass"]);
  if (empty(test_input($_POST["uid"]))) {
    $uid_err = "Please enter User ID";
    $flg = false;
  }
  if (empty(test_input($_POST["pass"]))) {
    $pass_err = "Please enter password";
    $flg = false;
  }
  if ($flg) {
    $uid = test_input($_POST["uid"]);
    $pass = test_input($_POST["pass"]);
    // var_dump($uid, $pass);
    $db = new DBConnection;
    $conn = $db->conn;
    $sql = "SELECT PASSWORD,EMAIL FROM `accounts` WHERE User_ID='" . $uid . "'; ";
    $res = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($res);
    $err_msg = "Invalid Credentails";

    // print_r(mysqli_fetch_assoc($res)) ;
    if ($num != 0) {
      $orgpass = mysqli_fetch_assoc($res);
      // var_dump($orgpass);
      var_dump($orgpass["EMAIL"]);
      $curr_email = $orgpass["EMAIL"];
      $orgpass = $orgpass["PASSWORD"];

      if (strcmp($pass, $orgpass) == 0) {

        unset($_SESSION["uid"]);
        unset($_SESSION["pass"]);
        unset($_SESSION["otp"]);
        include 'otp.php';
        // $code = 1;
        $_SESSION["otp"] = $code;
        $_SESSION["uid"] = $uid;
        $_SESSION["pass"] = $pass;
        session_commit();

        header("Location: " . base_url . "verf.php");
        // session_write_close();
        exit;
      } else {
        $err = "Invalid Credentials";
      }
    } else {
      echo '<div class="alert error">
      <input type="checkbox" id="alert1"/>
      <label class="close" title="close" for="alert1">
      <i class="icon-remove"></i>
      </label>
      <p class="inner"> ' . $err_msg . '
      </p>
      </div>';
    }
    mysqli_close($conn);
  }


  $uid = $_POST["uid"];
  $pass = $_POST["pass"];
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
                        <input type="text" name="uid" value="<?php echo $uid; ?>" placeholder="User ID"></input>
                            <?php if (isset($uid_err))
                              echo "<span class='error'>$uid_err</span>"; ?>
                        </div>
                        
                    </div>
                    <div class="form-row">
                        
                        <div class="form-group">
                        <input type="password" name="pass" value="<?php echo $pass; ?>" placeholder="Password"></input>
                            <?php if (isset($pass_err))
                              echo "<span class='error'>$pass_err</span>"; ?>
                        </div>
                    </div>
                    <button class="submitBTN btn btn-info" type="submit" value="Login">Login</button>
    </form>
    <div class="links">
      <a style="justify-content:left;" href="register.php" >Register</a>
      <br>
      <a style="justify-content:right;" href="#">Forgot Password?</a>
    </div>
  </div>
</body>

</html>