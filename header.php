<link rel="stylesheet" type="text/css" href="css/header.css?v=<?php echo time(); ?>">
<div class="header_main">
	
		<div class="logo-name">
			<div class="logo">
			<a href="home.php">
				<img class="logo_img" src="Image/logo.jpg"></a>
			</div>

			<div class="name">
			<a href="home.php"><h5> Online Banking Facility</h5></a>
		</div>
	
		
	</div>
	<div class="profile">
		<img class="profile_img" src="Image/profile.jpg">
		<h4>Welcome, <?php echo $first_name." ".$last_name; ?></h4>
		<a class="logOut" href="<?php echo base_url;?>login.php"><i class="fa-solid fa-right-from-bracket"></i></a>
	</div>

</div>

<div class="header_links">
	<div class="dropdown show header_link fund_transfer">
		<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			Fund Transfer
		</a>

		<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
			<a id="deposit_link" class="dropdown-item" href="#">Deposit</a>
			<a id="transact_link" class="dropdown-item" href="#">Transact</a>
		</div>
	</div>


	<div class="dropdown show header_link display_Transaction">
		<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			Transaction
		</a>

		<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
			<a id="graph_display_link" class="dropdown-item" href="#">Display Graph</a>
			<a id="table_display_link" class="dropdown-item" href="#">Display Table</a>
		</div>
	</div>

	<div class="dropdown show header_link manage_services">
		<a id="manage_cards_link" class="dropdown-item" href="#">Manage Cards</a>
		<!-- <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			Manage Services
		</a>

		<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
			<a class="dropdown-item" href="#">Action</a>
			<a  class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
		</div> -->
	</div>

	<div class="dropdown show header_link manage_bineficiary">
		<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			Bineficiary
		</a>

		<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
			<a id="addB_link" class="dropdown-item" href="#">Add</a>
			<a id="removeB_link" class="dropdown-item" href="#">Remove</a>
		</div>
	</div>
</div>


</div>



</div>