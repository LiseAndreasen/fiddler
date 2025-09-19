<?php

///////////////////////////////////////////////////////////////////////////
// constants

// is it fiddler or extra credit?
$fiddler = 1;

// number of team members
if($fiddler == 1) {
	$members = 4;
} else {
	$members = 10;
}

///////////////////////////////////////////////////////////////////////////
// functions

// find longest increasing subsequence
// https://www.geeksforgeeks.org/dsa/longest-increasing-subsequence-dp-3/
function lis($arr) {
	// preparation
	$n = sizeof($arr);
	$lis_vector = array();
	for($i=1;$i<=$n;$i++) {
		$lis_vector[] = 1;
	}

    // Compute optimized LIS values in
    // bottom-up manner
    for($i=1;$i<$n;$i++) {
    	for($prev=0;$prev<$i;$prev++) {
    		if($arr[$i] > $arr[$prev]
    		&& $lis_vector[$i] < $lis_vector[$prev] + 1) {
    			$lis_vector[$i] = $lis_vector[$prev] + 1;
    		}
    	}
    }

	return max($lis_vector);
}

// create all permutations of list
function pc_permute($items, $perms = array()) {
	global $all_permutations;
    if (empty($items)) { 
//        echo join(' ', $perms) . "\n";
		$all_permutations[] = $perms;
    } else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
             list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             pc_permute($newitems, $newperms);
         }
    }
}


///////////////////////////////////////////////////////////////////////////
// main program

// populate array with numbers
$line = array();
for($i=1;$i<=$members;$i++) {
	$line[] = $i;
}
// permute
$all_permutations = array();
pc_permute($line);

// look at all permutations
$all_tries = 0;
$all_values = 0;
// loop
foreach($all_permutations as $perm) {
	$lis_no = lis($perm);
	$all_tries++;
	$all_values += $lis_no;
}

printf("Extected length of longest subsequence with %d members: %5f\n", $members, $all_values / $all_tries);

?>
