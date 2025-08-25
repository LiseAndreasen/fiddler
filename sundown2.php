<?php

///////////////////////////////////////////////////////////////////////////
// constants

// the time left
// 5.55 - 7.00
// 65 minutes
$time_left = 65;

// 4 different sizes of loops, measured in minutes!
$loops = array(45, 35, 30, 10);
$no_of_loops = sizeof($loops);

$runs = 1000000;

$mulligans = array(
	00 => array("mull", "mull", "mull", "mull"),
	05 => array("mull", "mull", "mull", "mull"),
	10 => array("mull", "mull", "mull", "keep"),
	15 => array("mull", "mull", "mull", "keep"),
	20 => array("mull", "mull", "mull", "keep"),
	25 => array("mull", "mull", "mull", "keep"),
	30 => array("mull", "mull", "keep", "keep"),
	35 => array("mull", "keep", "keep", "mull"),
	45 => array("keep", "keep", "mull", "mull"),
	55 => array("keep", "mull", "mull", "keep"),
	65 => array("mull", "mull", "keep", "keep")
);

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

for($mulligan_left=0;$mulligan_left<=10;$mulligan_left++) {
	$score_sum = 0;
	for($i=0;$i<$runs;$i++) {
		$this_mulligan = $mulligan_left;
		$score = 0;
		$this_time_left = $time_left;
		while($this_time_left > 0) {
			$loop_chosen = rand(0, 3);
			$loop_time = $loops[$loop_chosen];
			if($this_mulligan > 0
				&& strcmp($mulligans[$this_time_left][$loop_chosen], "mull") == 0)
			{
				$this_mulligan--;
			} else {
				if($this_time_left < $loop_time) {
					// run over
					$this_time_left -= $loop_time;
				} else {
					$this_time_left -= $loop_time;
					$score += $loop_time;
				}
			}
		}
		$score_sum += $score;
	}

	printf("Expected score with %2d mulligans: %.3f\n",
		$mulligan_left, $score_sum / $runs);
}

?>
