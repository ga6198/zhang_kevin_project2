<?php 
require_once 'controllers/authController.php';
require_once 'cardController.php';

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
	
	<title>Search Cards</title>
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
			<?php
				if (isset($_SESSION['currentdeckname'])){
					echo '<p>Current deck: <strong>' . $_SESSION['currentdeckname'] . '</strong>. Any card added will go to this deck.</p>';
					
					/* want to add a form that lists all of the user's decks. Use sql query and place in session. Then use javascript to display
					echo '<form action="deckController.php" method="post">';
					echo '<h6>Current Deck</h6>';
					echo '<select name="deckchoice">';
					echo '</form>';
					*/
					
					if (isset($_SESSION['numberOfCards']) && isset($_SESSION['currentcardname'])){
						echo '<p>Added <strong>' . $_SESSION['numberOfCards'] .  '</strong> copies of <strong>' . $_SESSION['currentcardname'] . '</strong> to <strong>' . $_SESSION['currentdeckname'] . '</strong>.</p>';
					}
				}
			?>
		
			<form action="cardController.php" method="post">
				<h3 class="text-center">Browse Cards</h3>
				<div class="form-group">
					<h6>Card Name</h6>
					<!-- <label for="cardname">Card Name</label> -->
					<input type="text" name="cardname" class="form-control form-control-sm">
				</div>
				<div class="form-group">
					<h6>Clan</h6>
					<select name='clan'>
						<option value='%' >Any</option>
						<option value='Angel Feather'>Angel Feather</option>
						<option value='Aqua Force'>Aqua Force</option>
						<option value='Bermuda Triangle' >Bermuda Triangle</option>
						<option value='Dark Irregulars'>Dark Irregulars</option>
						<option value='Dimension Police'>Dimension Police</option>
						<option value='Gear Chronicle' >Gear Chronicle</option>
						<option value='Genesis'>Genesis</option>
						<option value='Gold Paladin'>Gold Paladin</option>
						<option value='Granblue' >Granblue</option>
						<option value='Great Nature'>Kagero</option>
						<option value='Link Joker'>Link Joker</option>
						<option value='Megacolony' >Megacolony</option>
						<option value='Murakumo'>Murakumo</option>
						<option value='Narukami'>Narukami</option>
						<option value='Neo Nectar' >Neo Nectar</option>
						<option value='Nova Grappler'>Nova Grappler</option>
						<option value='Nubatama'>Nubatama</option>
						<option value='Oracle Think Tank' >Oracle Think Tank</option>
						<option value='Pale Moon'>Pale Moon</option>
						<option value='Royal Paladin'>Royal Paladin</option>
						<option value='Shadow Paladin'>Shadow Paladin</option>
						<option value='Spike Brothers'>Spike Brothers</option>
						<option value='Tachikaze'>Tachikaze</option>
					</select>
				</div>
				<div class="form-group">
					<h6>Grade</h6>
					<select name='grade'>
						<option value='%' >Any</option>
						<option value='Grade 0'>0</option>
						<option value='Grade 1'>1</option>
						<option value='Grade 2' >2</option>
						<option value='Grade 3' >3</option>
						<option value='Grade 4' >4</option>
					</select>
				</div>
				<div class="form-group">
					<button type="submit" name="card-search-btn" class="btn btn-primary btn-block btn-lg">Search Cards</button>
				</div>
			</form>
		
			<div class="row">
			<?php
				// function to display cards
				displayCardSearchResults(); 
			?>
			</div>
		</div>
	</section>

	<footer class="site-footer">

		
	</footer>

</body>

</html>