<?php

// constants

// number of loops in experiment
$loops = 1000000;

// functions

//////////////////////////////////////////////

// sum of length of rivers
$sum = 0;

// produce loops rivers
for($i=0;$i<$loops;$i++) {
	// produce lines until the river ends
	// the river begins in line 1, position 12
	$pos = 12;
	// length
	$len = 1;
	// has river ended?
	$ended = 0;
	while($ended == 0) {
		$pos++;
		// produce line
		$linepos = 0;
		// produce words until we're past the river part
		while($linepos < $pos) {
			$word_len = 3 + rand(0, 1);
			$linepos += $word_len + 1;
		}
		if($linepos == $pos) {
			// the river continues
			$len++;
		} else {
			$ended = 1;
		}
	}
	$sum += $len;
}

// calculate average
printf("Average length of river: %.4f\n", $sum / $loops);

?>
