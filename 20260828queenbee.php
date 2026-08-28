<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 100000000;

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

$queen_bee_ambigous = 0;

for($i=0;$i<$loops;$i++) {
    // progress
    if($i * 100 % $loops == 0) {
        print(".");
    }
    
    $genius_cutoff = rand(10000, 100000);
    
    $queen_bee_estimate = $genius_cutoff / 0.7;
    $queen_bee_higher = ceil($queen_bee_estimate);
    $queen_bee_lower = floor($queen_bee_estimate);
    
    $genius_cutoff_higher = round($queen_bee_higher * 0.7);
    $genius_cutoff_lower = round($queen_bee_lower * 0.7);
    
    // we want 2 different queen bee values to produce different genius cutoffs
    if($genius_cutoff_lower == $genius_cutoff_higher) {
        $queen_bee_ambigous++;
    }
}

printf("\nResult 1: %.5f\n", $queen_bee_ambigous / $loops);

?>