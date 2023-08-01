<?php include 'init_data.php' ?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Transactions</h3>
		<!-- <div class="card-tools">
			<a href="?page=accounts/manage_account" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div> -->
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped" id="indi-list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Date Created</th>
						<th>Type</th>
						<th>Amount</th>
						<th>Details</th>
						<th>Sender/Receiver</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					foreach($transactions as $t):
					?>
					
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class="text-center"><?php echo $t['date'] ?></td>
							<td class="text-center"><?php echo $t['type'] ?></td>
							<td class='text-center'><?php echo number_format($t['amount'],2) ?></td>
							<td class="text-center"><?php echo $t['details'] ?></td>
							<td class="text-center"><?php echo $t['Sender/Receiver'] ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>