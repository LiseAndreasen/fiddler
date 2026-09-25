<?php

///////////////////////////////////////////////////////////////////////////
// constants

// loops around the monte carlo loop
$super_loops = 5;

// radius of circumscribed circle
$d = 1.0 / pow(3, 0.5);  

///////////////////////////////////////////////////////////////////////////
// functions

// sample variance
function variance($samples) {
	$average = array_sum($samples) / sizeof($samples);
	$variance = 0;
	foreach($samples as $s) {
		$variance += pow($average - $s, 2) / (sizeof($samples) - 1);
	}
	return [$average, $variance];
}

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

function smallest_square($angle) {
	global $d;
	
	$xa = $d * cos($angle);
	$ya = $d * sin($angle);
	$xb = $d * cos($angle + pi() * 2 / 3);
	$yb = $d * sin($angle + pi() * 2 / 3);
	$xc = $d * cos($angle - pi() * 2 / 3);
	$yc = $d * sin($angle - pi() * 2 / 3);
	$xmin = min($xa, $xb, $xc);
	$xmax = max($xa, $xb, $xc);
	$ymin = min($ya, $yb, $yc);
	$ymax = max($ya, $yb, $yc);
	$s = max($xmax - $xmin, $ymax - $ymin);
	return $s;
}

function monte_carlo($loops) {
	$s_arr = [];
	for($l=0;$l<$loops;$l++) {
		$angle = random_0_1() * 2 * pi() / 3;
		$s = smallest_square($angle);
		$s_arr[] = $s;
	}
	$min = min($s_arr);
	$avg = array_sum($s_arr) / sizeof($s_arr);
	return [$min, $avg];
}

///////////////////////////////////////////////////////////////////////////
// main program

$deviation_target = 0.0001;		// ability to round to 3 decimals
$variance_target = pow($deviation_target, 2);

// init
$loops = 100;
$var1 = 1;
$var2 = 1;

while($variance_target < $var1 || $variance_target < $var2) {
	$loops *= 3.17;			// sort of square root of 10
	$res1_arr = [];
	$res2_arr = [];
	
	for($i=0;$i<$super_loops;$i++) {
		[$res1, $res2] = monte_carlo($loops);
		printf("Result 1................: %12.7f\n", $res1);
		printf("Result 2................: %12.7f\n", $res2);
		$res1_arr[] = $res1;
		$res2_arr[] = $res2;
	}
	
	[$avg1, $var1] = variance($res1_arr);
	[$avg2, $var2] = variance($res2_arr);
	
	print("======================================\n");
	printf("Number of loops.... %d * : %12d\n", $super_loops, $loops);
	printf("Target deviation........: %12.7f\n", $deviation_target);
	printf("Deviations so far, res 1: %12.7f\n", pow($var1, 0.5));
	printf("...................res 2: %12.7f\n", pow($var2, 0.5));
	print("======================================\n");
}

printf("Final result 1..........:   %10.7f +/- %10.7f\n", $avg1, pow($var1, 0.5));
printf("Final result 2..........:   %10.7f +/- %10.7f\n", $avg2, pow($var2, 0.5));

?>
