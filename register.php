<?php
ini_set('date.timezone', 'Asia/Manila');
date_default_timezone_set('Asia/Manila');
session_start();

require_once('config.php');
require_once('DB.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <script src="https://kit.fontawesome.com/6bf4d6296c.js" crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
    <link rel="stylesheet" href="css/style_register.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/style_alert.css?v=<?php echo time(); ?>">
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

function getUidAcc(&$UserID, &$Account_number, &$conn)
{
    $query = "SELECT User_ID, account_number FROM accounts;";

    $result = mysqli_query($conn, $query);
    $dmy = date("Y") . date("m") . date("d");
    $userst = "UID";

    if ($result->num_rows == 0) {
        $Account_number = $dmy . "01";
        $UserID = $userst . "0000001";
    } else {
        $uid_array = array();
        $account_array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($uid_array, $row["User_ID"]);
            array_push($account_array, $row["account_number"]);
        }
        rsort($uid_array);
        rsort($account_array);
        $temp_uid = (int)substr($uid_array[0], 3) + 1;
        $temp_account = (int)substr($account_array[0], 8) + 1;
        $temp_uid = sprintf("%07d", $temp_uid);
        $temp_account = sprintf("%02d", $temp_account);
        $UserID = $userst . $temp_uid;
        $Account_number = $dmy . $temp_account;
    }
}
?>

