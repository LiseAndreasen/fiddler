<?php

///////////////////////////////////////////////////////////////////////////
// constants

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

printf("Go for %d wins, %d districts with p = %.3f, %d districts with p = 0\n", 5, 5, 0.7, 2);

$wins5_5 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = true;
	for($i=0;$i<5;$i++) {
		if(0.7 < random_0_1()) {
			$winning_this_one = false;
			break;
		}
	}
	if($winning_this_one) {
		$wins5_5++;
	}
}

printf("Wins: %.f\n", $wins5_5/$loops);

$p = 7.0 / 12;

printf("Go for %d wins, %d districts with p = %.3f, %d districts with p = 0\n", 5, 6, $p, 1);

$wins5_6 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = 0;
	for($i=0;$i<6;$i++) {
		if(random_0_1() <= $p) {
			$winning_this_one++;
		}
	}
	if(5 <= $winning_this_one) {
		$wins5_6++;
	}
}

printf("Wins: %.f\n", $wins5_6/$loops);

printf("Go for %d wins, %d districts with p = %.3f, %d districts with p = 0\n", 5, 7, 0.5, 0);

$wins5_7 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = 0;
	for($i=0;$i<7;$i++) {
		if(random_0_1() <= 0.5) {
			$winning_this_one++;
		}
	}
	if(5 <= $winning_this_one) {
		$wins5_7++;
	}
}

printf("Wins: %.f\n", $wins5_7/$loops);

print("\n");

printf("Go for %d wins, %d districts with p = %.3f, %d districts with p = 0\n", 4, 4, 0.875, 3);

$wins4_4 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = true;
	for($i=0;$i<4;$i++) {
		if(0.875 < random_0_1()) {
			$winning_this_one = false;
			break;
		}
	}
	if($winning_this_one) {
		$wins4_4++;
	}
}

printf("Wins: %.f\n", $wins4_4/$loops);

printf("Go for %d wins, %d districts with p = %.3f, %d districts with p = 0\n", 4, 5, 0.7, 2);

$wins4_5 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = 0;
	for($i=0;$i<5;$i++) {
		if(random_0_1() <= 0.7) {
			$winning_this_one++;
		}
	}
	if(4 <= $winning_this_one) {
		$wins4_5++;
	}
}

printf("Wins: %.f\n", $wins4_5/$loops);

printf("Go for %d wins, %d districts with p = %.3f, %d districts with p = 0\n", 4, 6, $p, 1);

$wins4_6 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = 0;
	for($i=0;$i<6;$i++) {
		if(random_0_1() <= $p) {
			$winning_this_one++;
		}
	}
	if(4 <= $winning_this_one) {
		$wins4_6++;
	}
}

printf("Wins: %.f\n", $wins4_6/$loops);

printf("Go for %d wins, %d districts with p = %.3f, %d districts with p = 0\n", 4, 7, 0.5, 0);

$wins4_7 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = 0;
	for($i=0;$i<7;$i++) {
		if(random_0_1() <= 0.5) {
			$winning_this_one++;
		}
	}
	if(4 <= $winning_this_one) {
		$wins4_7++;
	}
}

printf("Wins: %.f\n", $wins4_7/$loops);

print("\n");

printf("Difference: %.f\n", $wins4_4/$loops - $wins5_7/$loops);

?>
