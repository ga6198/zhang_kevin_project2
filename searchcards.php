<?php
//work around CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';
require_once 'card.class.php';

$data = array();

// fetch data from POST
$cardname = $_POST['cardname'];
$clan = $_POST['clan'];
$grade = $_POST['grade'];

// modify data to work better in SQL statements
$grade = $grade."%"; // add a wild card onto grade, in order to negate Skill part in database
if(empty($cardname) || $cardname == "")
{
	$cardname = "%"; //use any card
}
$cardname = "%" . $cardname . "%"; //add SQL wildcards to beginning and end of cardname

// get cards based on input
$cardQuery = "SELECT * FROM cards_all WHERE Name LIKE ? AND Clan LIKE ? AND `Grade/Skill` LIKE ? ORDER BY Name";
$stmt = $conn->prepare($cardQuery);
$stmt->bind_param('sss', $cardname, $clan, $grade);
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
	$cards = array();

	while ($row = mysqli_fetch_assoc($result)) {
		$cards_id = $row['cards_id'];
		$name = $row['Name'];
		$set = $row['Set/Number'];
		$imageFile = $row['ImageFile'];
		$cardClan = $row['Clan'];
		$cardGrade = $row['Grade/Skill'];
		$type = $row['Type'];
		
		$newCard = new Card($cards_id, $name, $set, $imageFile, $cardClan, $cardGrade, $type);
		//$newCard->displayCardInfo();
		//array_push($cards, $newCard);
		array_push($cards, $newCard);
	}
	
	echo json_encode($cards);
	
	//store retrieved cards
	/*while ($row = mysqli_fetch_object($result)){
		$data[]=$row;
	}
	
	echo json_encode($data);*/
}



?>