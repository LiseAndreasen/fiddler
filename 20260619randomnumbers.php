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

print("Fiddler:\n\n");
printf("%12s   %10s   %10s   %10s\n", "actual loops", "good loops",
	"delta", "average a");

// delta: accepted distance between result and 0.5
$loops = 1000;
$loops_p = 100000;		// progress counter

for($delta=0.1;0.0001<=$delta;$delta*=0.2) {
	$a_sum = 0;
	$loops_good = 0;
	$loops_actual = 0;

	while($loops_good < $loops) {
		$a = random_0_1();
		$b = random_0_1() * $a;		// random number, [0-a]
		if(abs($b - 0.5) < $delta) {
			$a_sum += $a;
			$loops_good++;
			if($loops_good % $loops_p == 0) { print("p"); }
		}
		$loops_actual++;
	}
	
	if($loops_p < $loops_good) { print("\n"); }
	printf("%12d   %10d   %10.6f   %10.6f\n", $loops_actual, $loops,
		$delta, $a_sum / $loops_good);
}

print("\n");

///////////////////////////////////////////////////////////////////////////
// extra credit

printf("Extra credit:\n\n");
printf("%12s   %10s   %10s   %10s\n", "actual loops", "good loops",
	"", "average a");

$loops = 1000;

$a_sum = 0;
$loops_good = 0;
$loops_actual = 0;

while($loops_good < $loops) {
	$a = random_0_1();
	$b = random_0_1() * $a;		// random number, [0-a]
	if($b <= 0.5) {
		$a_sum += $a;
		$loops_good++;
		if($loops_good % $loops_p == 0) { print("p"); }
	}
	$loops_actual++;
}

if($loops_p < $loops_good) { print("\n"); }
printf("%12d   %10d   %10s   %10.6f\n", $loops_actual, $loops,
	"", $a_sum / $loops_good);

?>
