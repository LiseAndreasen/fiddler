<?php

///////////////////////////////////////////////////////////////////////////
// constants

// 1st time: 100000
// 2nd time: 1000000
$loops = 1000000;

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

function print_python($map, $file, $s_min, $s_max, $t_min, $t_max) {
	$file_name = "worldcup_" . $file . ".py";
	$myfile = fopen($file_name, "w") or die("Unable to open file!");
	
	global $loops;
	fwrite($myfile, "$file = [\n");
	foreach($map as $row) {
		$str = "[";
		foreach($row as $cell) {
			$str .= sprintf("%.5f,", $cell/$loops);
		}
		for($i=sizeof($row);$i<sizeof($map[min(array_keys($map))]);$i++) {
			$str .= sprintf("%.5f,", 0);
		}
		$str .= "],\n";
		fwrite($myfile, $str);
	}
	fwrite($myfile, "]\n");
	$str = sprintf("s_min = %.5f\n", $s_min);
	fwrite($myfile, $str);
	$str = sprintf("s_max = %.5f\n", $s_max);
	fwrite($myfile, $str);
	$str = sprintf("t_min = %.5f\n", $t_min);
	fwrite($myfile, $str);
	$str = sprintf("t_max = %.5f\n", $t_max);
	fwrite($myfile, $str);
	fclose($myfile);
}

///////////////////////////////////////////////////////////////////////////
// main program

// difference between strategies, s - s_previous = this
// 1st time:
// 0.01 / 1 / no_of_strategies / 0.37
// 2nd time:
// 0.001 / 0.48 * no_of_strategies / 0.66 * no_of_strategies / 0.38
$strategy_diff = 0.001;
$no_of_strategies = floor(1 / $strategy_diff);
$s_min = 0.48 * $no_of_strategies;
$s_max = 0.66 * $no_of_strategies;
$wins_cutoff = 0.384;

for($s=$s_min;$s<$s_max;$s++) {
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
		if($wins_cutoff <= $wins/$loops) {
			$winner_string .= sprintf("\nE1: %.4f E2: %.4f Wins: %.7f",
				$e1/$no_of_strategies, $e2/$no_of_strategies, $wins/$loops);
		}
		if($wins_max < $wins) {
			$wins_max = $wins;
		}
	}
}

printf("Result 1, max wins: %.5f\n%s\n\n", $wins_max/$loops, $winner_string);

print_python($strategies, "data1", $s_min/$no_of_strategies,
	$s_max/$no_of_strategies, 0, 0);

///////////////////////////////////////////////////////////////////////////

$strategies = [];
// 1st time:
// 0.01 / 1 / no_of_strategies / 1 / no_of_strategies / 0.2775
// 2nd time:
// 0.001 / 0.47 * no_of_strategies / 0.58 * no_of_strategies /
//			0.2 * no_of_strategies / 0.28 * no_of_strategies / 0.2821
$strategy_diff = 0.001;
$no_of_strategies = floor(1 / $strategy_diff);
$s_min = 0.47 * $no_of_strategies;
$s_max = 0.58 * $no_of_strategies;
$t_min = 0.2 * $no_of_strategies;
$t_max = 0.28 * $no_of_strategies;
$wins_cutoff = 0.2821;

for($s=$s_min;$s<$s_max;$s++) {
	for($t=$t_min;$t<$t_max;$t++) {
		if($no_of_strategies < $s + $t) {
			break;
		}
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
		if($wins_cutoff <= $wins/$loops) {
			$winner_string .= sprintf("\nE1: %.4f E2: %.4f Wins: %.7f",
				$e1/$no_of_strategies, $e2/$no_of_strategies, $wins/$loops);
		}
		if($wins_max < $wins) {
			$wins_max = $wins;
		}
	}
}

printf("Result 2, max wins: %.5f\n%s\n\n", $wins_max/$loops, $winner_string);

print_python($strategies, "data2", $s_min/$no_of_strategies,
	$s_max/$no_of_strategies, $t_min/$no_of_strategies,
	$t_max/$no_of_strategies);

?>
