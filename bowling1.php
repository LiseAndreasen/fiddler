<?php

// constants

$test = 0;

$min = 0;
$max = 1;

$scoreval = 0;
$sequence = 1;

// number of frames
// this is just a toy example! use 2, 3 or (if patient) 4
$frames = 2;

// functions

function add_frame($bowls_so_far, $frames_left) {
	global $scores, $min, $max, $scoreval, $sequence, $frames;

	$dont_count_yet = 0;
	if($frames_left <= 0) {
		// there might be frame extension!
		$no_of_frames = sizeof($bowls_so_far);
		$prev_was_strike = 0;
		$prev_was_spare = 0;
		$preprev_was_strike = 0;
		if(isset($bowls_so_far[$no_of_frames-1][2])
			&& strcmp($bowls_so_far[$no_of_frames-1][2], "s") == 0) {
			// previous was a strike
			$prev_was_strike = 1;			
			if(isset($bowls_so_far[$no_of_frames-2][2])
			&& strcmp($bowls_so_far[$no_of_frames-2][2], "s") == 0) {
				// the pre previous was also a strike
				$preprev_was_strike = 1;
			}
		}
		if(isset($bowls_so_far[$no_of_frames-1][2])
		&& strcmp($bowls_so_far[$no_of_frames-1][2], "p") == 0) {
			// previous was a spare
			$prev_was_spare = 1;
		}
		
		if($prev_was_strike == 1 || $prev_was_spare == 1) {
			if($no_of_frames < $frames + 1) {
				$dont_count_yet = 1;
			}
		}
		if($prev_was_strike == 1 && $preprev_was_strike == 1) {
			if($no_of_frames < $frames + 2) {
				$dont_count_yet = 1;
			}
		}

		if($dont_count_yet == 0) {
			// count pins and score for these bowls
			$all_bowls = sizeof($bowls_so_far);
			$score = 0;
			$pins = 0;
			
			for($bowls=0;$bowls<$all_bowls;$bowls++) {
				$pins += $bowls_so_far[$bowls][0]
						+ $bowls_so_far[$bowls][1];
				if($bowls < $frames) {
					$score += $bowls_so_far[$bowls][0]
							+ $bowls_so_far[$bowls][1];
					if(strcmp($bowls_so_far[$bowls][2], "s") == 0) {
						$score += $bowls_so_far[$bowls+1][0]
								+ $bowls_so_far[$bowls+1][1];
						if(strcmp($bowls_so_far[$bowls+1][2], "s") == 0) {
							$score += $bowls_so_far[$bowls+2][0];
						}
					}
					if(strcmp($bowls_so_far[$bowls][2], "p") == 0) {
						$score += $bowls_so_far[$bowls+1][0];
					}
				}
			}
			
			// we found a new min?
			if($scores[$pins][$min][$scoreval] > $score) {
				$scores[$pins][$min][$scoreval] = $score;
				$scores[$pins][$min][$sequence] = $bowls_so_far;
			}
			
			// we found a new max?
			if($scores[$pins][$max][$scoreval] < $score) {
				$scores[$pins][$max][$scoreval] = $score;
				$scores[$pins][$max][$sequence] = $bowls_so_far;
			}
			
			return;
		} // if($dont_count_yet == 0)
	} // if($frames_left == 0)
	
	for($bowl1=0;$bowl1<=10;$bowl1++) {
		// special case
		// frame extension, "last" and "last+1" were strikes
		// stop now!
		
		// special case
		// frame extension, "last" was a spare
		// stop now!
		if($dont_count_yet == 1
		&& (($prev_was_strike == 1 && $preprev_was_strike == 1)
		|| $prev_was_spare == 1)) {
			$tmp_bowls_so_far = $bowls_so_far;
			// ordinary?
			$type = "o";
			if($bowl1 == 10) {
				// strike
				$type = "s";
			}
			$tmp_bowls_so_far[] = array($bowl1, 0, $type);
			add_frame($tmp_bowls_so_far, $frames_left - 1);
			continue;		
		}
	
		for($bowl2=0;$bowl2<=10-$bowl1;$bowl2++) {
			$tmp_bowls_so_far = $bowls_so_far;
			// ordinary?
			$type = "o";
			if($bowl1 + $bowl2 == 10) {
				// sPare
				$type = "p";
			}
			if($bowl1 == 10) {
				// strike
				$type = "s";
			}
			$tmp_bowls_so_far[] = array($bowl1, $bowl2, $type);
			add_frame($tmp_bowls_so_far, $frames_left - 1);
		}
	}
}

function print_sequence($seq) {
	print("[");
	foreach($seq as $frame) {
		print("(");
		$comma = "";
		foreach($frame as $bowl) {
			print "$comma$bowl";
			$comma = ", ";
		}
		print(")");
	}
	print("]");
}

function print_scores() {
	global $scores, $min, $max, $scoreval, $sequence, $test;
	print("Pins | Min score | Max score\n");
	foreach($scores as $pins => $minmax) {
		printf(" %3d |    %3d    |    %3d", $pins,
			$minmax[$min][$scoreval], $minmax[$max][$scoreval]);
		if($test == 1) {
			print("      ");
			print_sequence($minmax[$min][$sequence]);
			print("\n                               ");
			print_sequence($minmax[$max][$sequence]);
		}
		print("\n");
	}
}

//////////////////////////////////////////////

// structure, score, min pins, max pins, sequence of bowls
for($pins=0;$pins<=($frames+2)*10;$pins++) {
	$scores[$pins][$min][$scoreval] = 301;
	$scores[$pins][$max][$scoreval] = -1;
	$scores[$pins][$min][$sequence] = array();
	$scores[$pins][$max][$sequence] = array();
}

add_frame(array(), $frames);

print_scores();

?>
