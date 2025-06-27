<?php

// constants

// fiddler or extra credit?
$fiddler = 1;

if($fiddler == 1) {
	// legal combinations
	$combos = array("I", "II", "III");

	// beginning string
	$scroll = "IIIIIIIIII";
} else {
	// legal combinations
	$combos = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII");

	// beginning string
	$scroll = "IIIVIIIVIIIVIII";
}

// functions

// search through the tree of possibilities, one combo at a time
function search_combos($str) {
	global $combos, $no_solutions;
	
	// the whole string has been used, a solution was found
	if(strcmp("", $str) == 0) {
		$no_solutions++;
		return 1;
	}
	
	// keep looking for different solutions
	$str_sz = strlen($str);
	foreach($combos as $combo) {
		$combo_sz = strlen($combo);
		if($str_sz < $combo_sz) {
			// this wouldn't work as the next bit
			continue;
		}
		if(strcmp($combo, substr($str, 0, $combo_sz)) == 0) {
			$new_str = substr($str, $combo_sz);
			search_combos($new_str);
		}
	}
}

//////////////////////////////////////////////

// number of valid solutions
$no_solutions = 0;

search_combos($scroll);

printf("There were %d solutions.\n", $no_solutions);

?>
