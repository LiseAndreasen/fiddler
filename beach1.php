<?php

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

// number of times through loop
$n = 1000000;

/////////////

$hits = 0;
$dist = 0;

for($i=0;$i<$n;$i++) {
// monte carlo loop
	// choose random x, y within semi-disk
	$x = random_0_1();
	$y = random_0_1();
	while ($x * $x + $y * $y > 1) {
		$x = random_0_1();
		$y = random_0_1();
	}

	// distance to semi-circle:
	// 1 - (x^2 + y^2)^0.5
	$d1 = 1 - pow($x * $x + $y * $y, 0.5);

	// distance to diameter
	// y
	$d2 = $y;
	
	// did we land closest to the diameter?
	if($d1 > $d2) {
		$hits++;
		$dist += $d2;
	} else {
		$dist += $d1;
	}
}

print("Probability closest to diameter: " . $hits/$n."\n");
print("Average distance to beach:...... " . $dist/$n."\n");

?>
