<?php 
require_once 'controllers/authController.php';

if(!isset($_SESSION['id']))
{
	header('location: login.php');
	exit();
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	
	<!-- Bootstrap 4 CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	
	<link rel="stylesheet" href="style.css">
	
	<title>Browse Decks</title>
</head>

<body>
	<header class="site-header">
		<div class="container">
			<h1>Vanguard Deck Builder</h1>
		</div>
	</header>

	<nav class="site-nav">
		<div class="container">
			<ul>
				<li><a href="browse.php">Browse Decks</a></li>
				<li><a href="build.php">Build Decks</a></li>
			</ul>
		</div>
	</nav>
	
	<section class="site-main">
		<div class="container">
			
		</div>
	</section>

	<footer class="site-footer">

		
	</footer>
</div>

</body>

</html>