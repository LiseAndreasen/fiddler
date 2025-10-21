<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 100000000;

///////////////////////////////////////////////////////////////////////////
// functions

// https://amazingalgorithms.com/snippets/php/generate-a-random-float-number/
function random_angle($max) {
	// Generating a random float number between two specific values (inclusive)
	$min = 0.0;
	$random_float = mt_rand() / mt_getrandmax() * ($max - $min) + $min;
	return $random_float;
}

///////////////////////////////////////////////////////////////////////////
// main program

$loop_sum = 0;
$min_dist = 1;
$max_dist = 0;
for($i=0;$i<$loops;$i++) {
	// at center of unit square, choose direction randomly
	// WLOG, direction is towards upper right corner
	// direction is 0-45 deg

	$dir = random_angle(45);
	// triangle with right angle in center of square
	// distance from right angle to y axis: 0.5
	// distance from right angle to x axis:
	$x_dist = tan(deg2rad($dir)) * 0.5;

	$edge_dist = pow($x_dist * $x_dist + 0.5 * 0.5, 0.5);
	$loop_sum += $edge_dist;
	if($edge_dist < $min_dist) {
		$min_dist = $edge_dist;
	}
	if($max_dist < $edge_dist) {
		$max_dist = $edge_dist;
	}
}

printf("Average distance from center to square: %.5f\n", $loop_sum / $loops);
printf("Minimum distance......................: %.5f\n", $min_dist);
printf("Maximum distance......................: %.5f\n\n", $max_dist);

// and then again, with a unit cube
$loop_sum = 0;
$min_dist = 1;
$max_dist = 0;
for($i=0;$i<$loops;$i++) {
	// at center of unit cube, choose direction randomly
	// WLOG, direction is towards upper right corner
	// direction is 0-45 deg in the x-y-plane
	// and 0-45 deg in the z direction

	$dir_xy = random_angle(45);
	// triangle with right angle in center of square
	// distance from right angle to y axis: 0.5
	$y_dist = 0.5;
	// distance from right angle to x axis:
	$x_dist = tan(deg2rad($dir_xy)) * 0.5;
	
	$dir_z = random_angle(45);
	$z_dist = tan(deg2rad($dir_z)) * 0.5;

	$edge_dist = pow($x_dist * $x_dist + $y_dist * $y_dist + $z_dist * $z_dist, 0.5);
	$loop_sum += $edge_dist;
	if($edge_dist < $min_dist) {
		$min_dist = $edge_dist;
	}
	if($max_dist < $edge_dist) {
		$max_dist = $edge_dist;
	}
}

print("Method 1:\n");
printf("Average distance from center to cube..: %.5f\n", $loop_sum / $loops);
printf("Minimum distance......................: %.5f\n", $min_dist);
printf("Maximum distance......................: %.5f\n\n", $max_dist);

// calculating x, y and z differently
$loop_sum = 0;
$min_dist = 1;
$max_dist = 0;
for($i=0;$i<$loops;$i++) {
	// at center of unit cube, choose direction randomly

	// https://stackoverflow.com/questions/30011741/3d-vector-defined-by-2-angles
	$alpha = random_angle(360);
	$beta = random_angle(360);
	$x_dist = cos(deg2rad($alpha)) * cos(deg2rad($beta));
	$z_dist = sin(deg2rad($alpha)) * cos(deg2rad($beta));
	$y_dist = sin(deg2rad($beta));
	// these distances are from (0,0,0) to unit sphere
	// scale accordingly
	// one of these distances should be 0.5
	// the others should be more
	$scaling = max(abs($x_dist), abs($y_dist), abs($z_dist));
	$x_dist = $x_dist * 0.5 / $scaling;
	$y_dist = $y_dist * 0.5 / $scaling;
	$z_dist = $z_dist * 0.5 / $scaling;

	$edge_dist = pow($x_dist * $x_dist + $y_dist * $y_dist + $z_dist * $z_dist, 0.5);
	$loop_sum += $edge_dist;
	if($edge_dist < $min_dist) {
		$min_dist = $edge_dist;
	}
	if($max_dist < $edge_dist) {
		$max_dist = $edge_dist;
	}
}

print("Method 2:\n");
printf("Average distance from center to cube..: %.5f\n", $loop_sum / $loops);
printf("Minimum distance......................: %.5f\n", $min_dist);
printf("Maximum distance......................: %.5f\n", $max_dist);

?>
