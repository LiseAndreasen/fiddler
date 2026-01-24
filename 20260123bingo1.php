<?php

///////////////////////////////////////////////////////////////////////////
// constants

$grid_size = 3; // fiddler: 3, extra credit: 5
$loops = 10000;

///////////////////////////////////////////////////////////////////////////
// functions

// change (y, x) map to (x, y) map
function pivot($map) {
	$map_height = sizeof($map);
	$map_width = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			$map2[$i][$j] = $map[$j][$i];
		}
	}
	return $map2;
}

function print_map($map) {
	foreach($map[0] as $j => $cell) {
		foreach($map as $i => $col) {
			echo $map[$i][$j];
		}
		echo "\n";
	}
	for($i=0;$i<sizeof($map);$i++) {
		echo "=";
	}
	echo "\n";
}

function theres_bingo($grid) {
	$grid_size = sizeof($grid);
	foreach($grid as $col) {
		if(array_sum($col) == $grid_size) {
			return true;
		}
	}
	$grid = pivot($grid);
	foreach($grid as $col) {
		if(array_sum($col) == $grid_size) {
			return true;
		}
	}
	$diag_sum1 = 0;
	$diag_sum2 = 0;
	for($i=0;$i<$grid_size;$i++) {
		$diag_sum1 += $grid[$i][$i];
		$diag_sum2 += $grid[$i][$grid_size-$i-1];
	}
	if($diag_sum1 == $grid_size || $diag_sum2 == $grid_size) {
		return true;
	}
	return false;
}

// assumes grid_size = 3
function test($empty_grid) {
	// test of fundtion
	$test_grid = $empty_grid;
	print_map($test_grid);
	if(theres_bingo($test_grid)) {
		print("Bingo\n");
	} else {
		print("No bingo\n");
	}

	// diag 1
	$test_grid = $empty_grid;
	$test_grid[0][0] = 1;
	$test_grid[2][2] = 1;
	print_map($test_grid);
	if(theres_bingo($test_grid)) {
		print("Bingo\n");
	} else {
		print("No bingo\n");
	}

	// diag 2
	$test_grid = $empty_grid;
	$test_grid[0][2] = 1;
	$test_grid[2][0] = 1;
	print_map($test_grid);
	if(theres_bingo($test_grid)) {
		print("Bingo\n");
	} else {
		print("No bingo\n");
	}

	// vertical
	$test_grid = $empty_grid;
	$test_grid[0][0] = 1;
	$test_grid[0][1] = 1;
	$test_grid[0][2] = 1;
	print_map($test_grid);
	if(theres_bingo($test_grid)) {
		print("Bingo\n");
	} else {
		print("No bingo\n");
	}

	// horizontal
	$test_grid = $empty_grid;
	$test_grid[0][0] = 1;
	$test_grid[1][0] = 1;
	$test_grid[2][0] = 1;
	print_map($test_grid);
	if(theres_bingo($test_grid)) {
		print("Bingo\n");
	} else {
		print("No bingo\n");
	}
}

// https://stackoverflow.com/questions/30087158/how-can-i-rotate-a-2d-array-in-php-by-90-degrees
function rotateMatrix90( $matrix )
{
    $matrix = array_values( $matrix );
    $matrix90 = array();

    // make each new row = reversed old column
    foreach( array_keys( $matrix[0] ) as $column ){
        $matrix90[] = array_reverse( array_column( $matrix, $column ) );
    }

    return $matrix90;
}

function mirror_matrix($matrix) {
	foreach($matrix as $col) {
		$matrix_mirror[] = array_reverse($col);
	}
	return $matrix_mirror;
}

// find rotation/mirror with lowest value and return as string
function grid_id_lowest($grid) {
	global $grid_size, $grid_id_values;
	
	$grid_flat = array_merge(...$grid);
	$id0 = implode("", $grid_flat);
	$id[] = $id0;
	// memoization
	if(isset($grid_id_values[$id0])) {
		return $grid_id_values[$id0];
	}
	
	for($i=1;$i<=3;$i++) {
		$grid = rotateMatrix90($grid);
		$grid_flat = array_merge(...$grid);
		$id[] = implode("", $grid_flat);
	}
	
	$grid = mirror_matrix($grid);
	$grid_flat = array_merge(...$grid);
	$id[] = implode("", $grid_flat);

	for($i=1;$i<=3;$i++) {
		$grid = rotateMatrix90($grid);
		$grid_flat = array_merge(...$grid);
		$id[] = implode("", $grid_flat);
	}
	
	$grid_id_values[$id0] = min($id);
	return min($id);
}

