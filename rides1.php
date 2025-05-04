<?php

// constants

// fiddler or extra credit?
$fiddler = 0;

// number of times through loop
$loops = 100000;

// functions

// choose nth zero value in array
function choose_this($n, $arr) {
	// so far none chosen
	$chosen = 0;
	foreach($arr as $key => $elem) {
		if($elem == 0) {
			$chosen++;
		}
		if($chosen == $n) {
			return $key;
		}
	}
}

// find latest booked slot
function latest_booked($arr) {
	for($i=11;$i>=2;$i--) {
		if($arr[$i] == 1) {
			return $i;
		}
	}
}

function still_left($slots, $time) {
	$sum = 0;
	for($i=$time;$i<12;$i++) {
		if($slots[$i] == 0) {
			$sum++;
		}
	}
	return $sum;
}

//////////////////////////////////////////////

// sum of results
$sum = 0;

// loop of experiments
for($j=0;$j<$loops;$j++) {
	// there are 12 time slots
	// at the beginning none of them are booked
	for($i=0;$i<12;$i++) {
		$slots[$i] = 0;
	}

	// then 3 are booked, at any time, not conflicting
	for($i=0;$i<3;$i++) {
		// how many are still available?
		$left = 12 - array_sum($slots);
		// choose one of these
		$chosen = rand(1, $left);
		$chosen_empty = choose_this($chosen, $slots);
		$slots[$chosen_empty] = 1;
	}

	// keep riding and choosing a next, until the day is over
	for($i=0;$i<12;$i++) {
		if($slots[$i] == 1) {
			// this slot is booked
			// time to book a new slot
			if($fiddler == 1) {
				// next booked slot must be later than latest already booked
				
				// when is the latest booked slot?
				$latest = latest_booked($slots);
				
				// we might be done
				if($latest == 11) {
					break;
				}

				// book!
				// choose one of those after the latest
				$chosen = rand($latest + 1, 11);
				$slots[$chosen] = 1;
			} else {
				// next booked slot must be later than now
				$now = $i;
				// we might be done
				if($now == 11) {
					break;
				}

				// book!
				// choose one of those after now
				
				$all_left = still_left($slots, 0);
				$left = still_left($slots, $i);
				if($left == 0) {
					// we're done
					break;
				} else {
					// choose an empty slot after now
					$chosen = rand(1, $left) + $all_left - $left;
					$chosen_empty = choose_this($chosen, $slots);
					
					$slots[$chosen_empty] = 1;
				}
			}
		} else {
		}
	}

	$sum += array_sum($slots);
}

printf("Expected number of rides: %1.4f\n", $sum / $loops);

?>
