<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 1000000;
// this strategy says whether my next guess will be
// the same as this guess
// or different
$same = "s";
$diff = "d";
$strategies_4 = [
	[$same, $same], [$same, $diff],
	[$diff, $same], [$diff, $diff]
];

///////////////////////////////////////////////////////////////////////////
// functions

function simulate_n_questions($n, $strategy) {
	global $same, $diff;
	// this assumes the previous question was correct
	// and was already awarded 1 point
	$points = 0;
	// guesses and answers are 0-3
	// the next guess shouldn't be the same as the 1st guess
	// only 3 options
	$g = rand(1, 3);
	$a = rand(1, 3);
	if($g == $a) {
		$points++;
	}
	for($i=0;$i<$n-1;$i++) {
		if($strategy[$i] == $diff) {
			$g = (rand(1, 3) + $g) % 4;
		} else {
			// just don't change g
		}
		$a = (rand(1, 3) + $a) % 4;
		if($g == $a) {
			$points++;
		}
	}
	return $points;
}

///////////////////////////////////////////////////////////////////////////
// main program

$exprected = [];

for($j=0;$j<sizeof($strategies_4);$j++) {
	$points_sum = 0;
	for($i=0;$i<$loops;$i++) {
		$points_sum += 1 + simulate_n_questions(4 - 1, $strategies_4[$j]);
	}
	$expected[$j] = $points_sum / $loops;
}

printf("Result 1: %.5f\n", max($expected));

?>
