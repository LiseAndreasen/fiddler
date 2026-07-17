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
			// 2nd game, energy of opponent
			// 1 - random_0_1 has same distribution as random_0_1
			$e2_op = random_0_1();
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
		if(0.23 <= $wins/$loops) {
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

$strategies = [];

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
				// 2nd game, energy of opponent
				// spent random_0_1 in 1st game
				// now uses random amount of 1 - random_0_1 in 2nd game
				$e2_op = random_0_1() * random_0_1();
				if($e2 < $e2_op) {
					// we lost
				} else {
					// 3rd game, energy of opponent
					$e3_op = random_0_1() * random_0_1();
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
		if(0.1785 <= $wins/$loops) {
			$winner_string .= sprintf("\nE1: %.4f E2: %.4f Wins: %.7f",
				$e1/$no_of_strategies, $e2/$no_of_strategies, $wins/$loops);
		}
		if($wins_max < $wins) {
			$wins_max = $wins;
		}
	}
}

printf("Result 2, max wins: %.5f\n%s\n\n", $wins_max/$loops, $winner_string);

?>
