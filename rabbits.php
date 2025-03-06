<?php

$maxrabbits = 2; // for extra credit: 10
$allrabbits = 3 * $maxrabbits;

// find element in array with lowest value
function lowest($arr) {
	$minval = 10000;
	for($i=0;$i<sizeof($arr);$i++) {
		if($arr[$i] < $minval) {
			$goodkey = $i;
			$minval = $arr[$i];
		}
	}
	return $goodkey;
}

// make a guess
// rout: rabbits already out of the hat
function guessing($rout) {
	global $guesses, $allrabbits, $maxrabbits;
	
	// first check whether we've already been here
	if(isset($guesses[$rout[0]][$rout[1]][$rout[2]])) {
		return $guesses[$rout[0]][$rout[1]][$rout[2]];
	}
	
	// rabbits left
	$rleft = $allrabbits - array_sum($rout);
	
	// if there's only 1 rabbit left
	if($rleft == 1) {
		// i can guess the color correctly
		// 1 point for me
		$guesses[$rout[0]][$rout[1]][$rout[2]] = 1;
		return 1;
	}
	
	// more than 1 rabbit left
	// the best guess is for (one of) the color(s) with the most rabbits left
	$goodcolor = lowest($rout);
	
	// points for each color it could turn out to be
	for($i=0;$i<sizeof($rout);$i++) {
		if($rout[$i] < $maxrabbits) {
			$routtmp = $rout;
			$routtmp[$i]++;
			$pp = guessing($routtmp);

			if($i == $goodcolor) {
				$p[$i] = ($maxrabbits - $rout[$i]) / $rleft * (1 + $pp);
			} else {
				$p[$i] = ($maxrabbits - $rout[$i]) / $rleft * (0 + $pp);
			}
		}
	}	
	// sum of probability-for-this-situation * (points now + points later)
	$ps = array_sum($p);

	$guesses[$rout[0]][$rout[1]][$rout[2]] = $ps;
	return $ps;
}

/////////////

// orange, green, purple 
$rout = [0, 0, 0];
echo guessing($rout) . "\n";

// print_r($guesses);

?>
