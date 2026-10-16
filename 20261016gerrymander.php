<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 10000;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

///////////////////////////////////////////////////////////////////////////
// main program

print("5 districts with p = 0.7, 2 districts with p = 0\n");

$wins5 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = true;
	for($i=0;$i<5;$i++) {
		if(0.7 < random_0_1()) {
			$winning_this_one = false;
			break;
		}
	}
	if($winning_this_one) {
		$wins5++;
	}
}

printf("Wins: %.f\n", $wins5/$loops);

print("4 districts with p = 0.875, 3 districts with p = 0\n");

$wins4 = 0;
for($l=0;$l<$loops;$l++) {
	$winning_this_one = true;
	for($i=0;$i<4;$i++) {
		if(0.875 < random_0_1()) {
			$winning_this_one = false;
			break;
		}
	}
	if($winning_this_one) {
		$wins4++;
	}
}

printf("Wins: %.f\n", $wins4/$loops);

printf("Difference: %.f\n", $wins4/$loops - $wins5/$loops);

?>