function generate_bingo($grid, $markers) {
	global $grid_size, $progress, $generated_bingos;
	$progress[$markers]++;
	foreach($progress as $m => $val) {
		if($markers < $m) {
			$progress[$m] = 0;
		}
	}
	if(1 <= $markers && $markers < $grid_size + 1) {
		printf("Round %s\n", implode(".", array_slice($progress, 1, $grid_size)));
	}
	
	$grid_id = grid_id_lowest($grid);
	// memoization
	if(isset($generated_bingos[$grid_id])) {
		return $generated_bingos[$grid_id];
	}
	
	if(theres_bingo($grid)) {
		$generated_bingos[$grid_id] = array($markers => 1);
		return array($markers => 1);
	}
	foreach($grid as $x => $col) {
		foreach($col as $y => $square) {
			if($square != 1) {
				$test_grid = $grid;
				$test_grid[$x][$y] = 1;
				$result = generate_bingo($test_grid, $markers + 1);
				foreach($result as $m => $b) {
					if(isset($bingo_markers[$m])) {
						$bingo_markers[$m] += $b;
					} else {
						$bingo_markers[$m] = $b;
					}
				}
			}
		}
	}
	$generated_bingos[$grid_id] = $bingo_markers;
	return $bingo_markers;
}

///////////////////////////////////////////////////////////////////////////
// main program

$time1 = "The time was " . date("h:i:sa" . "\n");
print("Monte carlo running\n");

$col = array_fill(0, $grid_size, 0);
$empty_grid = array_fill(0, $grid_size, $col);
$empty_grid[($grid_size-1)/2][($grid_size-1)/2] = 1;

//test($empty_grid);

$markers_placed_sum = 0;
for($i=0;$i<$loops;$i++) {
	$test_grid = $empty_grid;
	$squares = [];
	foreach($test_grid as $x => $col) {
		foreach($col as $y => $square) {
			if($square != 1) {
				$squares[] = [$x, $y];
			}
		}
	}
	shuffle($squares);
	while(!theres_bingo($test_grid)) {
		$square = array_shift($squares);
		[$x, $y] = $square;
		$test_grid[$x][$y] = 1;
	}
	$markers_placed = $grid_size * $grid_size - sizeof($squares) - 1;
	$markers_placed_sum += $markers_placed;
}

///////////////////////////////////////////////////////////////////////////

$time2 = "The time was " . date("h:i:sa" . "\n");
print("Systematic approach running\n");

// init
$grid_grid = $grid_size * $grid_size;
$progress = array_fill(0, $grid_grid - 1, 0);
$generated_bingos = [];
$grid_id_values = [];

// action
$bingo_markers = generate_bingo($empty_grid, 0);

// calculation
// for 3x3 grid
// a 2 marker bingo is 1 option out of 8*7
// a 3 marker bingo is 1 option out of 8*7*6
$bingo_marker_sum = 0;
foreach($bingo_markers as $markers => $bingos) {
	$factor = 1;
	for($i=$grid_grid-1;$grid_grid-1-$markers<$i;$i--) {
		$factor *= $i;
	}
	$bingo_marker_sum += $markers * $bingos / $factor;
}

$time3 = "The time was " . date("h:i:sa" . "\n");

///////////////////////////////////////////////////////////////////////////

printf("\nGrid size %d x %d\n", $grid_size, $grid_size);

print("\n" . $time1 . "\n");

print("Monte carlo\n");
printf("Loops %d\n", $loops);
printf("Average markers placed: %9.6f\n", $markers_placed_sum / $loops);

print("\n" . $time2. "\n");

print("Systematic approach\n");
printf("Average markers placed: %9.6f\n", $bingo_marker_sum);

print("\n" . $time3);

?>
