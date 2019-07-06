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
$deckId = $_POST['deckId'];

$sql = "select * from ownsdeck where user_id = " . $user_id;
// get right deck id using query above
$results = $conn->query($sql);
$actualDeckId = 0;
if ($results->num_rows > 0)
{
	$counter = 0;
	while ($row = $results->fetch_assoc())
	{
		if ($counter == $deckId)
		{
			$actualDeckId = $row["deck_id"];
		}
		++$counter;
	}
}


$deleteownsdeck = "delete from ownsdeck where deck_id = " . $actualDeckId;
if ($conn->query($deleteownsdeck) === TRUE) 
{
    $deletedeck = "delete from decks where deck_id = " . $actualDeckId;
	if ($conn->query($deletedeck) === TRUE)
	{
		echo "Success";
	} else {
		echo "Error";
	}
} else {
    echo "Errors";
}

$conn->close();
?>