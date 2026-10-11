<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

$first = true;
$epsilon = 0.01;
$speed = true;		// print speed or path of crest?

for($t=0.01;$t<=2*pi()/3;$t+=0.01) {
	// x coordinate of crest
	$x = atan((2 * cos(- $t) + cos(2 * $t)) / (2 * sin(- $t) + sin(2 * $t))) + pi();
	// y coordinate of crest
	$y = 2 * sin($x - $t) + sin($x + 2 * $t);

	if(!$speed) {
		// path of crest
		printf("%f\t%f\n", $x, $y);		
	}

	if($first) {
		$first = false;
		$x_old = $x;
		continue;
	}

	// speed of crest
	$v = ($x - $x_old) / $epsilon;
	
	if($speed) {
		// curve for speed
		printf("%f\t%f\n", $x, $v);
	}
	
	$x_old = $x;
}
?>
