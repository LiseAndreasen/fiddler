<?php

/////////////

for($k=1;$k<10;$k++) {
	// probability that a domino will tip over
	$p = 1 / pow(10,$k);

	// a population of dl domino lines are started
	// for every extra domino added
	// dl * p lines will tip over
	// we want to know the length of the dl * 1/2 th line

	// the proportion of untipped lines
	$tip = 1;

	// current line length
	$cll = 0;

	while($tip > 0.5) {
		$cll++;
		$tip *= (1 - $p);
	}

	echo $cll * $p . "\n";
}

// note: ln(2) = 0.69314718056...

?>
