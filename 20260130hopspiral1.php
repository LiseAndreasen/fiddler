<?php

///////////////////////////////////////////////////////////////////////////
// constants

$k = sqrt(3) / 2;

// vectors pointing towards the 6 directions, going clock wise
$v_test[] = [1, 0];
$v_test[] = [0.5, -1];
$v_test[] = [-0.5, -1];
$v_test[] = [-1, 0];
$v_test[] = [-0.5, 1];
$v_test[] = [0.5, 1];

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

// the first points (lily pads centers)
// 2nd argument should actually be multiplied by k
$p[0] = [0, 0];
$p[1] = [1, 0];
$p[2] = [1.5, 1];
$p1 = $p[1];
$p2 = $p[2];

$input = "";
while($input != 7) {
	$p_test[7] = $p2;
		if($p_test[7][0] == 0) {
			$p_angle[7] = 1000;
		} else {
			$p_angle[7] = rad2deg(atan($p_test[7][1] * $k
				/ $p_test[7][0]));
		}
		$p_dist[7] = sqrt($p_test[7][0] * $p_test[7][0]
			+ $p_test[7][1] * $k * $p_test[7][1] * $k);
		$p_text[7] = sprintf("(%4.1f,%4.1f) %5.2f deg %5.2f dist",
			$p_test[7][0], $p_test[7][1],
			$p_angle[7], $p_dist[7]);

	foreach($v_test as $v_id => $vt) {
		$p_test[$v_id] = [$p2[0] + $vt[0], $p2[1] + $vt[1]];
		if($p_test[$v_id][0] == 0) {
			$p_angle[$v_id] = 1000;
		} else {
			$p_angle[$v_id] = rad2deg(atan($p_test[$v_id][1] * $k
				/ $p_test[$v_id][0]));
		}
		$p_dist[$v_id] = sqrt($p_test[$v_id][0] * $p_test[$v_id][0]
			+ $p_test[$v_id][1] * $k * $p_test[$v_id][1] * $k);
		if($p_dist[7] <= $p_dist[$v_id]) {
			$p_text[$v_id] = sprintf("(%4.1f,%4.1f) %5.2f deg %5.2f dist",
				$p_test[$v_id][0], $p_test[$v_id][1],
				$p_angle[$v_id], $p_dist[$v_id]);
		} else {
			$p_text[$v_id] = "********************************";
		}
	}
	
	print("\t\t$p_text[4]\t\t$p_text[5]\t\t\t\t 4 5\n\n");
	print("$p_text[3]\t$p_text[7]\t\t$p_text[0]\t3 7 0\n\n");
	print("\t\t$p_text[2]\t\t$p_text[1]\t\t\t\t 2 1\n\n");
	
	echo "Choose direction: ";
	$input = rtrim(fgets(STDIN));
	
	$p2 = $p_test[$input];
	$p[] = $p2;
}

foreach($p as $pid => $pp) {
	printf("Point id: %d, (%.1f,%.1f)\n", $pid, $pp[0], $pp[1]);
}
print("=====================================\n");

///////////////////////////////////////////////////////////////////////////
// extra credit

$N = 0;
$flies[0][0] = 1;

while(0.01 < $flies[0][0] || $N < 2) {
	$N++;
	$new_flies = [];
	$new_flies[0][0] = 0;
	
	foreach($flies as $key1 => $col) {
		foreach($col as $key2 => $pad) {
			if(isset($new_flies[$key1 - 1][$key2 - 1])) {
				$new_flies[$key1 - 1][$key2 - 1] += $flies[$key1][$key2] / 6;
			} else {
				$new_flies[$key1 - 1][$key2 - 1] = $flies[$key1][$key2] / 6;
			}
			if(isset($new_flies[$key1 - 1][$key2 + 1])) {
				$new_flies[$key1 - 1][$key2 + 1] += $flies[$key1][$key2] / 6;
			} else {
				$new_flies[$key1 - 1][$key2 + 1] = $flies[$key1][$key2] / 6;
			}
			if(isset($new_flies[$key1 + 1][$key2 - 1])) {
				$new_flies[$key1 + 1][$key2 - 1] += $flies[$key1][$key2] / 6;
			} else {
				$new_flies[$key1 + 1][$key2 - 1] = $flies[$key1][$key2] / 6;
			}
			if(isset($new_flies[$key1 + 1][$key2 + 1])) {
				$new_flies[$key1 + 1][$key2 + 1] += $flies[$key1][$key2] / 6;
			} else {
				$new_flies[$key1 + 1][$key2 + 1] = $flies[$key1][$key2] / 6;
			}
			if(isset($new_flies[$key1 - 2][$key2])) {
				$new_flies[$key1 - 2][$key2] += $flies[$key1][$key2] / 6;
			} else {
				$new_flies[$key1 - 2][$key2] = $flies[$key1][$key2] / 6;
			}
			if(isset($new_flies[$key1 + 2][$key2])) {
				$new_flies[$key1 + 2][$key2] += $flies[$key1][$key2] / 6;
			} else {
				$new_flies[$key1 + 2][$key2] = $flies[$key1][$key2] / 6;
			}
		}
	}
		
	$flies = $new_flies;
}

printf("Round %d, %f flies\n", $N, $flies[0][0]);

?>