<?php
$firstname = $lastname = $email = $phone = $address =  $deposit = $password = $confirm_password = $password_err = "";;
$firstname_err = $lastname_err = $email_err = $phone_err = $address_err = $accounttype_err = $deposit_err = "";
$accounttype = "-1";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(test_input($_POST["firstname"]))) {
        $firstname_err = "Please enter your first name.";
    }
    if (!preg_match("/^[a-zA-Z]+$/", $_POST["firstname"])) {
        $firstname_err = "Only letters are allowed.";
    } else {
        $firstname = test_input($_POST["firstname"]);
    }


    if (empty(test_input($_POST["lastname"]))) {
        $lastname_err = "Please enter your last name.";
    }
    if (!preg_match("/^[a-zA-Z]+$/", $_POST["lastname"])) {
        $lastname_err = "Only letters are allowed.";
    } else {
        $lastname = test_input($_POST["lastname"]);
    }


    if (empty(test_input($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } else {
        $email = test_input($_POST["email"]);

        // Check if email address is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email address format.";
        }
    }


    if (empty(test_input($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = test_input($_POST["phone"]);

        // Check if phone number is valid
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $phone_err = "Invalid phone number format.";
        }
    }


    if (empty(test_input($_POST["address"]))) {
        $address_err = "Please enter your permanent address.";
    } else {
        $address = test_input($_POST["address"]);
    }


    // if (empty(test_input($_POST["accounttype"])) || $_POST["accounttype"]==-1) {
    if ($_POST["accounttype"] == "-1") {
        $accounttype_err = "Please select an account type.";
    } else {
        $accounttype = test_input($_POST["accounttype"]);
    }

    /*
    if (empty(test_input($_POST["deposit"]))) {
        $deposit_err = "Please enter the deposit amount.";
    } elseif (!filter_var($_POST["deposit"], FILTER_VALIDATE_INT)) {
        $ageErr = "Invalid deposit format";
    } else {
        $deposit = intval($_POST["deposit"]);
        if ($deposit <= 0 || $deposit > 50000) {
            $deposit_err = "Deposit value should be between 0 to 50000.";
        }
    }*/

    $flg = true;
    if (empty($_POST["password"])) {
        $password_err = "Please enter a password.";
        $flg = false;
    } elseif (strlen($_POST["password"]) < 8) {
        $password_err = "Password must have at least 8 characters.";
        $flg = false;
    } elseif (!preg_match('/[A-Z]/', $_POST["password"])) {
        $flg = false;

        $password_err ="Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]+/', $_POST["password"])) {
        $flg = false;
        $password_err = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $_POST["password"])) {
        $flg = false;
        $password_err = "Password must contain at least one special character.";
    } elseif (!preg_match('/[0-9]/', $_POST["password"])) {
        $flg = false;
        $password_err = "Password must contain at least one number.";
    } else {
        $password = test_input($_POST["password"]);
    }


    if (empty($_POST["confirm_password"])) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = test_input($_POST["confirm_password"]);
        // if ($confirm_password != $password && (strcmp($confirm_password_err, "") && strcmp($password_err, "")) != 0) {
        if ($confirm_password != $password && $flg) {
            $confirm_password_err = "Password did not match.";
        }
    }
    if (empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($phone_err) && empty($address_err) && empty($accounttype_err) && empty($deposit_err)) {
        // echo "Success";
        // DB stuff
        $db = new DBConnection;
        $conn = $db->conn;

        $UserID = $Account_number = "";
        getUidAcc($UserID, $Account_number, $conn);
        $Account_number = (int)$Account_number;
        $phone = (int)$phone;
        $balance = 0;
        if ($accounttype == "0") {
            $accounttype = 0;
            $balance = 5000;
        } else {
            $accounttype = 1;
            $balance = 25000;
        }
        $sql = "INSERT INTO `accounts` (`User_ID`, `account_number`, `password`, `first_name`, `last_name`, `email`, `phone_number`, `balance`, `address`, `type`, `account_opening_date`) VALUES ('" . $UserID . "', '" . $Account_number . "', '" . $password . "', '" . $firstname . "', '" . $lastname . "', '" . $email . "', '" . $phone . "', '" . $balance . "', '" . $address . "', '" . $accounttype . "', current_timestamp());";
        $result = mysqli_query($conn, $sql);
        $succ = "You are Registerd Successfully!! Please Login to Continue.";
        $err_msg = "Some Error Occured. Please try again.";

        if ($result) {
            echo '<div class="alert success">
  				  <input type="checkbox" id="alert2"/>
  				  <label class="close" title="close" for="alert2">
    			  <i class="icon-remove"></i>
  				  </label>
  				  <p class="inner">
    				' . $succ . '
  				  </p>
				  </div>';
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
}
?>

<body>
    <div id="olb">
        <div class="heading">
            <h2 class="formTitle">Create New Account</h2>
        </div>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="main">
                <div class="formDiv divLeft">
                    <div class="form-row">
                        <div class="form-group">
                            <label class='formLabel'>First Name:</label>
                            <input type="text" id="firstName" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
                            <?php if (isset($firstname_err))
                                echo "<span class='error'>$firstname_err</span>"; ?>
                        </div>
                        <div class="form-group">
                            <label class='formLabel'>Last Name:</label>
                            <input type="text" id="lastName" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                            <?php if (isset($lastname_err))
                                echo "<span class='error'>$lastname_err</span>"; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class='formLabel'>Email:</label>
                            <input type="text" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <?php if (isset($email_err))
                                echo "<span class='error'>$email_err</span>"; ?>



                        </div>
                        <div class="form-group">
                            <label class='formLabel'>Phone Number:</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            <?php if (isset($phone_err))
                                echo "<span class='error'>$phone_err</span>"; ?>

                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class='formLabel'>Current Address:</label>
                            <input type="text" id="address" name="">

                        </div>
                        <div class="form-group">
                            <label class='formLabel'>Permanent Address:</label>
                            <input type="text" id="address" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                            <?php if (isset($address_err))
                                echo "<span class='error'>$address_err</span>"; ?>

                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class='formLabel'>Account Type:</label>
                            <select name="accounttype">
                                <option value="-1" <?php if ($accounttype == "-1") {
                                                        echo "selected";
                                                    } ?>>Select</option>
                                <option value="0" <?php if ($accounttype == "0") {
                                                        echo "selected";
                                                    } ?>>Saving Account</option>
                                <option value="1" <?php if ($accounttype == "1") {
                                                        echo "selected";
                                                    } ?>>Current Account</option>
                            </select>
                            <?php if (isset($accounttype_err))
                                echo "<span class='error'>$accounttype_err</span>"; ?>
                        </div>
                        <div class="form-group">
                            <label class='formLabel'>Gender:</label>
                            <select name="" id="">
                                <option value="" selected>Select</option>
                                <option value="">Male</option>
                                <option value="">Female</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class='formLabel'>Password:</label>
                            <input type="password" id="password" name="password" value="<?php if (isset($_POST['password']))
                                                                                        echo $_POST['password']; ?>">
                            <?php if (isset($password_err))
                                echo "<span class='error'>$password_err</span>"; ?>
                        </div>
                        <div class="form-group">
                            <label class='formLabel'>Confirm Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" value="<?php if (isset($_POST['confirm_password']))
                                                                                                        echo $_POST['confirm_password']; ?>">
                            <?php if (isset($confirm_password_err))
                                echo "<span class='error'>$confirm_password_err</span>"; ?>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <button class="submitBTN btn btn-info" type="submit" value="sumbit">Register</button>
        </form>
        <div class="links">
        <a href="<?php echo base_url;?>login.php" >Login</a>
        </div>
    </div>
</body>

</html>