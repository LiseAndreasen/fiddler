<?php

///////////////////////////////////////////////////////////////////////////
// constants

// result obtained elsewhere should be confirmed
$no_of_concerts = 138;

$loops = 100000;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

///////////////////////////////////////////////////////////////////////////
// main program

$no_of_scandals = 0;

// big loop
for($i=1;$i<=$loops;$i++) {
	$scandal_detected = 0;
	// all the concerts
	for($j=1;$j<=$no_of_concerts;$j++) {
		// will the special couple be shown on the jumbotron?
		if(random_0_1() <= 0.01) {
			// will they be canoodling at the time?
			if(random_0_1() <= 0.5) {
				$scandal_detected = 1;
				break;
			}
		}
	}
	if($scandal_detected == 1) {
		$no_of_scandals++;
	}
}

print("Frequency of scandals: " . $no_of_scandals / $loops . "\n");

?>
