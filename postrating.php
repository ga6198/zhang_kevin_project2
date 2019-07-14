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

$deck_id = $_POST['deck_id'];
$rating = $_POST['rating'];
$user_id = $_POST['user_id'];

// check if user has already posted rating
$ratingCountQuery = "SELECT * FROM deck_ratings WHERE user_id = ? AND deck_id = ?";
$stmt = $conn->prepare($ratingCountQuery);
$stmt->bind_param('ii', $user_id, $deck_id);
$stmt->execute();
$result = $stmt->get_result();
//$resultCount = $result->num_rows; 
$resultCount = mysqli_num_rows($result);
$stmt->close();

if ($resultCount > 0){
	echo "rating already submitted";
}
else{
	// sql statements to post rating
	$query = "INSERT INTO deck_ratings (deck_id, user_id, rating) VALUES (?, ?, ?)";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('iii', $deck_id, $user_id, $rating);
	$stmt->execute();
	$stmt->close();
}

?>