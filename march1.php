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

// a loop where k grows
for($k=2;$k<=10;$k++) {
	// the bracket has 2^k teams

	$wins = 0;

	// monte carlo loop
	for($j=0;$j<$n;$j++) {
		// populate bracket
		for($i=1;$i<=pow(2,$k);$i++) {
			$seed[$i] = $i;
		}

		// $k rounds of matches	
		for($l=$k;$l>=1;$l--) {
			for($i=1;$i<=pow(2,$l-1);$i++) {
				// team a: i
				// team b: 2^l + 1 - i
				// width of bracket: 2^l
				$width = pow(2,$l);
				// probability team a wins
				$p = $seed[$width + 1 - $i] / ($seed[$i] + $seed[$width + 1 - $i]);
				if(random_0_1() > $p) {
					// team b won, move them in the bracket
					$seed[$i] = $seed[$width + 1 - $i];
				}
			}
			if($seed[1] != 1) {
				continue;
			}
		}
		
		if($seed[1] == 1) {
			$wins++;
		}
	}

	print("For k = " . $k . ": Probability the 1-seed wins: " . $wins/$n . "\n");
}

?>
