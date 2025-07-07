<?php

// constants

// size of flag
$flag1 = 5;
$flag2 = 6;

// i goes down, j goes to the right

// board
// include a lot of the space around the board
for($i=-$flag1*2;$i<=$flag1*4;$i++) {
	for($j=-$flag2*2;$j<=$flag2*4;$j++) {
		if(($i + $j) % 2 == 0) {
			// so far empty space
			$board[$i][$j] = 0;
		} else {
			// there couldn't even be a hole here
			$board[$i][$j] = " ";
		}
	}
}

// then mark the holes
for($i=1;$i<=$flag1*2-1;$i++) {
	for($j=1;$j<=$flag2*2-1;$j++) {
		if(($i + $j) % 2 == 0) {
				// a hole!
				$board[$i][$j] = 1;
				$holes[] = array($i, $j);
		}
	}
}

// functions

function print_board() {
	global $flag1, $flag2, $board;
	for($i=-$flag1*2;$i<=$flag1*4;$i++) {
		for($j=-$flag2*2;$j<=$flag2*4;$j++) {
			print $board[$i][$j];
		}
		print "\n";
	}	
}

function print_parallelograms() {
	global $holes, $parallelograms;
	$parallelogram_no = 0;
	foreach($parallelograms as $p1 => $parallelogram1) {
		foreach($parallelogram1 as $p2 => $parallelogram2) {
			foreach($parallelogram2 as $p3 => $parallelogram3) {
				foreach($parallelogram3 as $p4 => $parallelogram4) {
					$h1i = $holes[$p1][0];
					$h1j = $holes[$p1][1];
					$h2i = $holes[$p2][0];
					$h2j = $holes[$p2][1];
					$h3i = $holes[$p3][0];
					$h3j = $holes[$p3][1];
					$h4i = $holes[$p4][0];
					$h4j = $holes[$p4][1];
					//printf("Parallelogram: (%d,%d), (%d,%d), (%d,%d), (%d,%d).\n", $h1i, $h1j, $h2i, $h2j, $h3i, $h3j, $h4i, $h4j);
					$parallelogram_no++;
				}
			}
		}
	}
	print("There were $parallelogram_no parallelograms in all.\n");
}

function add_parallelogram($key1, $key2, $key3, $i4, $j4) {
	global $holes, $parallelograms;
	$hole4 = array($i4, $j4);
	$key4 = array_search($hole4, $holes);
	$parallelogram = array($key1, $key2, $key3, $key4);
	sort($parallelogram);
	$parallelograms[$parallelogram[0]][$parallelogram[1]][$parallelogram[2]][$parallelogram[3]] = 1;
}

//////////////////////////////////////////////

foreach($holes as $key1 => $hole1) {
	foreach($holes as $key2 => $hole2) {
		if($key1 == $key2) {
			// skip duplicates
			continue;
		}
		foreach($holes as $key3 => $hole3) {
			if($key1 == $key3 || $key2 == $key3) {
				continue;
			}
			$i1 = $hole1[0];
			$j1 = $hole1[1];
			$i2 = $hole2[0];
			$j2 = $hole2[1];
			$i3 = $hole3[0];
			$j3 = $hole3[1];
			
			// exclude 3 points on a line
			if($j3 == $j1) {
				$slopea = 1000;
			} else {
				$slopea = ($i3 - $i1) / ($j3 - $j1);
			}
			if($j3 == $j2) {
				$slopeb = 1000;
			} else {
				$slopeb = ($i3 - $i2) / ($j3 - $j2);
			}
			if($j2 == $j1) {
				$slopec = 1000;
			} else {
				$slopec = ($i2 - $i1) / ($j2 - $j1);
			}
			if($slopea == $slopeb && $slopea == $slopec) {
				continue;
			}
			
			// parallelogram:
			// add 4th hole to existing triangle
			// add coordinates for 2 holes
			// subtract 3rd
			$i4 = $i1 + $i2 - $i3;
			$j4 = $j1 + $j2 - $j3;
			if($board[$i4][$j4] == 1) {
				add_parallelogram($key1, $key2, $key3, $i4, $j4);
			}

			$i4 = $i1 - $i2 + $i3;
			$j4 = $j1 - $j2 + $j3;
			if($board[$i4][$j4] == 1) {
				add_parallelogram($key1, $key2, $key3, $i4, $j4);
			}

			$i4 = - $i1 + $i2 + $i3;
			$j4 = - $j1 + $j2 + $j3;
			if($board[$i4][$j4] == 1) {
				add_parallelogram($key1, $key2, $key3, $i4, $j4);
			}
		}
	}
}

print_parallelograms();

?>
