<?php

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

/////////////////////////
// big, big loop

$loops = 5000000;
$all_wins = 0;

for($i=0;$i<$loops;$i++) {

	$legs_a = random_0_1();
	$legs_b = random_0_1();
	$legs_c = random_0_1();

	if($legs_a >= random_0_1()) {
		$sprint_a = 1;
	} else {
		$sprint_a = 0;
	}
	if($legs_b >= 0.5) {
		$sprint_b = 1;
	} else {
		$sprint_b = 0;
	}
	if($legs_c >= 0.5) {
		$sprint_c = 1;
	} else {
		$sprint_c = 0;
	}

	$i_win = 0;
	if($sprint_a == 1) {
		if($sprint_b == 0 || ($sprint_b == 1 && $legs_a > $legs_b)) {
			if($sprint_c == 0 || ($sprint_c == 1 && $legs_a > $legs_c)) {
				$i_win = 1;
			}
		}
	} else {
		if($sprint_b == 0 && $sprint_c == 0) {
			if(random_0_1() < 1/3) {
				$i_win = 1;
			}
		}
	}

	if($i_win == 1) {
		$all_wins++;
	}

}

printf("My wins: %.3f\n", $all_wins / $loops);

?>
