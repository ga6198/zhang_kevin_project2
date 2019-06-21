<?php

require_once 'config/db.php';
require_once 'card.class.php';
require_once 'deck.class.php';
require_once 'controllers/authController.php';

//store error messages
$errors = array();

// check if user has any decks
//$_SESSION['username']
//join user, deck, ownsdeck
// select the deck id where user id matches
// if null, then don't display decks


// if user clicks on add deck button
if(isset($_POST["add-deck-btn"]))
{
	$deckname = $_POST['deckname'];
	$description = $_POST['description'];
	$clan = $_POST['clan'];
	
	// sql statements to insert deck
	$insertDeckQuery = "INSERT INTO decks (deckname, description, clan) VALUES (?, ?, ?)";
	$stmt = $conn->prepare($insertDeckQuery);
	$stmt->bind_param('sss', $deckname, $description, $clan);
	$stmt->execute();
	$stmt->close();
	
	// Add SQL statement to link deck with user!
	// get most recent deck, which was just added. Should have highest id.
	$getDeckQuery = "SELECT DISTINCT MAX(deck_id) FROM decks";
	$stmt = $conn->prepare($getDeckQuery);
	$stmt->execute();
	$result = $stmt->get_result();
	$resultCount = $result->num_rows;
	$stmt->close();
	
	$deck_id = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		//print_r($row);
		$deck_id = $row['MAX(deck_id)'];
	}
	
	$ownsDeckQuery = "INSERT INTO ownsdeck (user_id, deck_id) VALUES (?, ?)";
	$stmt = $conn->prepare($ownsDeckQuery);
	$stmt->bind_param('ii', $_SESSION['id'], $deck_id);
	$stmt->execute();
	$stmt->close();
	
	
	//echo '<p>Deck set as current deck. Any cards added will be placed in this deck</p>';
	//echo '<a href="search.php">Click here to search cards</a>';
		
	header('location: build.php');
}

