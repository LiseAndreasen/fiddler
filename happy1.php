<?php

// constants

// size of dozo board
$dozo = 7;

// i goes down, j goes to the right
// the board is squished together and does not preserve 60 deg angles

// board
// include a lot of the space around the board
for($i=-$dozo;$i<=$dozo*2;$i++) {
	for($j=-$dozo;$j<=$dozo*3;$j++) {
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
for($i=1;$i<=$dozo;$i++) {
	for($j=1;$j<=$dozo*2-1;$j++) {
		if(($i + $j) % 2 == 0) {
			if($i <= $j && $j <= $dozo * 2 - $i) {
				// a hole!
				$board[$i][$j] = 1;
				$holes[] = array($i, $j);
			}
		}
	}
}

// functions

function print_board() {
	global $dozo, $board;
	for($i=-$dozo;$i<=$dozo*2;$i++) {
		for($j=-$dozo;$j<=$dozo*3;$j++) {
			print $board[$i][$j];
		}
		print "\n";
	}	
}

function print_triangles() {
	global $holes, $triangles;
	$triangle_no = 0;
	foreach($triangles as $t1 => $triangle1) {
		foreach($triangle1 as $t2 => $triangle2) {
			foreach($triangle2 as $t3 => $triangle3) {
				$h1i = $holes[$t1][0];
				$h1j = $holes[$t1][1];
				$h2i = $holes[$t2][0];
				$h2j = $holes[$t2][1];
				$h3i = $holes[$t3][0];
				$h3j = $holes[$t3][1];
				//printf("Triangle: (%d,%d), (%d,%d), (%d,%d).\n", $h1i, $h1j, $h2i, $h2j, $h3i, $h3j);
				$triangle_no++;
			}
		}
	}
	print("There were $triangle_no triangles in all.\n");
}

function add_triangle($key1, $key2, $i3, $j3) {
	global $holes, $triangles;
	$hole3 = array($i3, $j3);
	$key3 = array_search($hole3, $holes);
	$triangle = array($key1, $key2, $key3);
	sort($triangle);
	$triangles[$triangle[0]][$triangle[1]][$triangle[2]] = 1;
}

function test_cases($a, $b, $key1, $i1, $j1, $key2, $i2, $j2) {
	global $board;
	if($i1 + $b == $i2 && $j1 + 2 * $a + $b == $j2) {
		$i3 = $i1 + $a + $b;
		$j3 = $j1 + $a - $b;
		if($board[$i3][$j3] == 1) {
			add_triangle($key1, $key2, $i3, $j3);
		}
		
		$i3 = $i2 - $a - $b;
		$j3 = $j2 - $a + $b;
		if($board[$i3][$j3] == 1) {
			add_triangle($key1, $key2, $i3, $j3);
		}
	}
	
	if($i1 + $b == $i2 && $j2 + 2 * $a + $b == $j1) {
		$i3 = $i1 + $a + $b;
		$j3 = $j1 - $a + $b;
		if($board[$i3][$j3] == 1) {
			add_triangle($key1, $key2, $i3, $j3);
		}

		$i3 = $i1 - $a - $b;
		$j3 = $j1 + $a - $b;
		if($board[$i3][$j3] == 1) {
			add_triangle($key1, $key2, $i3, $j3);
		}
	}
}

//////////////////////////////////////////////

foreach($holes as $key1 => $hole1) {
	foreach($holes as $key2 => $hole2) {
		if($key2 <= $key1) {
			// skip duplicates
			continue;
		}
		$i1 = $hole1[0];
		$j1 = $hole1[1];
		$i2 = $hole2[0];
		$j2 = $hole2[1];
		
		if($i1 == $i2) {
			// a line in the triangle is parallel with the side of the board
			// the 3rd hole must be above or below
			$jdist = $j2 - $j1;
			
			// check above
			$i3 = $i1 - $jdist / 2;
			$j3 = $j1 + $jdist / 2;
			if($board[$i3][$j3] == 1) {
				add_triangle($key1, $key2, $i3, $j3);
			}
			
			// check below
			$i3 = $i1 + $jdist / 2;
			$j3 = $j1 + $jdist / 2;
			if($board[$i3][$j3] == 1) {
				add_triangle($key1, $key2, $i3, $j3);
			}
		} else {
			if($j1 == $j2) {
				// a line in the triangle is perpendicular to side of the board
				// the 3rd hole will be to the left or right
				$idist = $i2 - $i1;
				
				// check left
				$i3 = $i1 + $idist / 2;
				$j3 = $j1 - $idist * 1.5;
				if($board[$i3][$j3] == 1) {
					add_triangle($key1, $key2, $i3, $j3);
				}

				// check right
				$i3 = $i1 + $idist / 2;
				$j3 = $j1 + $idist * 1.5;
				if($board[$i3][$j3] == 1) {
					add_triangle($key1, $key2, $i3, $j3);
				}
			} else {
				// now for the weird cases
				
				// width = distance between 2 holes
				// 1 side is move a widths to the right,
				// make a 30 deg turn to the right,
				// move b further widths forwards
				// or the opposite: a left, turn left, b forward
				
				$a = 2;
				$b = 1;
				test_cases($a, $b, $key1, $i1, $j1, $key2, $i2, $j2);

				$a = 3;
				$b = 1;
				test_cases($a, $b, $key1, $i1, $j1, $key2, $i2, $j2);

				$a = 4;
				$b = 1;
				test_cases($a, $b, $key1, $i1, $j1, $key2, $i2, $j2);
				
				// a = 5 is too far
				// a = 3, b = 2, nothing new
				// a = 4, b = 3, nothing new
			}
		}
	}
}

print_triangles();

?>
