<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';
require_once 'card.class.php';
require_once 'deck.class.php';
//require_once 'controllers/authController.php';

// if user clicks add card button
$numberOfCards = $_POST['numCards'];
$currentcardid = $_POST['cards_id'];
$currentdeckid = $_POST['deck_id'];

//get number of cards already in deck in database
$numInsertedCardsQuery = "SELECT COUNT(*) AS inserted_cards FROM deckcontains WHERE deck_id=? AND cards_id=?";
$stmt = $conn->prepare($numInsertedCardsQuery);
$stmt->bind_param('ii', $currentdeckid, $currentcardid);
$stmt->execute();
$numInsertedCardsResult = $stmt->get_result();
//print_r($numInsertedCardsResult);
$count = $numInsertedCardsResult->fetch_assoc();
$numInsertedCards = $count['inserted_cards'];
$stmt->close();

$deckContainsQuery = "INSERT INTO deckcontains (deck_id, cards_id) VALUES (?, ?)";
$stmt = $conn->prepare($deckContainsQuery);
$stmt->bind_param('ii', $currentdeckid, $currentcardid);

if ($numInsertedCards == 0){
	for ($i = 0; $i < $numberOfCards; $i++) {
		$stmt->execute();
	} 
}
else if ($numInsertedCards == 1){
	// if inserting more than 3 cards, only insert 3
	if ($numberOfCards > 3){
		for ($i = 0; $i < 3; $i++) {
			$stmt->execute();
		}
	}
	else{
		for ($i = 0; $i < $numberOfCards; $i++) {
			$stmt->execute();
		}
	}
}
else if ($numInsertedCards == 2){
	// if inserting more than 2 cards, only insert 2
	if ($numberOfCards > 2){
		for ($i = 0; $i < 2; $i++) {
			$stmt->execute();
		}
	}
	else{
		for ($i = 0; $i < $numberOfCards; $i++) {
			$stmt->execute();
		}
	}
}
else if ($numInsertedCards == 3){
	//insert one card
	$stmt->execute();
}

$stmt->close();

?>