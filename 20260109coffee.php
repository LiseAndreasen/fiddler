<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

function pour_tea($epsilon, $dir) {
	$cc = 12;		// coffee in coffee cup
	$ct = 0;		// coffee in tea cup
	$tt = 12;		// tea in tea cup
	$tc = 0;		// tea in coffee cup
	
	$epsilon_org = $epsilon;

	while(0.00001 < $tt) {
		if($epsilon < 0.00001) {
			break;
		}
		if($tt < $epsilon) {
			$epsilon = $tt;
		}
		
		// pour tea in coffee cup
		$tc = $tc + $epsilon;
		$tt = $tt - $epsilon;
		
		// pour out from coffee cup
		$cc_out = $cc * $epsilon / ($cc + $tc);
		$tc_out = $tc * $epsilon / ($cc + $tc);
		$cc = $cc - $cc_out;
		$tc = $tc - $tc_out;
		
		if($tt == 0) {
			break;
		}
		
		if($dir == "g") {
			// grow
			$epsilon *= 1.05;
		}
		if($dir == "s") {
			// shrink
			$epsilon *= 0.97;
		}
	}

	printf("For epsilon = %7.4f and %s, coffee %9.6f and tea %7.4f.\n", $epsilon_org, $dir, $cc, $tc);
}

///////////////////////////////////////////////////////////////////////////
// main program

$epsilons = [12, 6, 3, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01, 0.005, 0.002, 0.001, 0.0005, 0.0002, 0.0001];

foreach($epsilons as $epsilon) {
	pour_tea($epsilon, "c"); // constant
}

foreach($epsilons as $epsilon) {
	pour_tea($epsilon, "g"); // growing
}

foreach($epsilons as $epsilon) {
	pour_tea($epsilon, "s"); // shrinking
}

?>
