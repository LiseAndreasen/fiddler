<?php

//////////////////////////////////////////////////////////////////////////
// constants

// how many friends are meeting?
$friends = 3;

// how many times through the monte carlo loop?
$loops = 10000000;

//////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

// count max group of friends based on arrival times
function count_friends($arrival) {
	// sort these arrival times
	sort($arrival);
	
	// the interesting points are the arrival times
	// when each person arrives, how many are already here?
	$max_friends = 1;
	foreach($arrival as $key => $person) {
		$my_time = $person;
		$my_group = 1;
		for($i=0;$i<$key;$i++) {
			if($my_time < $arrival[$i] + 0.25) {
				// this person is still here
				$my_group++;
			}
		}
		if($max_friends < $my_group) {
			$max_friends = $my_group;
		}
	}
	return $max_friends;
}

//////////////////////////////////////////////////////////////////////////
// main program

// initialize count of friends
for($i=1;$i<=$friends;$i++) {
	$max_friends[$i] = 0;
}

// loop
for($j=0;$j<$loops;$j++) {
	$arrival = array();

	// choose random arrival time for each friend
	// expressed as hours after 3 p.m.
	for($i=1;$i<=$friends;$i++) {
		$arrival[$i] = random_0_1();
	}
	
	$max_friends_now = count_friends($arrival);
	$max_friends[$max_friends_now]++;
}
// end loop

// print counts, might be useful later
print_r($max_friends);

// calculate expected value
$val_sum = 0;
$num_sum = 0;
foreach($max_friends as $val => $num) {
	$val_sum += $val * $num;
	$num_sum += $num;
}

$exp = $val_sum / $num_sum;
print("Monte carlo simulation.\n");
printf("With %d friends in all, a maximum group of %.5f friends is expected.\n",
	$friends, $exp);
?>
