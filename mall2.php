<?php

//////////////////////////////////////////////////////////////////////////
// constants

// length of segment, in sceonds
$length = 120;

// number of time segments
$segments = 3600 / $length;

// quarter of an hour expressed as time segments
$quarter = $segments / 4;

//////////////////////////////////////////////////////////////////////////
// functions

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

// if this changes, "//" will also have to be removed below
$friends = 3;

// initialize count of friends
for($i=1;$i<=$friends;$i++) {
	$max_friends[$i] = 0;
}

// go through each possible time segment for each friend

// 1st friends
for($f1=0;$f1<$segments;$f1++) {
	$arrival[1] = ($f1 + 0.5) / $segments;
	// 2nd friend
	for($f2=0;$f2<$segments;$f2++) {
		$arrival[2] = ($f2 + 0.5) / $segments;
		// 3rd friend
		for($f3=0;$f3<$segments;$f3++) {
			$arrival[3] = ($f3 + 0.5) / $segments;
			// 4th friend
//			for($f4=0;$f4<$segments;$f4++) {
//				$arrival[4] = ($f4 + 0.5) / $segments;
				$max_friends_now = count_friends($arrival);
				$max_friends[$max_friends_now]++;
//			} // 4
		} // 3
	} // 2
} // 1

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
print("Organized run through a defined group of possibilities.\n");
printf("With %d friends in all, a maximum group of %.5f friends is expected.\n",
	$friends, $exp);
?>
