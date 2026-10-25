<?php

///////////////////////////////////////////////////////////////////////////
// constants

// loops around the monte carlo loop
$super_loops = 5;

// number of results we're looking for, related to monte carlo function
$nor = 1;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

// sample variance
function variance($samples) {
	$average = array_sum($samples) / sizeof($samples);
	$variance = 0;
	foreach($samples as $s) {
		$variance += pow($average - $s, 2) / (sizeof($samples) - 1);
	}
	return [$average, $variance];
}

function quadrangular() {
	global $asp;
	
	// 3 points of the triangle
	$x1 = -0.5;
	$y1 = 0;
	$x2 = 0.5;
	$y2 = 0;
	$x3 = 0;
	$y3 = pow(3, 0.5) / 2;
	// 2 vectors between points
	$v1x = $x2 - $x1;
	$v1y = $y2 - $y1;
	$v2x = $x3 - $x1;
	$v2y = $y3 - $y1;
	// construct new random point inside triangle
	$r1 = random_0_1();
	$r2 = random_0_1();
	while(1 < $r1 + $r2) {
		$r2 = random_0_1();
	}
	$x4 = $x1 + $r1 * $v1x + $r2 * $v2x;
	$y4 = $y1 + $r1 * $v1y + $r2 * $v2y;
	// area of 3 new triangles
	$a1 = 0.5 * abs(($x4 - $x3) * ($y2 - $y4) - ($x4 - $x2) * ($y3 - $y4));
	$a2 = 0.5 * abs(($x1 - $x3) * ($y4 - $y1) - ($x1 - $x4) * ($y3 - $y1));
	$a3 = 0.5 * abs(($x1 - $x4) * ($y2 - $y1) - ($x1 - $x2) * ($y4 - $y1));
	
	$a12 = $a1 + $a2;
	$a13 = $a1 + $a3;
	$a23 = $a2 + $a3;
	
	$a_array = [$a12, $a13, $a23];
	sort($a_array);
	
	// looking for extra credit points
	$k1 = $a_array[1] - $a_array[0];
	$k2 = $a_array[2] - $a_array[1];
	
	// this limit seems to work well
	if(abs($k2 - $k1) < 0.00001) {
		$asp[] = [$x4, $y4];
	}
	
	return $a_array;
}

function monte_carlo($loops) {
	$res = [];
	for($l=0;$l<$loops;$l++) {
		$areas = quadrangular();
		$chosen = rand(0, 2);
		$res[] = $areas[$chosen];
	}
	return [array_sum($res) / $loops];
}

///////////////////////////////////////////////////////////////////////////
// main program

$a = "F";		// fiddler or extra credit

$target_decimals = 3;		// ability to round to this no of decimals
$deviation_target = pow(10, - $target_decimals - 1);
$variance_target = pow($deviation_target, 2);

// init
$loops = 10;
for($n=1;$n<=$nor;$n++) {
	$var[$n] = 1;
}

// array for arithmetic sequence points
$asp = [];

while($variance_target < max($var)) {
	$loops *= 3.1623;			// sort of square root of 10
	for($n=1;$n<=$nor;$n++) {
		$res_arr[$n] = [];
	}
	
	for($s=0;$s<$super_loops;$s++) {
		$mc_res = monte_carlo($loops);
		for($n=1;$n<=$nor;$n++) {
			// F = fiddler or fiddler + extra credit, E = extra credit
			printf("Result %s, %d, %d..........: %12.7f\n", $a, $s + 1, $n,
				$mc_res[$n-1]);
			$res_arr[$n][] = $mc_res[$n-1];
		}
	}

	for($n=1;$n<=$nor;$n++) {
		$var_res = variance($res_arr[$n]);
		$avg[$n] = $var_res[0];
		$var[$n] = $var_res[1];
	}
		
	print("======================================\n");
	printf("Number of loops.... %d * : %12d\n", $super_loops, $loops);
	printf("Target deviation........: %12.7f\n", $deviation_target);
	printf("Deviation so far........:\n");
	for($n=1;$n<=$nor;$n++) {
		printf("................result %d: %12.7f\n", $n,
			pow($var[$n], 0.5));
	}
	print("======================================\n");
}

for($n=1;$n<=$nor;$n++) {
	// F = fiddler or fiddler + extra credit, E = extra credit
	printf("Final result %s, %d.......:   %10.7f\n", $a, $n, $avg[$n]);
	printf("                         +/- %8.7f\n", pow($var[$n], 0.5));
}

// only enable this for extra credit
//foreach($asp as $p) {
//	printf("%f\t%f\n", $p[0], $p[1]);
//}

?>
