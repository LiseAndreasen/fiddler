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

///////////////////////////////////////////////////////////////////////////
// functions

function loop_on(& $averages, $that_time_left, $mulligan_left) {
	global $no_of_loops, $loops, $mulligans;
	if(isset($averages[$that_time_left][$mulligan_left])) {
		return $averages[$that_time_left][$mulligan_left];
	}
	
	if($mulligan_left > 0) {
		$tmp_score_with_mulligan
			= $averages[$that_time_left][$mulligan_left - 1];
	}
	// use recursion
	for($i=0;$i<$no_of_loops;$i++) {
		$this_loop_time = $loops[$i];
		$this_time_left = $that_time_left - $this_loop_time;
		if($this_time_left < 0) {
			$tmp_score = 0;
		} else {
			$tmp_score = $this_loop_time
				+ loop_on($averages, $this_time_left, $mulligan_left);
		}
		if($mulligan_left > 0) {
			// might mulligan be better?
			if($tmp_score < $tmp_score_with_mulligan) {
				$tmp_score = $tmp_score_with_mulligan;
				$mulligans[$that_time_left][$this_loop_time] = "mull";
			}
		}
		$loop_score[] = $tmp_score;
	}

	$average = array_sum($loop_score)/count($loop_score);
	$averages[$that_time_left][$mulligan_left] = $average;
	return $average;
}

function print_2d($arr) {
	print("  ");
	foreach($arr[0] as $arrcol => $arrval) {
		printf("%4d ", $arrcol);
	}
	echo "\n";
	foreach($arr as $arrj => $arrw) {
		printf("%2d ", $arrj);
		foreach($arrw as $arri => $arrc) {
			print($arr[$arrj][$arri] . " ");
		}
		echo "\n";
	}
}

function print_3d($arr) {
	print("  ");
	foreach($arr[0] as $arrcol => $arrval) {
		printf("%7d ", $arrcol);
	}
	echo "\n";
	foreach($arr as $arrj => $arrw) {
		printf("%2d ", $arrj);
		printf("%7.4f ", $arr[$arrj][0]);
		if(isset($arr[$arrj][1])) {
			printf("%7.4f ", $arr[$arrj][1]);
		}
		echo "\n";
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

// averages already calculated
$averages = array();
$averages[0][0] = 0;
$averages[0][1] = 0;

// calculate averages without the mulligan
loop_on($averages, $time_left, 0);

printf("Miles added to score on average: %.3f\n\n", $averages[$time_left][0] / 10);

print("Mulligan added.\n\n");

// populate the mulligan table
foreach($loops as $loop_time) {
	foreach($averages as $avg_time_left => $average_arr) {
		$mulligans[$avg_time_left][$loop_time] = "keep";
	}
}

// calculate averages with the mulligan
loop_on($averages, $time_left, 1);

print("Averages\n\n");
print_3d($averages);
print("\n");

print("Mulligan table\n\n");
print_2d($mulligans);
print("\n");

printf("Miles added to score on average, with mulligan: %.3f\n\n", $averages[$time_left][1] / 10);

?>
