<?php

// first to top wins
// fiddler: 3, extra credit: 5
$top = 5;

// vocal: talk a lot
$vocal = 0;

// cases
$accept = 1;
$reject = 2;
$hammer = 3;
$case[$accept] = "Accept";
$case[$reject] = "Reject";
$case[$hammer] = "Hammer";

/////////////

function printProbs($a, $b, $probs) {
	global $case, $accept;

	// print top row
	print("p($a,$b)   ");
	foreach($case as $key => $choice) {
		if(isset($probs[$accept][$key])) {
			printf($choice . "   ");
		}
	}
	print("\n");
	
	// print each next row with numbers
	foreach($case as $key1 => $choice1) {
		if(isset($probs[$key1][$accept])) {
			// print left column
			print($choice1 . "   ");
			foreach($case as $key2 => $choice2) {
				if(isset($probs[$key1][$key2])) {
					printf("%.5f  ", $probs[$key1][$key2]);
				}
			}
			print("\n");
		}
	}
}

function best($player, $probs) {
	global $accept, $reject, $hammer;
	if(strcmp($player, "me") == 0) {
		// looking for the best (highest) row
		// for now, assume accept is settled on
		$found = $accept;
		// but that hammer is actually better
		$newFound = 1;
		if($probs[$found][$accept] > $probs[$hammer][$accept]) {
			$newFound = 0;
		}
		if(isset($probs[$found][$reject])) {
			if($probs[$found][$reject] > $probs[$hammer][$reject]) {
				$newFound = 0;
			}
		}
		if($probs[$found][$hammer] > $probs[$hammer][$hammer]) {
			$newFound = 0;
		}
		if($newFound == 1) {
			$found = $hammer;
		}
		
		// same thing, but with reject
		if(isset($probs[$reject][$accept])) {
			$newFound = 1;
			if($probs[$found][$accept] > $probs[$reject][$accept]) {
				$newFound = 0;
			}
			if(isset($probs[$found][$reject])) {
				if($probs[$found][$reject] > $probs[$reject][$reject]) {
					$newFound = 0;
				}
			}
			if($probs[$found][$hammer] > $probs[$reject][$hammer]) {
				$newFound = 0;
			}
			if($newFound == 1) {
				$found = $reject;
			}
		}
		
		return $found;
	} else {
		// my opponent
		// looking for the best (lowest) column
		// for now, assume accept is settled on
		$found = $accept;
		// but that hammer is actually better
		$newFound = 1;
		if($probs[$accept][$found] < $probs[$accept][$hammer]) {
			$newFound = 0;
		}
		if(isset($probs[$reject][$found])) {
			if($probs[$reject][$found] < $probs[$reject][$hammer]) {
				$newFound = 0;
			}
		}
		if($probs[$hammer][$found] < $probs[$hammer][$hammer]) {
			$newFound = 0;
		}
		if($newFound == 1) {
			$found = $hammer;
		}
		
		// same thing, but with reject
		if(isset($probs[$accept][$reject])) {
			$newFound = 1;
			if($probs[$accept][$found] < $probs[$accept][$reject]) {
				$newFound = 0;
			}
			if(isset($probs[$reject][$found])) {
				if($probs[$reject][$found] < $probs[$reject][$reject]) {
					$newFound = 0;
				}
			}
			if($probs[$hammer][$found] < $probs[$hammer][$reject]) {
				$newFound = 0;
			}
			if($newFound == 1) {
				$found = $reject;
			}
		}
		
		return $found;
	}
}

// probability I will win, given the match so far is a-b
function p($a, $b) {
	global $pp, $top, $accept, $reject, $hammer, $case, $vocal;
	// memoization
	if(isset($pp[$a][$b])) {
		return $pp[$a][$b];
	}
	// I won
	if($a >= $top) {
		$pp[$a][$b] = 1;
		return 1;
	}
	// I lost
	if($b >= $top) {
		$pp[$a][$b] = 0;
		return 0;
	}
	// the symmetric case
	if($a == $b) {
		$pp[$a][$b] = 0.5;
		return 0.5;
	}
	
	// fill in table with probabilities
	// the game is for 1 point, 50% change for win
	$tmp = (p($a+1, $b) + p($a, $b+1)) / 2;
	$probs[$accept][$accept] = $tmp;
	$probs[$accept][$reject] = $tmp;
	$probs[$reject][$accept] = $tmp;
	$probs[$reject][$reject] = $tmp;
	
	// the game is for 2 points, 50% chance for win
	$tmp = (p($a+2, $b) + p($a, $b+2)) / 2;
	$probs[$accept][$hammer] = $tmp;
	$probs[$hammer][$accept] = $tmp;
	$probs[$hammer][$hammer] = $tmp;
	
	// I hammered, and was rejected
	$tmp = p($a+1, $b);
	$probs[$hammer][$reject] = $tmp;
	
	// I was hammered, and rejected
	$tmp = p($a, $b+1);
	$probs[$reject][$hammer] = $tmp;
	
	// check for rejection leading to win/loss first
	// because this shouldn't happen in optimal play
	if($probs[$hammer][$reject] == 1) {
		unset($probs[$accept][$reject]);
		unset($probs[$reject][$reject]);
		unset($probs[$hammer][$reject]);
	}
	if($probs[$reject][$hammer] == 0) {
		unset($probs[$reject][$accept]);
		unset($probs[$reject][$reject]);
		unset($probs[$reject][$hammer]);
	}
	
	
	// which strategy will I choose?
	$myStrategy = best("me", $probs);
	// and which will my opponent choose?
	$oppStrategy = best("notme", $probs);

	// this is the probability that will occur
	$pp[$a][$b] = $probs[$myStrategy][$oppStrategy];
	
	if($vocal == 1) {
		printProbs($a, $b, $probs);
		print("My strategy....: $case[$myStrategy]\n");
		print("Opp strategy...: $case[$oppStrategy]\n");
		print("p($a,$b) = " . $pp[$a][$b] . "\n");
		print("\n");
	}
	return $pp[$a][$b];
}

/////////////

$myProb = p(1,0);
print("Probability I will win: $myProb\n");

?>
