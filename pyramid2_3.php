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
	
	if(isset($dfs_memo[$u][$v][$visited])) {
		return $dfs_memo[$u][$v][$visited];
	}
	
	if($visited[$u]) {
		return 0;
	}
	
	$visited[$u] = true;
	$current_path[] = $u;
	
	if(strcmp($u, $v) == 0) {
		$visited[$u] = false;
		array_pop($current_path);
		return 1;
	}
	
	// sum of paths
	$sum = 0;
	foreach($g[$u] as $next) {
		$sum += dfs($next, $v, $visited);
	}

	array_pop($current_path);
	$visited[$u] = false;
	return $sum;
}

//////////////////////////////////////////////

$g[$v] = array();

$g["aaa"] = array("aab", "aba", "baa");

$g["aab"] = array("aba", "baa", "bbd", "bcc", "cbc");
$g["aba"] = array("aab", "baa", "bcc", "bdb", "ccb");
$g["baa"] = array("aab", "aba", "cbc", "ccb", "dbb");

$g["bbd"] = array("bcc", "cbc", "ccd");
$g["bcc"] = array("bbd", "bdb", "cbc", "ccb", "ccd", "cdc");
$g["bdb"] = array("bcc", "ccb", "cdc");
$g["cbc"] = array("bbd", "bcc", "ccb", "dbb", "ccd", "dcc");
$g["ccb"] = array("bcc", "bdb", "cbc", "dbb", "cdc", "dcc");
$g["dbb"] = array("cbc", "ccb", "dcc");

$g["ccd"] = array("cdc", "dcc", "ddd");
$g["cdc"] = array("ccd", "dcc", "ddd");
$g["dcc"] = array("ccd", "cdc", "ddd");

foreach($g as $vertex => $gg) {
	$visited[$vertex] = false;
}

$current_path = array();

$simple_paths = array();

$sum_all = dfs($u, $v, $visited);
printf("Number of simple paths: %d.\n", $sum_all);

?>
