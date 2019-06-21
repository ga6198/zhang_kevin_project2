<?php

require_once 'config/db.php';
require_once 'card.class.php';
require_once 'controllers/authController.php';

//store error messages
$errors = array();

// if user clicks on sign up button
if(isset($_POST["card-search-btn"]))
{
	$cardname = $_POST['cardname'];
	$clan = $_POST['clan'];
	$grade = $_POST['grade'];
	$grade = $grade."%"; // add a wild card onto grade, in order to negate Skill part in database
	
	//echo gettype($cardname);
	//echo gettype($clan);
	//echo gettype($grade);
	
	//validation
	if(empty($cardname))
	{
		$cardname = "%"; //use any card
	}
	$cardname = "%" . $cardname . "%";
	
	
	// get cards based on input
	$cardQuery = "SELECT * FROM cards_all WHERE Name LIKE ? AND Clan LIKE ? AND `Grade/Skill` LIKE ? ORDER BY Name";
	$stmt = $conn->prepare($cardQuery);
	$stmt->bind_param('sss', $cardname, $clan, $grade);
	$stmt->execute();
	$result = $stmt->get_result();
	$resultCount = $result->num_rows;
	$stmt->close();
	
	//echo $resultCount;
	
	if ($resultCount <= 0)
	{
		$errors['cards_not_found'] = "Could not retrieve cards";
	}
	else{
		//store retrieved cards
		$_SESSION['cards'] = array();

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
			array_push($_SESSION['cards'], $newCard);
		}
		
		//echo sizeof($cards);
		//$_SESSION['cards'] = $cards;
		//echo $_SESSION['cards'];
		//$_SESSION['cards'][0]->displayCardInfo();
		//print_r($_SESSION['cards']);
	}
	
	/*$_SESSION['test'] = 'test';
	
	$card1 = new Card("a", "kushinada", "c", "V-EB04-012-RR.jpg", "e", "f", "g");
	$_SESSION['testcard'] = $card1;
	
	$_SESSION['testcardarray'] = array();
	$card2 = new Card("a", "maelstrom", "c", "G-BT13-024EN-RR.jpg", "e", "f", "g");
	array_push($_SESSION['testcardarray'], $card1);
	array_push($_SESSION['testcardarray'], $card2);*/
	
	//header function
	header('location: search.php');
}

/*
function testSession(){
	echo $_SESSION['test'];
}

function testCard(){
	if (isset($_SESSION['testcard'])){
		$_SESSION['testcard']->displayCardInfo();
	}
}

function testCardArray(){
	if (isset($_SESSION['testcardarray'])){
		print_r($_SESSION['testcardarray']);
		//$_SESSION['testcard']->displayCardInfo();
		foreach ($_SESSION['testcardarray'] as $card){
			$card->displayCardInfo();
		}
	}
}
*/

// function to display all cards
function displayCardSearchResults() 
{
	if (isset($_SESSION['cards'])){
		//echo "Session is set";
		
		//print_r($_SESSION['cards']);
		
		/*for ($i = 0; $i < sizeof($_SESSION['cards']); $i++){
			echo $_SESSION['cards'][$i]->displayCardInfo();
		}*/
		
		foreach ($_SESSION['cards'] as $card){
			$card->displayCardInfo();
		}
	}
}

?>