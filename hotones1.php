<?php

///////////////////////////////////////////////////////////////////////////
// constants

$fiddler = 1;

///////////////////////////////////////////////////////////////////////////
// functions

// if there's a hole in the array between 0 and n, find it
function no_hole($arr, $n) {
	for($i=0;$i<=$n;$i++) {
		if(!isset($arr[$i])) {
			return false;
		}
	}
	return true;
}

function add_chili($chilis, $combos, $n, $min, $max) {

	global $counter;

	// chilis: these chilis have already been added
	// combos: these combos are already possible
	// n: at most n chilis
	// min: consider adding the chili with this no
	// max: this is the last possible chili to add

	if(sizeof($chilis) == $n) {
		// we can't buy anymore chilis
		if(sizeof($combos) == $max + 1) {
			// all combos are possible
			// we found a valid bunch of chilis
			//printf("Bunch: %s\n", implode(" ", $chilis));
			$counter++;
			return 1;
		} else {
			// not a valid combination
			return 0;
		}
	}	
	
	// adding min as the next chili requires
	// all combos from 0 - min-1 to be already present
	if(!no_hole($combos, $min - 1)) {
		// invalid combination
		return 0;
	}
	
	// choice: add chili no. min or not?
	
	// choice no. 1: don't add this chili
	$choice1 = add_chili($chilis, $combos, $n, $min + 1, $max);
	
	// choice no. 2: do add it
	$chilis[$min] = $min;
	
	// update list of combos
	foreach($combos as $combo) {
		$new_combo = $combo + $min;
		if($new_combo <= $max) {
			$combos[$new_combo] = $new_combo;
		}
	}

	$choice2 = add_chili($chilis, $combos, $n, $min + 1, $max);
	
	return $choice1 + $choice2;
}

///////////////////////////////////////////////////////////////////////////
// main program

// no of different chilis
if($fiddler == 1) {
	$max = 10;
} else {
	$max = 100;
}

// at most n chilis (ceil(log2(max)))
$n = ceil(log($max, 2));

// we need chili no. 1 and 2
$chilis = array(1 => 1, 2 => 2);

// these combine into 1, 2 and 3 (and 0)
$combos = array(0, 1, 2, 3);

// i will count valid bunches of chilis in 2 different ways
$counter = 0;

// next candidate chili is no. 3
$result = add_chili($chilis, $combos, $n, 3, $max);

printf("Result: %d %d\n", $result, $counter);

?>
