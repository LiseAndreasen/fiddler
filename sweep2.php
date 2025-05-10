<?php

// constants

// number of loops for each experiment
$loops = 10000;

// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

// auxiliary function
// returns random number with flat distribution from 0.6 to 0.75
function random_06_075() 
{
	$tmp = (float)rand() / (float)getrandmax();
	$tmp = $tmp * (0.75 - 0.6) + 0.6;
    return $tmp;
}

//////////////////////////////////////////////

$all_loops = 0;
$good_loops = 0;
// loop on random p
for($i=0;$i<$loops;$i++) {
	$p = random_06_075();
	
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
	
	$all_loops++;
	
	if($no_games[4] > $no_games[7] + $no_games[-7]) {
		$good_loops++;
	}
}
	
printf("p4 > p7 with probability %.4f.\n", $good_loops / $all_loops);
	
?>
