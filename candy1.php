<?php

// https://stackoverflow.com/questions/5506888/permutations-all-possible-sets-of-numbers
// create permutations
function pc_permute($items, $perms = array( )) {
	global $perms1;
    if (empty($items)) { 
    	$perms1[] = $perms;
        //print join(' ', $perms) . "\n";
    }  else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
             list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             pc_permute($newitems, $newperms);
         }
    }
}

// https://stackoverflow.com/questions/74414674/how-to-get-intersection-between-two-strings-in-php
// how much do 2 arrays intersect?
function inters(string $str1, string $str2): int {
   $arr1 = str_split($str1);
   $arr2 = str_split($str2);

   return count(array_intersect($arr1, $arr2));
};

// traverse the graph
function traverse($used) {
	global $permlgt, $neigh, $solfound, $solutions;
	// if we already found a solution, just go home
	if($solfound == 1) {
		return 1;
	}
	$newkey = sizeof($used);
	// if we found a solution now, save it and go home
	if($newkey == $permlgt) {
		$solutions[] = $used;
		$solfound = 1;
		return 1;
	}
	// look at the newest member of the list
	$newest = $used[$newkey-1];
	// create a list of neighbors
	$newneighs = array();
	for($i=0;$i<$permlgt;$i++) {
		if($neigh[$newest][$i] == 1 && !in_array($i, $used)) {
			$newneighs[] = $i;
		}
	}
	// if no neighbors
	if(sizeof($newneighs) == 0) {
		return 0; // isn't used
	}
	// recursion
	foreach($newneighs as $new) {
		$tmpused = $used;
		$tmpused[] = $new;
		traverse($tmpused);
	}
}

// fiddler
// beginning string of available letters
$letters = "abcdefg";
$letlgt = strlen($letters);
// size of groups
$grsz = 3;

// this is a hack, we only need 1 solution
// solution found?
$solfound = 0;

/////////////

// construct all possible permutations of the required size

$letters2 = str_split($letters);

// result of permute ends up in perms1
$perms1 = array();
pc_permute($letters2);

// throw away the part of the permunation that's more than grsz
foreach ($perms1 as $key => $permutation) {
	for($i=$grsz;$i<$letlgt;$i++) {
		unset($perms1[$key][$i]);
	}
}

// throw away permutations that aren't sorted
foreach ($perms1 as $key => $permutation) {
	$tmp = $permutation;
	sort($tmp);
	if($permutation == $tmp) {
		$perms2[] = implode("", $permutation);
	}
}

// throw away duplicates
$perms3 = array_unique($perms2);
// make keys nice
sort($perms3);

$permlgt = sizeof($perms3);

// note in matrix which permutations can be neighbors
for($i=0;$i<$permlgt;$i++) {
	for($j=0;$j<$permlgt;$j++) {
		if(inters($perms3[$i], $perms3[$j]) == 0) {
			$neigh[$i][$j] = 1;
		} else {
			$neigh[$i][$j] = 0;
		}
	}
}

// solutions found
$solutions = array();

// begin in permutation no 0
traverse(array(0));
foreach($solutions[0] as $key) {
	print($perms3[$key] . " ");
}
print("\n");

?>
