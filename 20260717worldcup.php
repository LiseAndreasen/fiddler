<?php

///////////////////////////////////////////////////////////////////////////
// constants

// difference between strategies, s - s_previous = this
$strategy_diff = 0.01;
$no_of_strategies = floor(1 / $strategy_diff);
$loops = 100000;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

// run little tournament with n rounds
function tournament($n) {
	$no_of_teams = pow(2, $n);
	for($i=0;$i<$no_of_teams;$i++) {
		$team_energy[$i] = 1;
	}
	$divider = 1;
	while(1 < $n) {
		for($i=0;$i<$no_of_teams;$i+=2*$divider) {
			$team1_e = $team_energy[$i] * random_0_1();
			$team2_e = $team_energy[$i+$divider] * random_0_1();
			if($team1_e < $team2_e) {
				// team 2 won
				// but remaining energy will be stored in team 1's spot
				$team_energy[$i] = $team_energy[$i+$divider] - $team2_e;
			} else {
				$team_energy[$i] = $team_energy[$i] - $team1_e;
			}
		}
		$divider *= 2;
		$n /= 2;
	}
	
	// winner of tournament will have this energy remaining
	return $team_energy[0];
}

function print_python($map) {
	print("[\n");
	foreach($map as $row) {
		print("[");
		foreach($row as $cell) {
			printf("%3d,", $cell);
		}
		for($i=sizeof($row);$i<sizeof($map[1]);$i++) {
			printf("%3d,", 0);
		}
		echo "],\n";
	}
	echo "]\n";
}

///////////////////////////////////////////////////////////////////////////
// main program

for($s=1;$s<$no_of_strategies;$s++) {
	$wins = 0;
	$e1 = $s / $no_of_strategies;
	for($l=0;$l<$loops;$l++) {
		// 1st game, energy of opponent
		$e1_op = random_0_1();
		if($e1 <= $e1_op) {
			// we lost
		} else {
			$e2_op = tournament(2);
			if(1 - $e1 < $e2_op) {
				// we lost
			} else {
				$wins++;
			}
		}
	}
	$strategies[$s][$no_of_strategies-$s] = $wins;
	print(".");
}
print("\n");

$wins_max = 0;
$winner_string = "";
foreach($strategies as $e1 => $substrategy) {
	foreach($substrategy as $e2 => $wins) {
		if(0.37 <= $wins/$loops) {
			$winner_string .= sprintf("\nE1: %.4f E2: %.4f Wins: %.7f",
				$e1/$no_of_strategies, $e2/$no_of_strategies, $wins/$loops);
		}
		if($wins_max < $wins) {
			$wins_max = $wins;
		}
	}
}

printf("Result 1, max wins: %.5f\n%s\n\n", $wins_max/$loops, $winner_string);

///////////////////////////////////////////////////////////////////////////

usleep(3000000);
print_python($strategies);
$strategies = [];
usleep(3000000);

for($s=1;$s<$no_of_strategies;$s++) {
	for($t=1;$t<$no_of_strategies-$s;$t++) {
		$wins = 0;
		$e1 = $s / $no_of_strategies;
		$e2 = $t / $no_of_strategies;
		$e3 = (1 - $e1 - $e2);
		for($l=0;$l<$loops;$l++) {
			// 1st game, energy of opponent
			$e1_op = random_0_1();
			if($e1 <= $e1_op) {
				// we lost
			} else {
				// 2nd game
				$e2_op = tournament(2) * random_0_1();
				if($e2 < $e2_op) {
					// we lost
				} else {
					// 3rd game
					$e3_op = tournament(4);
					if($e3 < $e3_op) {
						// we lost
					} else {
						$wins++;
					}
				}
			}
		}
		$strategies[$s][$t] = $wins;
		if($t%10==0) {
			print(".");
		}
	}
	print("#");
}
print("\n");

$wins_max = 0;
$winner_string = "";
foreach($strategies as $e1 => $substrategy) {
	foreach($substrategy as $e2 => $wins) {
		if(0.2775 <= $wins/$loops) {
			$winner_string .= sprintf("\nE1: %.4f E2: %.4f Wins: %.7f",
				$e1/$no_of_strategies, $e2/$no_of_strategies, $wins/$loops);
		}
		if($wins_max < $wins) {
			$wins_max = $wins;
		}
	}
}

printf("Result 2, max wins: %.5f\n%s\n\n", $wins_max/$loops, $winner_string);

usleep(3000000);
print_python($strategies);

?>
