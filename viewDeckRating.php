<?php
//work around CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';
require_once 'card.class.php';
require_once 'rating.class.php';

//store error messages
$errors = array();

$viewdeck_id = $_POST['deck_id'];

//FIXME: add in ratings

$deckRatingsQuery = "SELECT * FROM deck_ratings dr INNER JOIN users ui on dr.user_id = ui.id WHERE dr.deck_id = ?";
$stmt = $conn->prepare($deckRatingsQuery);
$stmt->bind_param('i', $viewdeck_id);
$stmt->execute();
$result = $stmt->get_result();
$resultCount = $result->num_rows;
$stmt->close();

if ($resultCount > 0){
	$currentRating = 0;
	$ratingArray = array();
	
	while ($row = mysqli_fetch_assoc($result)) {
		
		$username = $row['username'];
		$rating = $row['rating'];
		$message = $row['message'];
		$profile_picture = $row['profile_picture'];
		$user_id = $row['user_id'];
		
		$currentRating = new Rating($username, $rating, $message, $profile_picture, $user_id);

		array_push($ratingArray, $currentRating);
		//print_r($row);
	}
	
	$ratingsArray['rating'] = $ratingArray;
	
	echo json_encode($ratingsArray);

}
else
{
	echo json_encode("No Ratings");
}
?>