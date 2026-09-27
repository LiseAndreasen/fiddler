<?php

///////////////////////////////////////////////////////////////////////////
// constants

// loops around the monte carlo loop
$super_loops = 5;

// radius of circumscribed circle
$d = 1.0 / pow(3, 0.5);

// number of results we're looking for, related to monte carlo function
$nor = 2;

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
for($i=1;$i<=$nor;$i++) {
	$var[$i] = 1;
}

while($variance_target < max($var)) {
	$loops *= 3.17;			// sort of square root of 10
	for($i=1;$i<=$nor;$i++) {
		$res_arr[$i] = [];
	}
	
	for($s=0;$s<$super_loops;$s++) {
		$mc_res = monte_carlo($loops);
		for($i=1;$i<=$nor;$i++) {
			printf("Result %d................: %12.7f\n", $i,
				$mc_res[$i-1]);
			$res_arr[$i][] = $mc_res[$i-1];
		}
	}

	for($i=1;$i<=$nor;$i++) {
		$var_res = variance($res_arr[$i]);
		$avg[$i] = $var_res[0];
		$var[$i] = $var_res[1];
	}
		
	print("======================================\n");
	printf("Number of loops.... %d * : %12d\n", $super_loops, $loops);
	printf("Target deviation........: %12.7f\n", $deviation_target);
	printf("Deviation so far........:\n");
	for($i=1;$i<=$nor;$i++) {
		printf("................result %d: %12.7f\n", $i,
			pow($var[$i], 0.5));
	}
	print("======================================\n");
}

for($i=1;$i<=$nor;$i++) {
	printf("Final result %d..........:   %10.7f\n", $i, $avg[$i]);
	printf("                         +/- %8.7f\n", pow($var[$i], 0.5));
}

?>
