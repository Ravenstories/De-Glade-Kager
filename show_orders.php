<!DOCTYPE html>
<html lang="en">


<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>De Glade Kager</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="show_orders.css" rel="stylesheet">

</head>
<body>


<!-- Header -->
<?php include('templates/header.php'); ?>
	  
<!-- Page Content -->	 
	<?php
		// This is page is an alpha stage page for the site where users can see the orders that have been placed

		// Connect to datebase. See the connection file for connection information
		include('templates/connection.php');

		// Grapping the data we want and store it in a variable
		$sql = 'SELECT firstName, lastName, cake_order, comment, email, phone FROM orders';

		// To get the data we need to have the connection and sql statement in a variable we can use when we want to output data
		$result = mysqli_query($connection, $sql);

		// This store our data in an array we can use to output it
		$show_Orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

		// Will free the result from memory
		mysqli_free_result($result);

		// Closes the connection
		mysqli_close($connection)

	?>

	<!-- Shows all the orders in their own box -->
	<section>
		<div class="row text-center">
			<?php foreach($show_Orders as $orders){ ?>
				<div class="col-lg-4 col-md-6 mb-4">
					<div class="card h-100">
						<div class="card-body">
							<h4 class="card-title"><?php echo htmlspecialchars($orders['firstName']) . ' ' . htmlspecialchars($orders['lastName']); ?></h4>
							<p class="card-text"><?php echo htmlspecialchars($orders['cake_order']); ?></p>
							<p class="card-text"><?php echo htmlspecialchars($orders['comment']); ?></p>
							<p class="card-text"><?php echo htmlspecialchars($orders['email']); ?></p>
							<p class="card-text"><?php echo htmlspecialchars($orders['phone']); ?></p>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>	
	
	<section>			
		<div class="container">	
			<div id="main"></div>	
		</div>
	</section>

<!-- Footer -->
<section>
    <?php include('templates/footer.php'); ?>  
</section>

 </body>

</html>