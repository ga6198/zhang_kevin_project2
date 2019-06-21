<?php
class Card{
	var $cards_id;
	var $name;
	var $set;
	var $imageFile;
	var $clan;
	var $grade;
	var $type;
	
	function __construct($cards_id, $name, $set, $imageFile, $clan, $grade, $type){
		$this->cards_id = $cards_id;
		$this->name = $name;
		$this->set = $set;
		$this->imageFile = $imageFile;
		$this->clan = $clan;
		$this->grade = $grade;
		$this->type = $type;
	}
	
	function displayCardImage() //need parameters. For example, if card name doesn't contain $search, do not display
	{
		$imagepath = "images/" . $this->imageFile;
		echo '<img src="'.$imagepath.'">';
	}
	
	function displayCardInfo() //display full html block, including card image and name. Maybe display add button as well?
	{
		$altname = $this->name;
		$imagepath = "images/" . $this->imageFile;
		$onerrorpath = "images/cfv_back.jpg";
		
		echo '<div class="cardinfo">';
		echo '<img src="' .$imagepath. '" class="rounded" alt="' .$altname. '" onerror=this.src="' .$onerrorpath. '" style="width:100%">';
		echo '<p>';
		echo '<strong>Name: </strong>'.$altname.'<br>';
		echo '<strong>Clan: </strong>'.$this->clan.'<br>';
		echo '<strong>Grade/Skill: </strong>'.$this->grade.'<br>';
		echo '</p>';
		
		// need button to add card to deck
		if (isset($_SESSION['currentdeckname'])){
			echo '<form action="deckController.php" method="post">';
			
			echo '<div class="form-group nodisplay">'; // send card id
			echo '<input type="text" name="cards_id" value="'. $this->cards_id .'">';
			echo '</div>';
			
			echo '<div class="form-group nodisplay">'; // send card name
			echo '<input type="text" name="cardname" value="'. $this->name .'">';
			echo '</div>';
			
			echo '<div class="form-group">';
			echo '<p><strong>Number to Add: </strong>';
			echo '<select name="numberOfCards">';
			echo '<option value="1">1</option>';
			echo '<option value="2">2</option>';
			echo '<option value="3">3</option>';
			echo '<option value="4">4</option>';
			echo '</select>';
			echo '</p>';
			echo '</div>';
			
			echo '<div class="form-group">';
			echo '<button type="submit" name="add-card-btn" class="btn btn-primary btn-block btn-sm">Add Cards</button>';
			echo '</div>';
			echo '</form>';
		}
		
		echo '</div>';
		
		/*
		<div class="cardinfo">
			<img src="images/V-EB04-012-RR.jpg" class="rounded" alt="kushinada" onerror=this.src="images/cfv_back.jpg " style="width:100%" >
			<p>
			<strong>Name: </strong>Kushinada<br>
			<strong>Clan: </strong>Genesis<br>
			<strong>Grade/Skill: </strong>Grade 0/Whatever<br>
			</p>
		</div>
		*/
	}
	
}

//$card1 = new Card("a", "kushinada", "c", "V-EB04-012-RR.jpg", "e", "f", "g");
//echo "card name: ".$card1->name;
//$card1->displayCardImage();
?>