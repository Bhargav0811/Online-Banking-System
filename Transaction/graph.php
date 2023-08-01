<?php
// $transactions = array(
//   array("date" => "2023-04-29 15:52:54", "amount" => 5000, "type" => "Credit",'details'=>"A"),
//   array("date" => "2023-04-29 15:52:54", "amount" => 1550, "type" => "Debit",'details'=>"B"),
//   array("date" => "2023-04-29 15:52:54", "amount" => 1300, "type" => "Credit",'details'=>"C"),
//   array("date" => "2023-04-29 15:52:54", "amount" => 1200, "type" => "Debit",'details'=>"D"),
//   array("date" => "2023-04-29 15:52:54", "amount" => 1100, "type" => "Debit",'details'=>"E")
// );
include 'init_data.php';


$dates = array();
$labels = array();
$values = array();
$bg_color = array();
$border_color = array();

foreach($transactions as $transaction) {
  $dates[] = $transaction["date"];
  $amount = $transaction["amount"];
  $type = $transaction["type"];
  $labels[] = $transaction["details"];

  if ($type == "Credit") {
    $values[] = $amount;
    $bg_color[] = 'rgba(75, 192, 192, 0.2)';
    $border_color[] = 'rgb(75, 192, 192)';
  } else {
    $values[] = -1*$amount;
    $bg_color[] = 'rgba(255, 99, 132, 0.2)';
    $border_color[] = 'rgb(255, 99, 132)';
  }
}


$data = array(
  "labels" => $dates,
  "datasets" => 
  array(
  array(
    "data" => $values,
    "backgroundColor" => $bg_color,
    "borderColor" => $border_color,
    "borderWidth" => 1
  ))
);


$options = array(

  "plugins" => array(
    "legend" => false
  ),
  "scales" => array(
    "y" => array(
      "beginAtZero" => true
      )
    )
);

?>
<html>
  <head>
    <title>Transaction Bar Graph</title>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> -->
   
  </head>
  <body>
    <?php
    if(empty($transactions))
    {
      echo "<h3 class='cardD_message'>No Transactions</h3>";
    }
    else 
    {
      echo "<canvas id='transaction-chart'></canvas>";
    }
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
      var ctx = document.getElementById('transaction-chart').getContext('2d');
      var data_ob =  <?php echo json_encode($data); ?>;
      var options_ob =  <?php echo json_encode($options); ?>;
      console.log(data_ob);
      console.log(options_ob);
      var chart = new Chart(ctx, {
        type: 'bar',
        data: data_ob,
        options: options_ob
      });
    </script>
  </body>
</html>

