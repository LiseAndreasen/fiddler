<?php

// https://www.baeldung.com/cs/simple-paths-between-two-vertices

// constants

// first and last vertex
$u = "aaa";
$v = "ddd";

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

$g[$v] = array();

$g["aaa"] = array("aab", "aba", "baa");

$g["aab"] = array("aba", "baa", "ddd");
$g["aba"] = array("aab", "baa", "ddd");
$g["baa"] = array("aab", "aba", "ddd");

foreach($g as $vertex => $gg) {
	$visited[$vertex] = false;
}

$current_path = array();

$simple_paths = array();

dfs($u, $v, $visited);
printf("Number of simple paths: %d.\n", count($simple_paths));

?>
