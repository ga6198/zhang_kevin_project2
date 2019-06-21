<?php

function getClanImageDirectory($clanName){
	//takes in clan name with space to build the name of the icon image.
	$clanNameStripped = str_replace(' ', '', $clanName);
	$clanImageDir = "images/clan/Icon_" . $clanNameStripped . ".png";
	return $clanImageDir;
}

?>