<?php
//work around CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';
require_once 'deck.class.php';

$data = array();
$ratings = array();

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
	$counter = 0;
	while ($row = mysqli_fetch_object($result)){
		$data[]=$row;
		
		/*print_r($data);
		echo "<br>";
		print_r($data[$counter]);
		echo "<br>";*/
		$currentDeckId = $data[$counter]->deck_id;
		/*echo $currentDeckId;
		echo "<br>";*/
		
		//get rating for the current deck
		$query = "SELECT AVG(dr.rating) AS rating_average
			FROM deck_ratings dr
			INNER JOIN decks d
			  ON dr.deck_id = d.deck_id
			WHERE d.deck_id = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param('i', $currentDeckId);
		$stmt->execute();
		$ratingResult = $stmt->get_result();
		$rating = $ratingResult->fetch_assoc();
		$deckRating = $rating['rating_average'];
		$stmt->close();
		
		/*print_r($ratingResult);
		echo "<br>";
		print_r($rating);
		echo "<br>";*/
		
		$ratings[] = $rating;
		
		$counter = $counter + 1;
	}
	
	//echo json_encode($data);
	$decksAndRatings = array();
	$decksAndRatings['decks'] = $data;
	$decksAndRatings['ratings'] = $ratings;
	
	echo json_encode($decksAndRatings);
}

?>