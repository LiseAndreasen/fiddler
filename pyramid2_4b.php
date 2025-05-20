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
	
	// just to put something on the screen
	global $counting;

	if($visited[$u]) {
		return 0;
	}
	
	$visited[$u] = true;
	$current_path[] = $u;
	
	if(strcmp($u, $v) == 0) {

		// just to put something on the screen
		$counting++;
		if($counting % 1000000 == 0) {
			print(".");
			if($counting % 100000000 == 0) {
				print("\n");
			}
		}
		
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

// aaa

// aab aba
//   baa

// aac abb aca
//   bab bba
//     caa

// aad abc acb ada
//   bac bbb bca
//     cab cba
//       daa

// bbd bcc bdb
//   cbc ccb
//     dbb

// ccd cdc
//   dcc

// ddd
 
$g["aaa"] = array("aab", "aba", "baa");

$g["aab"] = array("aba", "baa", "aac", "abb", "bab");
$g["aba"] = array("aab", "baa", "abb", "aca", "bba");
$g["baa"] = array("aab", "aba", "bab", "bba", "caa");

$g["aac"] = array("abb", "bab", "aad", "abc", "bac");
$g["abb"] = array("aac", "aca", "bab", "bba", "abc", "acb", "bbb");
$g["aca"] = array("abb", "bba", "acb", "ada", "bca");
$g["bab"] = array("aac", "abb", "bba", "caa", "bac", "bbb", "cab");
$g["bba"] = array("abb", "aca", "bab", "caa", "bbb", "bca", "cba");
$g["caa"] = array("bab", "bba", "cab", "cba", "daa");

$g["aad"] = array("abc", "bac", "bbd");
$g["abc"] = array("aad", "acb", "bac", "bbb", "bbd", "bcc");
$g["acb"] = array("abc", "ada", "bbb", "bca", "bcc", "bdb");
$g["ada"] = array("acb", "bca", "bdb");
$g["bac"] = array("aad", "abc", "bbb", "cab", "bbd", "cbc");
$g["bbb"] = array("abc", "acb", "bac", "bca", "cab", "cba", "bcc", "cbc", "ccb");
$g["bca"] = array("acb", "ada", "bbb", "cba", "bdb", "ccb");
$g["cab"] = array("bac", "bbb", "cba", "daa", "cbc", "dbb");
$g["cba"] = array("bbb", "bca", "cab", "daa", "ccb", "dbb");
$g["daa"] = array("cab", "cba", "dbb");

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

// just to put something on the screen
$counting = 0;

$sum_all = dfs($u, $v, $visited);
printf("\nNumber of simple paths, calculated in 2 different ways: %d, %d.\n", $sum_all, $counting);

?>
