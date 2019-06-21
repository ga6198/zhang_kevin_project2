<?php
require_once 'clan.php';

class Deck{
	var $deck_id;
	var $deckname;
	var $description;
	var $clan;
	//var $timesRated;
	var $rating;
	var $cards; // array of cards
	
	// note that rating and cards array are not in constructor
	//function __construct($deck_id, $deckname, $clan, $timesRated){
	function __construct($deck_id, $deckname, $description, $clan){
		$this->deck_id = $deck_id;
		$this->deckname = $deckname;
		//$this->description = "";
		$this->description = $description;
		$this->clan = $clan;
		//$this->timesRated = $timesRated;
		
		// $this->rating = 0;
		$this->cards = array(); // initialize list of cards to empty array
	}
	
	/*function setRating($rating){
		$this->rating = $rating;
	}
	
	function calculateRating(){
		
	}*/
	
	/*function getClanImageDirectory($clanName){
		//takes in clan name with space to build the name of the icon image.
		$clanNameStripped = str_replace(' ', '', $clanName);
		$clanImageDir = "images/clan/Icon_" . $clanNameStripped . ".png";
		return $clanImageDir;
	}*/
	
	function addCardsToDeck($card, $numberOfCards) // insert card and card amount
	{
		for ($i = 0; $i < $numberOfCards; $i++){
			array_push($this->cards, $card);
		}
	}
	
	function setCards($cards){
		$this->cards = $cards;
	}
	
	function displayDeckInfo()
	{
		$clanImageDir = getClanImageDirectory($this->clan);
		
		if (!isset($this->rating)){
			$rating = "N/A";
		}
		else{
			$rating = $this->rating;
		}
		
		echo '<div class="deckinfo">';
		echo '<img src="' .$clanImageDir. '" class="rounded" alt="' .$this->clan. '" style="width:100%">';
		echo '<p>';
		echo '<strong>Deck Name: </strong>'.$this->deckname.'<br>';
		echo '<strong>Description: </strong>'.$this->description.'<br>';
		echo '<strong>Clan: </strong>'.$this->clan.'<br>';
		echo '<strong>Rating: </strong>'.$rating.'<br>';
		echo '</p>';
		
		// need button to view cards
		echo '<form action="deckController.php" method="post">';
		
		echo '<div class="form-group nodisplay">';
		echo '<input type="text" name="deck_id" value="'. $this->deck_id .'">';
		echo '</div>';
		
		echo '<div class="form-group nodisplay">';
		echo '<input type="text" name="deckname" value="'. $this->deckname .'">';
		echo '</div>';
		
		echo '<div class="form-group">';
		// echo '<input type="submit" value="View Deck Contents">';
		echo '<button type="submit" name="view-deck-btn" class="btn btn-primary btn-block btn-sm">View Deck Contents</button>';
		echo '</div>';
		echo '</form>';
		
		// need button to set as current deck
		echo '<form action="deckController.php" method="post">';
		
		echo '<div class="form-group nodisplay">';
		echo '<input type="text" name="deck_id" value="'. $this->deck_id .'">';
		echo '</div>';
		
		echo '<div class="form-group nodisplay">';
		echo '<input type="text" name="deckname" value="'. $this->deckname .'">';
		echo '</div>';
		
		echo '<div class="form-group">';
		echo '<button type="submit" name="set-deck-btn" class="btn btn-primary btn-block btn-sm">Set as Current Deck</button>';
		echo '</div>';
		echo '</form>';
		
		echo '</div>';
	}
	
	function compareCardNames($card1, $card2){
		return strcmp($card1->name, $card2->name);
	}
	
	function compareGrades($card1, $card2){
		return strcmp($card1->grade, $card2->grade);
	}
	
	function sortCardsByName()
	{
		$tempCardsSorted = usort($this->cards, array($this, "compareCardNames"));
	}
	
	function sortCardsByGrade() // sort cards by grade
	{
		//foreach($this->)
		$tempCardsSorted = usort($this->cards, array($this, "compareGrades"));
		//$tempCardsSorted = usort($this->cards, "compareGrades");
		return $tempCardsSorted;
	}
	
	function displayCardsInDeck() // display all cards in deck
	{
		//$this->sortCardsByName();
		$this->sortCardsByGrade();
		
		//print_r($cardsSorted);
		
		foreach($this->cards as $card){
		//foreach($cardsSorted as $card){
			$altname = $card->name;
			$imagepath = "images/" . $card->imageFile;
			$onerrorpath = "images/cfv_back.jpg";
			
			echo '<div class="cardinfo">';
			echo '<img src="' .$imagepath. '" class="rounded" alt="' .$altname. '" onerror=this.src="' .$onerrorpath. '" style="width:100%">';
			echo '<p>';
			echo '<strong>Name: </strong>'.$altname.'<br>';
			echo '<strong>Clan: </strong>'.$card->clan.'<br>';
			echo '<strong>Grade/Skill: </strong>'.$card->grade.'<br>';
			echo '</p>';
			echo '</div>';
		}
	}
	
}

//$card1 = new Card("a", "kushinada", "c", "V-EB04-012-RR.jpg", "e", "f", "g");
//echo "card name: ".$card1->name;
//$card1->displayCardImage();
?>