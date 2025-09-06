<?php

///////////////////////////////////////////////////////////////////////////
// constants

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

// keep going until we have a chain of more than 10
$chain = array();
$i = 0;
while($i < 50) {
	$chain[$i] = random_0_1();
	if($i > 0) {
		if(($chain[$i-1] < 0.5 && $chain[$i] > $chain[$i-1]) || ($chain[$i-1] > 0.5 && $chain[$i] < $chain[$i-1])) {
			$i++;
		} else {
			$chain = array();
			$i = 0;
		}
	} else {
		$i++;
	}
}

print_r($chain);

?>
