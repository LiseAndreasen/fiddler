<?php

///////////////////////////////////////////////////////////////////////////
// constants

$letters = "ABC";
$STRING = 0;
$LENGTH = 1;
$lengths["OA"] = 2;
$lengths["AO"] = 2;
$lengths["OB"] = 3;
$lengths["BO"] = 3;
$lengths["OC"] = 4;
$lengths["CO"] = 4;
$lengths["BC"] = 5;
$lengths["CB"] = 5;

///////////////////////////////////////////////////////////////////////////
// functions

// https://stackoverflow.com/questions/5506888/permutations-all-possible-sets-of-numbers
// create permutations
function pc_permute($items, $perms = array( )) {
    global $perms1, $STRING;
    if (empty($items)) {
        $perms1[$STRING][] = implode("", $perms);
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

function print_perms($perms) {
    global $STRING, $LENGTH;
    printf("%10s %15s %20s\n", "", "My strategy", "Max length");
    foreach($perms[$STRING] as $key => $perm) {
        printf("%10s %15s %20d\n", "", str_replace("O", "", $perm), $perms[$LENGTH][$key]);
    }
    printf("\n%10s %15s %20d\n", "min(max)", "", min($perms[$LENGTH]));
}

///////////////////////////////////////////////////////////////////////////
// main program

// construct all possible permutations

$letters2 = str_split($letters);

// result of permute ends up in perms1
$perms1 = array();
pc_permute($letters2);
sort($perms1[$STRING]);

// add O to all permutations
foreach($perms1[$STRING] as $key => $perm) {
    $perm_tmp = "O" . $perm;
    $perm_tmp = str_replace("AB", "AOB", $perm_tmp);
    $perm_tmp = str_replace("BA", "BOA", $perm_tmp);
    $perm_tmp = str_replace("AC", "AOC", $perm_tmp);
    $perm_tmp = str_replace("CA", "COA", $perm_tmp);
    $perms1[$STRING][$key] = $perm_tmp;
}

// calculate length

foreach ($perms1[$STRING] as $key1 => $perm) {
    $length_sum = 0;
    $perm_tmp = str_split($perm);
    foreach($perm_tmp as $key2 => $letter) {
        if($key2 != 0) {
            $sub_path = $prev . $letter;
            $length_sum += $lengths[$sub_path];
        }
        $prev = $letter;        
    }
    $perms1[$LENGTH][$key1] = $length_sum;
}

print_perms($perms1);

?>
