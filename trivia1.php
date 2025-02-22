<?php

// m: correct guesses
function score($points, $m) {
	$sum = 0;
	while($m>0) {
		// get random number k, 0 -> # points
		$pp = sizeof($points) - 1;
		$k = rand(0, $pp);
		// add kth points to sum
		$sum += $points[$k];
		$m--;
		unset($points[$k]);
		sort($points);
	}
	return $sum;
}

////////////////////////////////////////////////////////////////////////////////

// You must assign point values of 0, 1, 1, 2, 2, and 3 to the six questions.
$points = [0, 1, 1, 2, 2, 3];
// doing min and max by hand
$min = [0, 0, 1, 2, 4, 6, 9];
$max = [0, 3, 5, 7, 8, 9, 9];

// n simulations
$n = 1000000;

// probability sum = hits
$ps = 0;

for($i=1;$i<=$n;$i++) {
	// opponent had m correct guesses
	$m = 2;
	
	// get m correct answers, min point score is:
	$thismin = $min[$m];
	// and max correct point score is:
	$thismax = $max[$m];
	// and in this case, actual point score is:
	$actual = score($points, $m);
	
	// my defensive efficiency is defined as the maximum possible points allowed
	// minus actual points allowed, divided by the maximum possible points
	// allowed minus the minimum possible points allowed.
	// de = (max - act) / (max - min)
	$de = ($thismax - $actual) / ($thismax - $thismin);
	
	// was 0.5 < de?
	if(0.5 < $de) {
		$ps++;
	}
}
// simulations

// divide hits (ps) with # simulations (n)
print($ps/$n . "\n");

?>
