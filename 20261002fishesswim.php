<?php

///////////////////////////////////////////////////////////////////////////
// constants

// how many hours does the experiment run?
$hours = 100;

// how long is the pond?
$length = 1;

// mean and standard deviation
$mean = 0;
$dev = 1;

// loops around the monte carlo loop
$super_loops = 5;

// number of results we're looking for, related to monte carlo function
$nor = 1;

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

// https://www.php.net/manual/en/function.stats-rand-gen-normal.php
function stats_rand_gen_normal($av, $sd): float
{
    $x = mt_rand() / mt_getrandmax();
    $y = mt_rand() / mt_getrandmax();

    return sqrt(-2 * log($x)) * cos(2 * pi() * $y) * $sd + $av;
}

// how many times will this fish pass the sensor?
function passes($speed, $hours, $length) {
	$no = $speed * $hours / $length;
	return $no;
}

function swimming_fish($no_of_fish) {
	global $hours, $length, $mean, $dev;
	
	$sum_of_speeds = 0;
	$no_of_speeds = 0;
	for($f=0;$f<$no_of_fish;$f++) {
		$velocity = stats_rand_gen_normal($mean, $dev);
		$speed = abs($velocity);
		$no = passes($speed, $hours, $length);
		$sum_of_speeds += $no * $speed;
		$no_of_speeds += $no;
	}
	return [$sum_of_speeds / $no_of_speeds];
}

///////////////////////////////////////////////////////////////////////////
// main program

$sum_of_speeds = 0;
$no_of_speeds = 0;

// fish 1
$speed = 1;
$no = passes($speed, $hours, $length);
$sum_of_speeds += $no * $speed;
$no_of_speeds += $no;

// fish 2
$speed = 2;
$no = passes($speed, $hours, $length);
$sum_of_speeds += $no * $speed;
$no_of_speeds += $no;

printf("Result A: %f\n\n", $sum_of_speeds / $no_of_speeds);

///////////////////////////////////////////////////////////////////////////

$deviation_target = 0.0001;		// ability to round to 3 decimals
$variance_target = pow($deviation_target, 2);

// init
$loops = 1000000;
for($i=1;$i<=$nor;$i++) {
	$var[$i] = 1;
}

while($variance_target < max($var)) {
	$loops *= 3.17;			// sort of square root of 10
	for($i=1;$i<=$nor;$i++) {
		$res_arr[$i] = [];
	}
	
	for($s=0;$s<$super_loops;$s++) {
		$mc_res = swimming_fish($loops);
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
