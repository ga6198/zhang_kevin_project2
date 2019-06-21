<?php

//header('Access-Control-Allow-Origin: 127.0.0.1');
//header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';
require_once 'card.class.php';
require_once 'deck.class.php';

//store error messages
$errors = array();

// check if user has any decks

// if user clicks on add deck button
$user_id = $_POST['user_id'];
$deckname = $_POST['deckname'];
$description = $_POST['description'];
$clan = $_POST['clan'];

// sql statements to insert deck
$insertDeckQuery = "INSERT INTO decks (deckname, description, clan) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insertDeckQuery);
$stmt->bind_param('sss', $deckname, $description, $clan);
$stmt->execute();
$stmt->close();

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

//link user and deck
$ownsDeckQuery = "INSERT INTO ownsdeck (user_id, deck_id) VALUES (?, ?)";
$stmt = $conn->prepare($ownsDeckQuery);
//$stmt->bind_param('ii', $_SESSION['id'], $deck_id);
$stmt->bind_param('ii', $user_id, $deck_id);

if ($stmt->execute()){
	echo 'added deck';
}
/*else{
	echo 'failed'; //might be due to session
}*/
$stmt->close();

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