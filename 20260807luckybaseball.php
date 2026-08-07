<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

///////////////////////////////////////////////////////////////////////////
// main program

$loops = 10000000;
$no_of_games = 162;
$winner_wins = 0;
for($i=0;$i<$loops;$i++) {
	// progress
	if($i % 100000 == 0) {
		printf("%.5f ", $i/$loops);
	}
	$no_of_wins = 0;
	for($j=0;$j<$no_of_games;$j++) {
		// arbitrarily measure wins for 1st team
		if(0.5 <= random_0_1()) {
			$no_of_wins++;
		}
	}
	$winner_wins += max($no_of_wins, $no_of_games - $no_of_wins) - $no_of_games/2;
}

printf("\nResult 1: %.5f + %.1f = %.5f\n", $winner_wins / $loops,
	$no_of_games/2, $winner_wins / $loops + $no_of_games/2);
// (5.069 - 5.071) + 81 = 86.069 - 86.071

///////////////////////////////////////////////////////////////////////////

$loops = 100000;
$no_of_separate_games = 5;
$no_of_teams = 30;
$no_of_games = $no_of_separate_games * ($no_of_teams - 1);
$winner_wins = 0;
for($i=0;$i<$loops;$i++) {
	// progress
	if($i % 10000 == 0) {
		printf("%.5f ", $i/$loops);
	}
	$no_of_wins = array_fill(0, $no_of_teams, 0);
	for($j=0;$j<$no_of_teams;$j++) {
		for($k=0;$k<$no_of_teams;$k++) {
			if($j<=$k) {
				break;
			}
			for($l=0;$l<$no_of_separate_games;$l++) {
				if(0.5 <= random_0_1()) {
					$no_of_wins[$j]++;
				} else {
					$no_of_wins[$k]++;
				}
			}
		}
	}
	$winner_wins += max($no_of_wins) - $no_of_games/2;
}

printf("\nResult 2: %.5f + %.1f = %.5f\n", $winner_wins / $loops,
	$no_of_games/2, $winner_wins / $loops + $no_of_games/2);
// (12.47 - 12.49) + 72.5 = 84.97 - 84.99

?>
