<?php
//work around CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';
require_once 'deck.class.php';

$data = array();

// fetch data from POST
$deckname = $_POST['deckname'];
$clan = $_POST['clan'];
$creator = $_POST['creator'];

// modify data to work better in SQL statements
if(empty($deckname) || $deckname == "")
{
	$deckname = "%"; //use any card
}
$deckname = "%" . $deckname . "%"; //add SQL wildcards to beginning and end of deckname

if(empty($creator) || $creator == "")
{
	$creator = "%"; //use any card
}
$creator = "%" . $creator . "%"; //add SQL wildcards to beginning and end of creator

// get decks based on input
$query = "SELECT d.deck_id, d.deckname, d.description, d.clan, od.user_id, u.username   
FROM decks d
INNER JOIN ownsdeck od
    on d.deck_id = od.deck_id
INNER JOIN users u
    on od.user_id = u.id
WHERE d.deckname LIKE ? AND d.clan LIKE ? AND u.username LIKE ? ORDER BY d.deckname";
$stmt = $conn->prepare($query);
$stmt->bind_param('sss', $deckname, $clan, $creator);
$stmt->execute();
$result = $stmt->get_result();
$resultCount = $result->num_rows;
$stmt->close();

if ($resultCount < 0)
{
	//$errors['cards_not_found'] = "Could not retrieve cards";
	
}
else{
	//store retrieved decks
	/*$decks = array();

	while ($row = mysqli_fetch_assoc($result)) {
		$deck_id = $row['deck_id'];
		$deckname = $row['deckname'];
		$description = $row['description'];
		$cardClan = $row['clan'];
		$user_id = $row['user_id'];
		$username = $row['username'];
		
		$newCard = new Card($cards_id, $name, $set, $imageFile, $cardClan, $cardGrade, $type);
		//$newCard->displayCardInfo();
		//array_push($cards, $newCard);
		array_push($decks, $newCard);
	}
	
	echo json_encode($decks);*/
	
	while ($row = mysqli_fetch_object($result)){
		$data[]=$row;
	}
	
	echo json_encode($data);
}



?>