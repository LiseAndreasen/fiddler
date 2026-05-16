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

$hiding_places = ["A", "B"];

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
    
    $best_strategy = min($perms[$LENGTH]);
    foreach($perms[$LENGTH] as $key => $perm) {
        if($perm == $best_strategy) {
            printf("\n%10s %15s %20d\n", "min(max)", str_replace("O", "", $perms[$STRING][$key]), $best_strategy);
        }
    }
}

function construct_hiders($sofar) {
    global $hiders, $hiding_places, $N;
    
    if(sizeof($sofar) == $N) {
        $hiders[] = $sofar;
        return;
    }
    
    foreach($hiding_places as $place) {
        $sofar_next = $sofar;
        $sofar_next[] = $place;
        construct_hiders($sofar_next);
    }
}

function construct_seekers($sofar) {
    global $seekers, $N;
    
    if(sizeof($sofar) == 0) {
        $new_sofar_a[2] = "A";
        construct_seekers($new_sofar_a);
        $new_sofar_b[3] = "B";
        construct_seekers($new_sofar_b);
        return;
    }
    
    $max_key = max(array_keys($sofar));
    if($N <= $max_key) {
        // new strategy found
        $seekers[] = $sofar;
        return;
    }
    
    $this_place = $sofar[$max_key];
    if($this_place == "A") {
        $other_place = "B";
    } else {
        $other_place = "A";
    }
    
    // stay here 1 minute or go to the other place, in 5 minutes
    $sofar_next = $sofar;
    $sofar_next[$max_key + 1] = $this_place;
    construct_seekers($sofar_next);
    
    $sofar_next = $sofar;
    $sofar_next[$max_key + 5] = $other_place;
    construct_seekers($sofar_next);
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

// calculate max length of time
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

// find min(max)
print_perms($perms1);

///////////////////////////////////////////////////////////////////////////

print("\nSearching for best strategy against teleporter.\n\n");
$N = 12;

// construct all hider strategies of length N
$hiders = [];
construct_hiders([]);
$no_of_hiders = sizeof($hiders);

// construct all seeker strategies of length N
$seekers = [];
construct_seekers([]);
$no_of_seekers = sizeof($seekers);

// combine all strategies
foreach($seekers as $key1 => $seeker) {
    $time_sum = 0;
    $not_found = 0;
    foreach($hiders as $hider) {
        foreach($seeker as $key2 => $place) {
            if($N <= $key2) {
                // not found
                $time_sum += $N * 10;
                $not_found++;
                break;
            }
            if($hider[$key2] == $place) {
                // found!
                $time_sum += $key2;
                break;
            }
        }
    }
    $average_seek_time[$key1] = $time_sum / $no_of_hiders;
    $no_not_found[$key1] = $not_found;
}

// find the seeker strategy with the lowest average
$best_average_seek_time = min($average_seek_time);
foreach($average_seek_time as $key => $time) {
    if($time == $best_average_seek_time) {
        printf("Winner: %s, time %.5f, %d cases out of %d of not being found\n",
            implode("", $seekers[$key]), $average_seek_time[$key], $no_not_found[$key], $no_of_hiders);
    }
}

?>
