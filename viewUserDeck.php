<?php
//work around CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';
require_once 'card.class.php';
require_once 'deck.class.php';

//store error messages
$errors = array();

$viewdeck_id = $_POST['deck_id'];

//FIXME: add in ratings

$deckWithCardsQuery = "SELECT * FROM decks d INNER JOIN deckcontains dc on d.deck_id = dc.deck_id INNER JOIN cards_all c on dc.cards_id = c.cards_id WHERE d.deck_id=?";
$stmt = $conn->prepare($deckWithCardsQuery);
$stmt->bind_param('i', $viewdeck_id);
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
	
	$deckAndCardInfo = array();
	$deckAndCardInfo['deck'] = $currentDeck;
	$deckAndCardInfo['cards'] = $cardsArray;
	
	echo json_encode($deckAndCardInfo);
	
	//echo json_encode($cardsArray);
	//$currentDeck->setCards($cardsArray);
	//$_SESSION['viewdeck'] = $currentDeck;
}
/*else{
	if(isset($_SESSION['viewdeck']))
	{
		unset($_SESSION['viewdeck']);
	}
}*/

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