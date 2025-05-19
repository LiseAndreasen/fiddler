<?php

// https://www.baeldung.com/cs/simple-paths-between-two-vertices

// constants

// first and last letters for vertex names
$first_letter = "a";
$last_letter = "d";

// first and last vertex
$u = $first_letter . $first_letter;
$v = $last_letter . $last_letter;

// functions

// depth first search
// u: current vertex
// v: target vertex
// visited: already visited vertices
function dfs($u, $v, $visited) {
	global $simple_paths, $current_path, $g;
	if($visited[$u]) {
		return 0;
	}
	
	$visited[$u] = true;
	$current_path[] = $u;
	
	if(strcmp($u, $v) == 0) {
		$simple_paths[] = $current_path;
		$visited[$u] = false;
		array_pop($current_path);
		return 0;
	}
	
	foreach($g[$u] as $next) {
		dfs($next, $v, $visited);
	}

	array_pop($current_path);
	$visited[$u] = false;
}

//////////////////////////////////////////////

$after["a"] = "b";
$after["b"] = "c";
$after["c"] = "d";
$after["d"] = "x";
$before["a"] = "x";
$before["b"] = "a";
$before["c"] = "b";
$before["d"] = "c";

$g[$v] = array();

// the left upper line has a as first
// the right upper line has a as last
foreach(range($first_letter, $last_letter) as $first){
	// from b to a
	$first_down = $before[$first];
	// from b to c
	$first_up = $after[$first];
	foreach(range($first_letter, $last_letter) as $last){
		// from b to a
		$last_down = $before[$last];
		// from b to c
		$last_up = $after[$last];
		$this_name = $first . $last;
		
		// last +1
		if(strcmp($last, 'd') != 0) {
			$next_name = $first . $last_up;
			$g[$this_name][] = $next_name;
		}

		// first + 1		
		if(strcmp($first, 'd') != 0) {
			$next_name = $first_up . $last;
			$g[$this_name][] = $next_name;
		}

		// first - 1, last + 1		
		if(strcmp($first, 'a') != 0 && strcmp($last, 'd') != 0) {
			$next_name = $first_down . $last_up;
			$g[$this_name][] = $next_name;
		}		

		// first + 1, last - 1		
		if(strcmp($first, 'd') != 0 && strcmp($last, 'a') != 0) {
			$next_name = $first_up . $last_down;
			$g[$this_name][] = $next_name;
		}
	} 
}

foreach($g as $vertex => $gg) {
	$visited[$vertex] = false;
}

$current_path = array();

$simple_paths = array();

dfs($u, $v, $visited);
printf("Number of simple paths: %d.\n", count($simple_paths));

?>
