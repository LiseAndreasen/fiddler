<?php

// probability that a domino will tip over
$p = 1/100;

/////////////

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

echo $cll . "\n";

?>
