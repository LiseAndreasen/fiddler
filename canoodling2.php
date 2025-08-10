<?php

///////////////////////////////////////////////////////////////////////////
// constants

// no of couples
$sqrt_noc = 10;
$no_of_couples = pow($sqrt_noc, 2);

// no of appearances
$appearances = 10000;
$dot_freq = 1000;

// no of experiments
$loops = 10;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

function print_histogram() {
	global $couples, $heatmap, $sqrt_noc;
	// print histogram
	for($i=1;$i<10;$i++) {
		$histogram[$i] = "";
	}
	$histogram[0] = "0";
	for($i=1;$i<10;$i++) {
		$histogram[0] .= "---------$i";
	}
	$histogram[0] .= "---------A";

	// populate buckets
	for($i=0;$i<100;$i++) {
		$bucket[$i] = 0;
	}
	for($i=0;$i<$sqrt_noc;$i++) {
		for($j=0;$j<$sqrt_noc;$j++) {
			$bucketval = (int) ($couples[$i][$j] * 100);
			$bucketfreq = $heatmap[$i][$j];
			$bucket[$bucketval] += $bucketfreq;
		}
	}
	$max_bucket = max($bucket) + 1;
	$histogram_tops = "";

	for($i=0;$i<100;$i++) {
		$column_top = (int) (10 * $bucket[$i] / $max_bucket);
		for($j=1;$j<=$column_top;$j++) {
			$histogram[$j] .= "*";
		}
		for($j=$column_top+1;$j<10;$j++) {
			$histogram[$j] .= " ";
		}
		if($column_top == 9) {
			$histogram_tops .= "Probability " . $i / 100 . " appeared " . $bucket[$i] . " times.\n";
		}
	}
	for($i=9;$i>=0;$i--) {
		print($histogram[$i] . "\n");
	}
	print("Histogram shows probabilities from 0 through 0.1 and 0.9 to 1 (0, 1, 9, A).\n");

	print($histogram_tops);
}

///////////////////////////////////////////////////////////////////////////
// main program

echo "The time is " . date("h:i:sa" . "\n");

// initialize heatmap
for($i=0;$i<$sqrt_noc;$i++) {
	for($j=0;$j<$sqrt_noc;$j++) {
		$heatmap[$i][$j] = 0;
	}
}

for($l=0;$l<$loops;$l++) {
	// populate the audience probabilities in a regular way
	// assuming there are 10^2 couples
	// the couple in coordinates (0,0) will have probabilites 0.05 and 0.05
	for($i=0;$i<$sqrt_noc;$i++) {
		for($j=0;$j<$sqrt_noc;$j++) {
			// $couples[$i][$j] = ($i + 0.5)/$sqrt_noc * ($j + 0.5)/$sqrt_noc;
			$couples[$i][$j] =
				($i + random_0_1())/$sqrt_noc * ($j + random_0_1())/$sqrt_noc;
		}
	}

	for($k=0;$k<$appearances;$k++) {
		if($k % $dot_freq == 0) {
			echo ".";
		}
		$canoodling = array();
		for($i=0;$i<$sqrt_noc;$i++) {
			for($j=0;$j<$sqrt_noc;$j++) {
				if(random_0_1() < $couples[$i][$j]) {
					// remember this couple
					$canoodling[] = array($i, $j);
				}
			}
		}
		
		// pick random couple
		shuffle($canoodling);
		$i = $canoodling[0][0];
		$j = $canoodling[0][1];
		$heatmap[$i][$j]++;
	}
	echo "\n";
}

print_histogram();

printf("%d loops, %d appearances in a concert, %d couples\n", $loops, $appearances, $no_of_couples);

echo "The time is " . date("h:i:sa" . "\n");

?>
