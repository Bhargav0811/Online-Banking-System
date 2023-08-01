<?php
    error_reporting(E_ERROR | E_PARSE);
    $db = new DBConnection;
    $conn = $db->conn;

    
    $transactions = array();


    $qry = $conn->query("SELECT t.*,concat(a.first_name,' ',a.last_name) as `name`,a.account_number from `transaction` t inner join `accounts` a on a.account_number = t.neighbour_account_number where t.account_number = '".$account_number."' order by unix_timestamp(t.time) desc;");
    
    while($row = $qry->fetch_assoc())
    {
        $details = "";
        $type = "";
        if($row['type']==0)
        {
            $details = "Transfered from ".$row["neighbour_account_number"];
            $type = "Credit";
        }
        else
        {
            $details = "Transfered to ".$row["neighbour_account_number"];
            $type = "Debit";
        }

        array_push($transactions,array("date" => $row['time'], "amount" => $row['amount'], "type" =>$type,"details" => $details,"Sender/Receiver"=>$row["name"]));

    }
    

?>