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
$currentcardid = $_POST['cards_id'];
$currentdeckid = $_POST['deck_id'];
//$currentcardid = 4872;
//$currentdeckid = 6;

// delete query
$deckContainsQuery = "delete dc.* from deckcontains dc where deckcontains_id in (select deckcontains_id from (select deckcontains_id from deckcontains where deck_id=? and cards_id=? limit 1) x)";
$stmt = $conn->prepare($deckContainsQuery);
$stmt->bind_param('ii', $currentdeckid, $currentcardid);
$stmt->execute();
$stmt->close();

?>