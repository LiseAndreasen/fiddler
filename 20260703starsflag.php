<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

function count_stars($x1, $y1, $r) {
	// count stars witin circle
	// assume star has integer coordinates
	$stars = 0;
	$x_min = ceil($x1 - $r);
	$x_max = floor($x1 + $r + 0.5);
	$y_min = ceil($y1 - $r);
	$y_max = floor($y1 + $r + 0.5);
	for($x2=$x_min;$x2<=$x_max;$x2++) {
		for($y2=$y_min;$y2<=$y_max;$y2++) {
			$distance = pow(pow($x2 - $x1, 2) + pow($y2 - $y1 , 2), 0.5);
			if($distance <= $r) {
				$stars++;
			}
		}
	}
	return $stars;
}

// given a radius, how many stars will fit?
function stars_max($d1, $r) {
	$stars_max = 0;

	// position of center of circle
	// vary x and y, 0 - 0.5
	for($x1=0;$x1<=0.5;$x1+=$d1) {
		for($y1=0;$y1<=0.5;$y1+=$d1) {
			// radius of circle
			$stars = count_stars($x1, $y1, $r);
			if($stars_max < $stars) {
				$stars_max = $stars;
			}
		}
	}
	return $stars_max;
}

///////////////////////////////////////////////////////////////////////////
// main program

// delta
$d1 = 0.001;		// fiddler, 0.001 is good
$stars_max = stars_max($d1, 2);

printf("Result 1: %d\n\n", $stars_max);

///////////////////////////////////////////////////////////////////////////

$r_min = 4.14;
$r_max = 4.18;

$d1 = 0.0005;

while(0.00001 < $r_max - $r_min) {
	printf("%.7f < r < %.7f\n", $r_min, $r_max);
	$r_mid = ($r_min + $r_max) / 2;
	$stars_r_mid = stars_max($d1, $r_mid);
	if($stars_r_mid < 58) {
		$r_min = $r_mid;
	} else {
		$r_max = $r_mid;
	}
}
printf("\nResult 2: %.7f - %.7f\n", $r_min, $r_max);

?>
