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

$deckContainsQuery = "INSERT INTO deckcontains (deck_id, cards_id) VALUES (?, ?)";
$stmt = $conn->prepare($deckContainsQuery);
$stmt->bind_param('ii', $currentdeckid, $currentcardid);

for ($i = 0; $i < $numberOfCards; $i++) {
	$stmt->execute();
} 
$stmt->close();

?>