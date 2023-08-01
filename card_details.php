<!DOCTYPE html>
<html>
  <head>
    <title>Card Details</title>
    <link rel="stylesheet" href="css/card_details_style.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php

    if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }
          
            $sql = "SELECT d.*,c.card_name FROM card_details d INNER JOIN cards c WHERE c.card_ID=d.card_ID AND d.account_number = '".$account_number."'";
            $result = $conn->query($sql);
          
            if ($result->num_rows > 0) {
              echo "<table>";
              echo "<tr><th>Card ID</th><th>Name</th><th>Status</th><th>Card Limit</th></tr>";
              while($row = $result->fetch_assoc()) {
                $temp_status = "";
                if($row["status"]==1){$temp_status="ACTIVE";}
                else {$temp_status="BLOCKED";}
                echo "<tr><td>" . $row["card_ID"] . "</td><td>" . $row["card_name"] . "</td><td>" . $temp_status . "</td><td>" . $row["card_limit"] . "</td></tr>";
              }
              echo "</table>";
            } else {
              echo "<h3 class='cardD_message'>No card details</h3>";
            }
    ?>
  </body>
</html>
