<?php

// constants

// delta, the width of the smaller cells
$delta = 0.01;

// loops, the number of experiments
$loops = 1000000;

// mode: stars or numbers?
$mode = 1;

// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() 
{
    return (float)rand() / (float)getrandmax();
}

function print_map() {
	global $heat_map, $delta, $no_cells, $mode;
	
	$remember_top = "These coordinates are at the top: ";
	
	// which cell got the most hits?
	$tmp_map = array_merge(...$heat_map);
	$max_hits = max($tmp_map);
	$min_hits = min($tmp_map);
	if($min_hits == 0) {
		$min_hits = 1;
	}
	// going forward, hits will be normalized to be 0-9

	echo "^ y\n";
	echo "|\n";
	for($y=$no_cells-1;$y>=0;$y--) {
		echo "| ";
		for($x=0;$x<$no_cells;$x++) {
			$norm_hits = floor(9 * $heat_map[$x][$y] / $max_hits);
			if($norm_hits == 9) {
				// we found the top!
				$xtop = $x * $delta;
				$ytop = $y * $delta;
				$remember_top .= "($xtop,$ytop) ";
			}
			if($mode == 0) {
				switch($norm_hits) {
					case 9:
						echo "@";
						break;
					case 8:
						echo "*";
						break;
					case 7:
						echo ".";
						break;
					default:
						echo " ";
						break;
				}
			} else {
				switch($norm_hits) {
					case 9:
						echo "\033[41m$norm_hits$norm_hits\033[0m"; // red
						break;
					case 8:
						echo "\033[43m$norm_hits$norm_hits\033[0m"; // yellow
						break;
					case 7:
						echo "\033[42m$norm_hits$norm_hits\033[0m"; // green
						break;
					case 6:
					case 5:
						echo "\033[46m$norm_hits$norm_hits\033[0m"; // cyan
						break;
					case 4:
					case 3:
						echo "\033[44m$norm_hits$norm_hits\033[0m"; // blue
						break;
					case 2:
					case 1:
					case 0:
						echo "\033[45m$norm_hits$norm_hits\033[0m"; // magenta
						break;
				}
			}
		}
		echo "\n";
	}
	echo "|\n";
	echo "+-";
	for($x=0;$x<$no_cells;$x++) {
		echo "--";
	}
	echo "-> x\n";
	
	echo "$remember_top\n";
	echo "Max hits: $max_hits. Min hits: $min_hits. Ratio: " .
		$max_hits / $min_hits . "\n";
}

//////////////////////////////////////////////

// structure for all the little cells
// number of cells with the given width
$no_cells = 1 / $delta;
for($x=0;$x<$no_cells;$x++) {
	for($y=0;$y<$no_cells;$y++) {
		$heat_map[$x][$y] = 0;
	}
}

// loop
for($i=0;$i<$loops;$i++) {
	// first point, a
	$xa = random_0_1();
	$ya = random_0_1();
	
	// second point, b
	$xb = random_0_1();
	$yb = random_0_1();

	// line through a and b, y = c * x + d
	if($xa == $ya) {
		// rare case, throw away
		continue;
	}
	
	$c = ($ya - $yb) / ($xa - $xb);
	$d = $ya - $c * $xa;
	
	for($x=0;$x<$no_cells;$x++) {
		for($y=0;$y<$no_cells;$y++) {
			// does the line go through?
			$xlow = $x * $delta;
			$xhigh = ($x + 1) * $delta;
			$ylow = $y * $delta;
			$yhigh = ($y + 1) * $delta;
			// does each corner go over or under line?
			$corner_low_low = $c * $xlow + $d - $ylow;
			$corner_low_high = $c * $xlow + $d - $yhigh;
			$corner_high_low = $c * $xhigh + $d - $ylow;
			$corner_high_high = $c * $xhigh + $d - $yhigh;
			if($corner_low_low * $corner_high_high < 0 ||
				$corner_low_high * $corner_high_low < 0) {
				$heat_map[$x][$y]++;
			}
		}
	}
	if($i % 10000 == 0) {
		echo ".";
	}
}
// end loop
echo "\n";

print_map();

?>
