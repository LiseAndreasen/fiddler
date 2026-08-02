<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 10000000;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

function print_gaps($a, $c, $e, $w) {
	$no_of_prints = 100;
	for($i=0;$i<$no_of_prints;$i++) {
		if($a <= $i/$no_of_prints && $i/$no_of_prints <= $a + $w) {
			print(" ");
		} else {
			print("#");
		}
	}
	printf(" %.5f - %.5f\n", $a, $a + $w);
	if(1 < $c + $w) {
		for($i=0;$i<$no_of_prints;$i++) {
			if($c <= $i/$no_of_prints || $i/$no_of_prints <= $c + $w - 1) {
				print(" ");
			} else {
				print("#");
			}
		}
		printf(" %.5f - %.5f\n", $c, $c + $w - 1);
	} else {
		for($i=0;$i<$no_of_prints;$i++) {
			if($c <= $i/$no_of_prints && $i/$no_of_prints <= $c + $w) {
				print(" ");
			} else {
				print("#");
			}
		}
		printf(" %.5f - %.5f\n", $c, $c + $w);
	}
	if(1 < $e + $w) {
		for($i=0;$i<$no_of_prints;$i++) {
			if($e <= $i/$no_of_prints || $i/$no_of_prints <= $e + $w - 1) {
				print(" ");
			} else {
				print("#");
			}
		}
		printf(" %.5f - %.5f\n", $e, $e + $w - 1);
	} else {
		for($i=0;$i<$no_of_prints;$i++) {
			if($e <= $i/$no_of_prints && $i/$no_of_prints <= $e + $w) {
				print(" ");
			} else {
				print("#");
			}
		}
		printf(" %.5f - %.5f\n", $e, $e + $w);
	}
	for($i=0;$i<$no_of_prints;$i++) {
		print(".");
	}
	print("\n");
}

///////////////////////////////////////////////////////////////////////////
// main program

// WLOG, first gap is 0 - 0.125
// gaps 2 and 3 are defined by their beginning and 0.125 wide

// 2 gaps, a-b and c-d overlap if either
// a <= c <= b
// a <= d <= b

// note overrun

// the gaps go a-b, c-d and e-f

$a = 0;
$b = 0.125;
$w = 0.125;		// width

// count successful sneaks
$sneaks = 0;
for($i=0;$i<$loops;$i++) {
	// progress
	if($i % ($loops/10) == 0 && 100 < $loops) {
		print($i/($loops/10));
	}
	
	$c = random_0_1();
	$e = random_0_1();
	$d = $c + $w;
	if(1 < $d) {
		$d--;
	}
	$f = $e + $w;
	if(1 < $f) {
		$f--;
	}
	// find the overlap interval, if it exists
	if($a <= $c && $c <= $b) {
		$i1 = $c;
		$i2 = $b;
	} else {
		if($a <= $d && $d <= $b) {
			$i1 = $a;
			$i2 = $d;
		} else {
			continue;
		}
	}
	if(($i1 <= $e && $e <= $i2) || ($i1 <= $f && $f <= $i2) ||
	($e < $f && $e <= $i1 && $i2 <= $f) || ($f < $e && $i2 <= $f)) {
		$sneaks++;
		if($loops <= 100) {
			print_gaps($a, $c, $e, $w);
		}
	}
}

printf("\n%d sneaks out of %d, prob = %.5f\n",
	$sneaks, $loops, $sneaks/$loops);

?>
