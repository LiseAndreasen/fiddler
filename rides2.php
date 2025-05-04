<?php

// constants

// fiddler or extra credit?
$fiddler = 0;

// should a slot be added after the latest
// or just not conflicting?
$not_conflicting = 0;
$after_latest = 1;

// beginning situation
$mode = $not_conflicting;

// beginning time
// later: the time for a slot being used
$time = 0;

// latest booked at the beginning
$latest = -1;

// functions

function reset_slots() {
	global $slots;
	for($i=0;$i<12;$i++) {
		$slots[$i] = 0;
		$slots_order[$i] = ".";
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

// add an extra slot to the program, trying all options
function add_slots($slots, $mode, $time, $latest) {
	// we arrive here, because the slot at time $time is being used!
	
	global $sum, $after_latest, $fiddler;

	// how many are still available?
	$all_left = still_left($slots, 0);
	$left = still_left($slots, $time);

	// nothing was booked for this time slot, move on
	if($all_left <= 9 && $slots[$time] == 0) {
		return add_slots($slots, $mode, $time + 1, $latest);
	}
	
	// end case: the day is over, planning wise
	if($time == 12 || $left == 0 || ($mode == $after_latest && $latest == 11)) {
		return array_sum($slots);
	}
	
	// may have to change mode
	if($fiddler == 1 && $all_left == 9) {
		$mode = $after_latest;
	}
	
	// time only advances after 3 slots are booked
	if($all_left <= 9) {
		$thisTime = $time + 1;
	} else {
		$thisTime = $time;
	}

	// keep track of possible no of rides
	$local_sum = 0;
	// and number of tries
	$local_tries = 0;
	
	for($i=$time;$i<12;$i++) {
		if($mode == $after_latest && $i <= $latest) {
			// can't book something before the latest booking
			continue;
		}
		if($slots[$i] == 0) {
			// this slot is available, try!
			$slots[$i] = 1;
			$slots_order[$i] = 12 - $all_left + 1;
			if($latest < $i) {
				$this_latest = $i;
			} else {
				$this_latest = $latest;
			}
			$local_tries++;
			$local_sum += add_slots($slots, $mode, $thisTime, $this_latest);
			$slots[$i] = 0;			
			$slots_order[$i] = ".";
		}
	}
	
	if($local_tries == 0) {
		// couldn't add another ride
		return array_sum($slots);
	} else {
		return $local_sum / $local_tries;
	}
}

//////////////////////////////////////////////

// sum of results
$sum = 0;

// there are 12 time slots
// at the beginning none of them are booked
reset_slots();

// go recursively through all options
$expected = add_slots($slots, $mode, $time, $latest);

printf("Expected number of rides: %1.4f\n", $expected);

?>
