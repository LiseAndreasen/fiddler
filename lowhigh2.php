<?php

///////////////////////////////////////////////////////////////////////////
// constants

// how many loops?
$loops = 2000;

// max length of chain?
// 50 takes too long
$chain_length = 30;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

function print_histogram($data) {
	$no_of_buckets = sizeof($data);
	$max_bucket = max($data) + 1;
	// print histogram
	// height: 9
	for($i=1;$i<10;$i++) {
		$histogram[$i] = "";
	}
	$histogram[0] = "+";
	// assuming no of buckets less than width of screen
	for($i=1;$i<=1+$no_of_buckets/10;$i++) {
		$histogram[0] .= "---------+";
	}

	for($i=0;$i<$no_of_buckets;$i++) {
		$column_top = (int) (10 * $data[$i] / $max_bucket);
		for($j=1;$j<=$column_top;$j++) {
			$histogram[$j] .= "*";
		}
		for($j=$column_top+1;$j<10;$j++) {
			$histogram[$j] .= " ";
		}
	}
	for($i=9;$i>=0;$i--) {
		print($histogram[$i] . "\n");
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

// populate structure for measuring nth value of long chain
for($i=10;$i<=$chain_length;$i+=10) {
	for($j=0;$j<=100;$j++) {
		$chain_data[$i][$j] = 0;
		$chain_guesses[$i] = 0;
		$chain_wins[$i] = 0;
	}
}

for($j=0;$j<$loops;$j++) {
	// keep going until we have a chain of more than chain length
	$chain = array();
	$i = 0;
	while($i <= $chain_length + 1) {
		$chain[$i] = random_0_1();
		if($i > 0) {
			// the chain already has some length
			if(($chain[$i-1] < 0.5 && $chain[$i] > $chain[$i-1])
				|| ($chain[$i-1] > 0.5 && $chain[$i] < $chain[$i-1]))
			{
				if($i % 10 == 0) {
					$chain_val = (int) ($chain[$i] * 100);
					$chain_data[$i][$chain_val]++;
				}
				if($i % 10 == 1 && $i > 2) {
					// having a chain of length n,
					// the next guess was a win
					$chain_guesses[$i-1]++;
					$chain_wins[$i-1]++;
				}
				$i++;
			} else {
				// oops, lost
				if($i % 10 == 1 && $i > 2) {
					// having a chain of length n,
					// the next guess was a loss
					$chain_guesses[$i-1]++;
				}
				$chain = array();
				$i = 0;
			}
		} else {
			// this is the beginning of the chain
			$i++;
		}
	}
	if($j % 100 == 0) {
		print(".");
	}
}
print("\n");

/*
for($i=10;$i<=$chain_length;$i+=10) {
	print("Histogram for chains length $i.\n");
	print_histogram($chain_data[$i]);
}

// print data for csv
for($j=0;$j<=100;$j++) {
	print("$j");
	for($i=10;$i<=$chain_length;$i+=10) {
		print(";" . $chain_data[$i][$j]);
	}
	print("\n");
}
*/

for($i=10;$i<=$chain_length;$i+=10) {
	printf("Probability for win at length %d: %.5f\n", $i, $chain_wins[$i] / $chain_guesses[$i]);
}


?>
