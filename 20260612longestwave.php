<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

// https://www.desmos.com/calculator/mj7yoo2tte

// go from 0 to 90 degrees, 90 degrees in all
// delta: the distance between the samples
// 1st sample: 0 + delta/2
// last sample: 90 - delta/2

$delta = 0.0001;
$no_of_samples = 90 / $delta;

$d_max = 0;
$d_sum = 0;

// t: theta
for($t=$delta/2;$t<90;$t+=$delta) {
	// point where upper, right wave hits island
	$x1 = cos(deg2rad($t));
	$y1 = sin(deg2rad($t));
	
	// point in the middle between waves entering and leaving island
	$x2 = $x1 / 2;
	$y2 = ($y1 - 1) / 2;
	
	// upper left end of wave path across island midway
	$x3a = 0;
	$a = $x1 * $x1 / ($y1 * $y1) + 1;
	$b = - 2 * $x1 * $x1 * $x2 / ($y1 * $y1) - 2 * $x1 * $y2 / $y1;
	$c = $x1 * $x1 * $x2 * $x2 / ($y1 * $y1) + $y2 * $y2 + 2 * $x1 * $x2 * $y2 / $y1 - 1;
	$x3b = (- $b - pow($b * $b - 4 * $a * $c, 0.5)) / (2 * $a);
	$x3 = max($x3a, $x3b);
	$y3 = $y2 - $x1 * ($x3 - $x2) / $y1;
	
	// lower right end
	$x4 = (- $b + pow($b * $b - 4 * $a * $c, 0.5)) / (2 * $a);
	$y4 = - $x1 / $y1 * ($x4 - $x2) + $y2;
	
	// distance between these 2 points
	$d = pow(pow($x4 - $x3, 2) + pow($y4 - $y3, 2), 0.5);
	if($d_max < $d) {
		$d_max = $d;
	}
	$d_sum += $d;
}

printf("Fiddler.....: %.6f\n", $d_max);
printf("Extra credit: %.6f\n", $d_sum / $no_of_samples);

?>
