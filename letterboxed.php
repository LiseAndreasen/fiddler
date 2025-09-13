<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

function print_permutation($perm) {
	$perm_sz = sizeof($perm);
	for($i=0;$i<$perm_sz;$i++) {
		print($perm[$i]);
	}
	print("\n");
}

// factor: what to multiply with before counting the valid permutations
function permute($letters, $used, $factor) {
	global $valid_permutations;
	if(array_sum($letters) == 0) {
		// all letters have been used
		$valid_permutations += $factor;
		return;
	}
	$choices = sizeof($letters);
	$used_sz = sizeof($used);
	for($i=0;$i<$choices;$i++) {
		if($letters[$i] > 0) {
			// there are still letters of this kind left
			if($used_sz > 0) {
				// there was a letter before this
				// and we can't have the same twice
				if($used[$used_sz - 1] != $i) {
					// valid new letter
					$new_letters = $letters;
					$new_letters[$i]--;
					$new_used = $used;
					$new_used[] = $i;
					$new_factor = $factor * $letters[$i];
					permute($new_letters, $new_used, $new_factor);
				}
			} else {
				// this is the first letter
				$new_letters = $letters;
				$new_letters[$i]--;
				$new_used = array($i);
				$new_factor = $factor * $letters[$i];
				permute($new_letters, $new_used, $new_factor);
			}
		}
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

$valid_permutations = 0;

// starting configuration
// fiddler: 4 groups with 2 letters each
$letters = array(2, 2, 2, 2);
// extra credit: 4 groups with 3 letters each
$letters = array(3, 3, 3, 3);
$used = array();

permute($letters, $used, 1);

print("This is the beginning:\n");
print_r($letters);
print("Valid permutations: $valid_permutations.\n");

?>
