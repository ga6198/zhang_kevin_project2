<?php
/*
header('Access-Control-Allow-Origin: 127.0.0.1');
header('Access-Control-Allow-Credentials: true');
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';

$data = array();
$ratings = array();
	
$user_id = $_POST['user_id'];
// sql statements to get only user's decks

$query = "SELECT * FROM decks d
	INNER JOIN ownsdeck od
		on d.deck_id = od.deck_id
	INNER JOIN users u
		on od.user_id = u.id
	WHERE u.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$resultCount = $result->num_rows;
$stmt->close();

if ($resultCount <= 0)
{
	//$errors['cards_not_found'] = "Could not retrieve cards";
	//echo empty array
	$decksAndRatings = array();
	echo json_encode($decksAndRatings);
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