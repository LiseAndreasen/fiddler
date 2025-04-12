<?php

// https://stackoverflow.com/questions/2253232/generate-sequence-with-all-permutations

// the cost of adding b at the end of a
function costToAdd($a, $b) {
	$blgt = strlen($b);
	// big number ;-)
	$cost = 10;
	for($i=1;$i<$blgt;$i++) {
		// test how much b overlaps with the end of a
		if(strcmp(substr($a, - $i), substr($b, 0, $i)) == 0) {
			$cost = $i;
		}
	}
	if($cost < $blgt) {
		return $blgt - $cost;
	}
	return $blgt;
}

// recursion, add a new permutation
function addPerm($a) {
	global $longest, $allLegal, $shrubColors;
	// if a lot of permutations have been absorbed, stop
	if(strlen($a) == sizeof($allLegal) + $shrubColors - 1) {
		$longest = $a;
		return 1;
	}
	if($longest != "") {
		return 1;
	}
	// go through all the perms
	foreach($allLegal as $key => $nextPerm) {
		// but not those already used
		if($nextPerm[1] == 1) {
			continue;
		}
		// also not those with too small overlap
		$cost = costToAdd($a, $nextPerm[0]);
		if(strlen($a) != 0 && $cost > 1) {
			continue;
		}
		// we found a good one!
		$b = $nextPerm[0];
		if(strlen($a) == 0) {
			$atmp = $b;
		} else {
			$atmp = $a . substr($b, -1);
		}
		$allLegal[$key][1] = 1;
		// recursion
		addPerm($atmp);
		$allLegal[$key][1] = 0;
	}
}

// shrub colors
$red = 0;
$orange = 1;
$yellow = 2;
$pink = 3;

// longest sequence found
$longest = "";

// how many different colors do we have?
// how long is a permutation?
// fiddler: 3, extra credit: 4
$shrubColors = 3;

/////////////

// create an array of all legal permutations
// perm + used/not

for($i=0;$i<pow($shrubColors, $shrubColors);$i++) {
	// convert i to number, base shrubColors, shrubColors digits
	$j = $i;
	$tmp0 = "";
	while($j > 0) {
		$tmp2 = $j % $shrubColors;
		$j = intdiv($j, $shrubColors);
		$tmp0 = $tmp2 . $tmp0;
	}
	// may have to put 0s in front
	while(strlen($tmp0) < $shrubColors) {
		$tmp0 = "0" . $tmp0;
	}
	// candidate: tmp0
	// no permutations with 2 neighbors being the same
	for($k=0;$k<$shrubColors-1;$k++) {
		if(strcmp(substr($tmp0, $k, 1),
		substr($tmp0, $k + 1, 1)) == 0) {
			continue 2;
		}
	}
	// no permutations with less than shrubColors-1 colors
	$tmp3 = str_split($tmp0);
	$tmp3 = array_unique($tmp3);
	if(sizeof($tmp3) < $shrubColors - 1) {
		continue;
	}
	$allLegal[] = array($tmp0, 0);
}

print("There are " . sizeof($allLegal) . " permutations ");
print("with $shrubColors shrubs.\n");

// begin searching
addPerm("");

// convert sequence from numbers to letters
$longest = str_replace("$red", "R", $longest);
$longest = str_replace("$orange", "O", $longest);
$longest = str_replace("$yellow", "Y", $longest);
$longest = str_replace("$pink", "P", $longest);

print("This is the best sequence found:\n$longest\n");
print("This sequence has length " . strlen($longest) . ".\n");

?>
