<?php

// probability that a domino will tip over
$p = 1/100;

// no of simulations
$nos = 10000;

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

/////////////

// a population of dl domino lines are started
// for every extra domino added
// dl * p lines will tip over
// we want to know the length of the dl * 1/2 th line

// simulations
for($i=0;$i<$nos;$i++) {
	// current line length
	$cll = 0;
	$tipped = 0;
	while($tipped == 0) {
		$cll++;
		if(random_0_1() < $p) {
			// it tipped
			$tipped = 1;
		}
	}
	
	// remember this cll
	if(isset($tips[$cll])) {
		$tips[$cll]++;
	} else {
		$tips[$cll] = 1;
	}
}

$cll = 0;
$lowtips = 0;
while($lowtips / $nos < 0.5) {
	$cll++;
	$lowtips += $tips[$cll];
}

echo $cll . "\n";

?>
