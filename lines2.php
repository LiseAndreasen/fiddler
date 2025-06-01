<?php

// constants

// delta, the distance between the points
$delta = 0.01;

// number of points with the given distance
$no_cells = 1 / $delta;

// mode: stars or numbers?
$mode = 1;

// functions

// https://www.symbolab.com/solver/step-by-step/%5Cint%20%5Cfrac%7B1%7D%7Bcosx%7Ddx
// F(t) related to 1/cos
function f_cos($t) {
	return log(abs(tan($t) + 1/cos($t)));
}

// https://www.symbolab.com/solver/step-by-step/%5Cint%5Cfrac%7B1%7D%7B%5Csin%5Cleft(x%5Cright)%7Ddx
// F(t) related to 1/sin
function f_sin($t) {
	return log(abs(tan($t/2)));
}

function calc_area($p1, $p2) {
	// the square
	// corners a, b, c, d
	// angle t, 0 to 2 pi
	// b---------a
	// |       / |
	// |     / t |
	// |   p-----|
	// |         |
	// |         |
	// c---------d

	// angle when line between p and a
	// tan t = (a2 - p2) / (a1 - p1)
	$ta = atan((1 - $p2) / (1 - $p1));
	$tb = pi() - atan((1 - $p2) / ($p1));
	$tc = pi() + atan($p2 / $p1);
	$td = 2 * pi() - atan($p2 / (1 - $p1));

	// distance from p to square
	// between 0 and ta, and again between td and 2 pi
	// d1 = (1 - p1) / cos(t)
	// between ta and tb
	// d2 = (1 - p2) / sin(t)
	// between tb and tc
	// d3 = - p1 / cos(t)
	// between tc and td
	// d4 = - p2 / sin(t)

	// let f(t) = above defined distance, 0 <= t <= 2 pi
	// what is area under f(t)?
	// between td and 2 pi, 0 and ta
	$f1 = (1 - $p1) * (f_cos($ta) - f_cos($td));
	// between ta and tb
	$f2 = (1 - $p2) * (f_sin($tb) - f_sin($ta));
	// between tb and tc
	$f3 = - $p1 * (f_cos($tc) - f_cos($tb));
	// between tc and td
	$f4 = - $p2 * (f_sin($td) - f_sin($tc));

	$area = $f1 + $f2 + $f3 + $f4;
	return $area;
}

function print_map() {
	global $heat_map, $delta, $no_cells, $mode;
	
	$remember_top = "These coordinates are at the top: ";
	
	// which cell got the most hits?
	$tmp_map = array_merge(...$heat_map);
	$max_hits = max($tmp_map);
	$min_hits = min($tmp_map);
	// going forward, hits will be normalized to be 0-9

	echo "^ y\n";
	echo "|\n";
	for($y=$no_cells-1;$y>0;$y--) {
		echo "| ";
		for($x=1;$x<$no_cells;$x++) {
			// not set for entry 14???
			if(!isset($heat_map[$x][$y])) {
				echo "  ";
				continue;
			}
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
						echo "\033[46m$norm_hits$norm_hits\033[0m"; // cyan
						break;
					case 5:
						echo "\033[44m$norm_hits$norm_hits\033[0m"; // blue
						break;
					case 4:
					case 3:
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

for($p1=$delta;$p1<1;$p1+=$delta) {
	for($p2=$delta;$p2<1;$p2+=$delta) {
		$heat_map[$p1/$delta][$p2/$delta] = calc_area($p1, $p2);
	}
}

print_map();

printf("Area for (0.5, 0.5)......: %.10f\n", calc_area(0.5, 0.5));
printf("Area for (1/10^1, 1/10^1): %.10f\n", calc_area(0.1, 0.1));
printf("Area for (1/10^2, 1/10^2): %.10f\n", calc_area(0.01, 0.01));
printf("Area for (1/10^3, 1/10^3): %.10f\n", calc_area(0.001, 0.001));
printf("Area for (1/10^4, 1/10^4): %.10f\n", calc_area(0.0001, 0.0001));
printf("Area for (1/10^5, 1/10^5): %.10f\n", calc_area(0.00001, 0.00001));
printf("Area for (1/10^6, 1/10^6): %.10f\n", calc_area(0.000001, 0.000001));
printf("Area for (1/10^7, 1/10^7): %.10f\n", calc_area(0.0000001, 0.0000001));
printf("Area for (1/10^8, 1/10^8): %.10f\n", calc_area(0.00000001, 0.00000001));

printf("Calculated ratio between high and low: %.10f\n", calc_area(0.00000001, 0.00000001) / calc_area(0.5, 0.5));

?>
