<?php

// constants

// whether it's fiddler or extra credit
$fiddler = 1;

// number of loops for each experiment
$loops = 100000;

// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

//////////////////////////////////////////////

// remember the good p's
$good_p = array();

// loop on p
$p_step = 0.001;
$p_loops = 0;
$good_loops = 0;

if($fiddler == 1) {
	$p_begin = $p_step;
	$p_end = 1 - $p_step;
} else {
	$p_begin = 0.6 + $p_step;
	$p_end = 0.75 - $p_step;
}

for($p=$p_begin;$p<=$p_end;$p+=$p_step) {
	// bins for remembering number of games in a series
	for($j=4;$j<=7;$j++) {
		$no_games[$j] = 0;
		$no_games[-$j] = 0;
	}
	
	// loop on series
	for($j=0;$j<$loops;$j++) {
		$wins = 0;
		$losses = 0;
		// loop on games
		for($k=0;$k<7;$k++) {
			if(random_0_1() < $p) {
				$wins++;
			} else {
				$losses++;
			}
			if(max($wins, $losses) == 4) {
				// we have a winner
				break;
			}
		}
		if($wins == 4) {
			$no_games[$wins + $losses]++;
		} else {
			$no_games[- ($wins + $losses)]++;
		}
	}
	
	if($no_games[5] == max($no_games)) {
		$good_p[] = $p;
	}
	
	if($no_games[4] > $no_games[7] + $no_games[-7]) {
		$good_loops++;
	}
	
	$p_loops++;
}

if($fiddler == 1) {
	printf("Interval goes from %.3f to %.3f.\n", min($good_p), max($good_p));
} else {
	printf("p4 > p7 with probability %.4f.\n", $good_loops / $p_loops);
}
	
?>