// if user clicks on view button
if(isset($_POST["view-deck-btn"]))
{
	$_SESSION['viewdeck_id'] = $_POST['deck_id'];
	$_SESSION['viewdeckname'] = $_POST['deckname'];
	
	//FIXME: add in ratings
	
	$deckWithCardsQuery = "SELECT * FROM decks d INNER JOIN deckcontains dc on d.deck_id = dc.deck_id INNER JOIN cards_all c on dc.cards_id = c.cards_id WHERE d.deck_id=?";
	$stmt = $conn->prepare($deckWithCardsQuery);
	$stmt->bind_param('i', $_SESSION['viewdeck_id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$resultCount = $result->num_rows;
	$stmt->close();
	
	if ($resultCount > 0){
		$currentDeck = 0;
		$cardsArray = array();
		
		while ($row = mysqli_fetch_assoc($result)) {
			
			$deck_id = $row['deck_id'];
			$deckname = $row['deckname'];
			$description = $row['description'];
			$clan = $row['clan'];
			
			$currentDeck = new Deck($deck_id, $deckname, $description, $clan);
			
			$cards_id = $row['cards_id'];
			$name = $row['Name'];
			$set = $row['Set/Number'];
			$imageFile = $row['ImageFile'];
			$cardClan = $row['Clan'];
			$cardGrade = $row['Grade/Skill'];
			$type = $row['Type'];
			
			$currentCard = new Card($cards_id, $name, $set, $imageFile, $cardClan, $cardGrade, $type);
			array_push($cardsArray, $currentCard);
			//print_r($row);
		}
		
		$currentDeck->setCards($cardsArray);
		$_SESSION['viewdeck'] = $currentDeck;
	}
	else{
		if(isset($_SESSION['viewdeck']))
		{
			unset($_SESSION['viewdeck']);
		}
	}
	
	header('location: viewdeck.php');
}

// if user clicks on set button
if(isset($_POST["set-deck-btn"]))
{
	if(isset($_SESSION['currentdeck_id'])){
		unset($_SESSION['currentdeck_id']);
	}
	if(isset($_SESSION['currentdeckname'])){
		unset($_SESSION['currentdeckname']);
	}
	if(isset($_SESSION['currentcardname'])){
		unset($_SESSION['currentcardname']);
	}
	
	$_SESSION['currentdeck_id'] = $_POST['deck_id'];
	$_SESSION['currentdeckname'] = $_POST['deckname'];
	header('location: search.php');
}

// if user clicks add card button
if(isset($_POST["add-card-btn"]))
{
	$_SESSION['numberOfCards'] = $_POST['numberOfCards'];
	$_SESSION['currentcardid'] = $_POST['cards_id'];
	$_SESSION['currentcardname'] = $_POST['cardname'];
	
	// sql statements to link card with deck
	/*$cardQuery = "SELECT * FROM cards_all WHERE cards_id=?";
	$stmt = $conn->prepare($cardQuery);
	$stmt->bind_param('i', (int)$_SESSION['currentcardid']);
	$stmt->execute();
	$result = $stmt->get_result();
	$cardResultCount = $result->num_rows;
	$stmt->close();
	
	var $currentCard;
	while ($row = mysqli_fetch_assoc($result)) {
		$cards_id = $row['cards_id'];
		$name = $row['Name'];
		$set = $row['Set/Number'];
		$imageFile = $row['ImageFile'];
		$cardClan = $row['Clan'];
		$cardGrade = $row['Grade/Skill'];
		$type = $row['Type'];
		
		$currentCard = new Card($cards_id, $name, $set, $imageFile, $cardClan, $cardGrade, $type);
	}
	
	$deckQuery = "SELECT * FROM decks WHERE deck_id=?"
	$stmt = $conn->prepare($deckQuery);
	$stmt->bind_param('i', (int)$_SESSION['currentdeck_id']);
	$stmt->execute();
	$deckResult = $stmt->get_result();
	$cardResultCount = $deckResult->num_rows;
	$stmt->close();
	
	var $currentDeck;
	while ($row = mysqli_fetch_assoc($deckResult)) {
		$deck_id = $row['deck_id'];
		$deckname = $row['deckname'];
		$description = $row['description'];
		$clan = $row['clan'];
		
		$currentDeck = new Deck($deck_id, $deckname, $description, $clan);
	}*/
	
	$deckContainsQuery = "INSERT INTO deckcontains (deck_id, cards_id) VALUES (?, ?)";
	$stmt = $conn->prepare($deckContainsQuery);
	$stmt->bind_param('ii', $_SESSION['currentdeck_id'], $_SESSION['currentcardid']);
	
	for ($i = 0; $i < $_SESSION['numberOfCards']; $i++) {
		$stmt->execute();
	} 
	$stmt->close();
	
	// FIXME: add in ratings
	
	/*$query = "SELECT Name FROM cards_all WHERE cards_id=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('i', (int)$_SESSION['currentcardid']);
	$stmt->execute();
	$result = $stmt->get_result();
	$resultCount = $result->num_rows;
	$stmt->close();
	
	if ($resultCount != 1)
	{
		$errors['numcards'] = "Retrieved incorrect number of records";
	}
	else{
		while ($row = mysqli_fetch_assoc($result)) {
			
		}
	}*/
	
	header('location: search.php');
}

// function to display all cards
function displayDecks($conn) 
{
	//FIXME: need to do some innerjoins to get only the user's decks
	
	/*
	SELECT * 
FROM decks d
INNER JOIN deckcontains dc
    on d.deck_id = dc.deck_id
INNER JOIN cards_all c
    on dc.cards_id = c.cards_id
	*/
	
	// sql statements to get all decks
	$query = "SELECT * FROM decks";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->get_result();
	$resultCount = $result->num_rows;
	$stmt->close();
	
	if ($resultCount <= 0)
	{
		$errors['cards_not_found'] = "Could not retrieve cards";
	}
	else{
		//store retrieved cards
		$_SESSION['decks'] = array();

		while ($row = mysqli_fetch_assoc($result)) {
			$deck_id = $row['deck_id'];
			$deckname = $row['deckname'];
			$description = $row['description'];
			$clan = $row['clan'];
			
			$newDeck = new Deck($deck_id, $deckname, $description, $clan);
			//$newCard->displayCardInfo();
			//array_push($cards, $newCard);
			array_push($_SESSION['decks'], $newDeck);
		}
		
		
		if (isset($_SESSION['decks'])){
			foreach ($_SESSION['decks'] as $deck){
				$deck->displayDeckInfo();
			}
		}
	}
}

// function to display all decks
function displayAllDecks($conn) 
{
	// sql statements to get all decks
	$query = "SELECT * FROM decks";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->get_result();
	$resultCount = $result->num_rows;
	$stmt->close();
	
	if ($resultCount <= 0)
	{
		$errors['cards_not_found'] = "Could not retrieve cards";
	}
	else{
		//store retrieved cards
		$_SESSION['decks'] = array();

		while ($row = mysqli_fetch_assoc($result)) {
			$deck_id = $row['deck_id'];
			$deckname = $row['deckname'];
			$description = $row['description'];
			$clan = $row['clan'];
			
			$newDeck = new Deck($deck_id, $deckname, $description, $clan);
			//$newCard->displayCardInfo();
			//array_push($cards, $newCard);
			array_push($_SESSION['decks'], $newDeck);
		}
		
		
		if (isset($_SESSION['decks'])){
			foreach ($_SESSION['decks'] as $deck){
				$deck->displayDeckInfo();
			}
		}
	}
}

?>