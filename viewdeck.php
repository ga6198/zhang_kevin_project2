<?php 
require_once 'controllers/authController.php';
require_once 'deckController.php';

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
	
	<title>View Decks</title>
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
		if (isset($_SESSION['viewdeck'])){
			// deckname
			echo '<h3>' . $_SESSION['viewdeck']->deckname . '</h3>';
			
			// need button to set as current deck
			echo '<div class="col-sm-3">';
			echo '<form action="deckController.php" method="post">';
			
			//echo '<br>';
			
			echo '<div class="form-group nodisplay">';
			echo '<input type="text" name="deck_id" value="'. $_SESSION['viewdeck']->deck_id .'">';
			echo '</div>';
			
			echo '<div class="form-group nodisplay">';
			echo '<input type="text" name="deckname" value="'. $_SESSION['viewdeck']->deckname .'">';
			echo '</div>';
			
			echo '<div class="form-group">';
			echo '<button type="submit" name="set-deck-btn" class="btn btn-primary btn-block btn-sm">Set as Current Deck</button>';
			echo '</div>';
			echo '</form>';
			
			echo '</div>';
		}
		else
		{
			echo '<p>This deck has no cards!</p>';
			echo '<button onclick="goBack()" class="col-sm-3 btn btn-primary btn-block btn-sm">Go Back</button>';

			echo '<script>';
			echo 'function goBack() {window.history.back();}';
			echo '</script>';
		}
		?>
			<div class="row">
			<?php
				// function to display decks
				if (isset($_SESSION['viewdeck'])){
					// dispay cards in deck
					$_SESSION['viewdeck']->displayCardsInDeck();
				}
			?>
			</div>
		
		</div>
	</section>

	<footer class="site-footer">

		
	</footer>

</body>

</html>