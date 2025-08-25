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
	
	// use recursion
	for($i=0;$i<$no_of_loops;$i++) {
		$this_loop_time = $loops[$i];
		$this_time_left = $that_time_left - $this_loop_time;
		// may use mulligan
		if(isset($mulligans[$that_time_left][$this_loop_time])
			&& strcmp($mulligans[$that_time_left][$this_loop_time], "mull") == 0
			&& $mulligan_left == 1) {
			$tmp_score = loop_on($averages, $that_time_left, 0);
		} else {
			if($this_time_left < 0) {
				$tmp_score = 0;
			} else {
				$tmp_score = $this_loop_time
					+ loop_on($averages, $this_time_left, $mulligan_left);
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

// calculate the mulligan table
foreach($loops as $loop_time) {
	foreach($averages as $avg_time_left => $average_arr) {
		$average = $average_arr[0];
		if($avg_time_left == $loop_time) {
			$mulligans[$avg_time_left][$loop_time] = "keep";
			continue;
		}
		if($avg_time_left < $loop_time) {
			$mulligans[$avg_time_left][$loop_time] = "mull";
			continue;
		}
		$new_time_left = $avg_time_left - $loop_time;
		$avg_score_keep = $averages[$new_time_left][0] + $loop_time;
		$avg_score_mull = $average;
		if($avg_score_keep < $avg_score_mull) {
			$mulligans[$avg_time_left][$loop_time] = "mull";
		} else {
			$mulligans[$avg_time_left][$loop_time] = "keep";
		}
	}
}

print("Mulligan table\n\n");
print_2d($mulligans);
print("\n");

// calculate averages with the mulligan
loop_on($averages, $time_left, 1);

print("Averages\n\n");
print_3d($averages);
print("\n");

printf("Miles added to score on average, with mulligan: %.3f\n\n", $averages[$time_left][1] / 10);

?>
