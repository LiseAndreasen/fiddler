<?php

///////////////////////////////////////////////////////////////////////////
// constants

$queen_bee_min = 100000;
$queen_bee_max = 1000000;

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

$queen_bee_ambiguous = 0;

// test whether a value and a neighbor can produce the same cutoff
for($queen_bee_value=$queen_bee_min;$queen_bee_value<$queen_bee_max;$queen_bee_value++) {
    $queen_bee_lower = $queen_bee_value - 1;
    $queen_bee_higher = $queen_bee_value + 1;

    $genius_cutoff_lower = round($queen_bee_lower * 0.7);
    $genius_cutoff_this = round($queen_bee_value * 0.7);
    $genius_cutoff_higher = round($queen_bee_higher * 0.7);
    
    if($genius_cutoff_lower == $genius_cutoff_this || $genius_cutoff_this == $genius_cutoff_higher) {
        $queen_bee_ambiguous++;
    }
}

printf("\nResult 1: %.5f\n", $queen_bee_ambiguous / ($queen_bee_max - $queen_bee_min));

///////////////////////////////////////////////////////////////////////////



?>