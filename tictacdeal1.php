<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 10000000;

///////////////////////////////////////////////////////////////////////////
// functions

// board:
//  3  4  5
//  6  7  8
//  9 10 11

// check whether there's a row
function check_rows($rolled) {
	$row = 0;
	
	// horizontal
	if($rolled[3] == 1 && $rolled[4] == 1 && $rolled[5] == 1) {
		$row = 1;
	}
	if($rolled[6] == 1 && $rolled[7] == 1 && $rolled[8] == 1) {
		$row = 1;
	}
	if($rolled[9] == 1 && $rolled[10] == 1 && $rolled[11] == 1) {
		$row = 1;
	}
	
	// vertical
	if($rolled[3] == 1 && $rolled[6] == 1 && $rolled[9] == 1) {
		$row = 1;
	}
	if($rolled[4] == 1 && $rolled[7] == 1 && $rolled[10] == 1) {
		$row = 1;
	}
	if($rolled[5] == 1 && $rolled[8] == 1 && $rolled[11] == 1) {
		$row = 1;
	}

	// diagonal
	if($rolled[3] == 1 && $rolled[7] == 1 && $rolled[11] == 1) {
		$row = 1;
	}
	if($rolled[5] == 1 && $rolled[7] == 1 && $rolled[9] == 1) {
		$row = 1;
	}
	
	return $row;
}

function print_board($rolled) {
	printf("%2d %2d %2d\n", $rolled[3], $rolled[4], $rolled[5]);
	printf("%2d %2d %2d\n", $rolled[6], $rolled[7], $rolled[8]);
	printf("%2d %2d %2d\n", $rolled[9], $rolled[10], $rolled[11]);
}

function print_rolls($necessary_rolls, $loops) {
	$sum = 0;
	for($i=3;$i<=15;$i++) {
		if(isset($necessary_rolls[$i])) {
			$sum += $necessary_rolls[$i];
		}
		printf("%2d rolls, accumulated probability = %.5f\n", $i, $sum/$loops);
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

for($j=0;$j<$loops;$j++) {
	// prepare structure for the rolls so far
	for($i=2;$i<=12;$i++) {
		$rolled[$i] = 0;
	}

	// keep track of whether a row has occurred
	$row = 0;

	// keep track of number of rolls
	$rolls = 0;

	while($row == 0) {
		// roll the dice
		$die1 = rand(1,6);
		$die2 = rand(1,6);
		$die_sum = $die1 + $die2;
		$rolled[$die_sum] = 1;
		$rolls++;
		$row = check_rows($rolled);
	}

	// print_board($rolled);
	// print("Number of rolls: $rolls.\n");
	if(isset($necessary_rolls[$rolls])) {
		$necessary_rolls[$rolls]++;
	} else {
		$necessary_rolls[$rolls] = 1;
	}
}

print_rolls($necessary_rolls, $loops);
?>
