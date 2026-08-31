<?php

///////////////////////////////////////////////////////////////////////////
// constants

$queen_bee_min = 10000;
$queen_bee_max = 100000;

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
    
    if($genius_cutoff_lower == $genius_cutoff_this
    || $genius_cutoff_this == $genius_cutoff_higher) {
        $queen_bee_ambiguous++;
    }
}

printf("Result 1: %.5f\n", 1 - $queen_bee_ambiguous
    / ($queen_bee_max - $queen_bee_min));

///////////////////////////////////////////////////////////////////////////

$cutoffs = [];

// store cutoff values for each queen bee value
for($queen_bee_value=$queen_bee_min-1;$queen_bee_value<=$queen_bee_max;$queen_bee_value++) {   
    $amazing_cutoff = round($queen_bee_value * 0.50);
    $great_cutoff = round($queen_bee_value * 0.40);
    $nice_cutoff = round($queen_bee_value * 0.25);
    $solid_cutoff = round($queen_bee_value * 0.15);
    $good_cutoff = round($queen_bee_value * 0.08);
    $moving_up_cutoff = round($queen_bee_value * 0.05);
    $good_start_cutoff = round($queen_bee_value * 0.02);
    $cutoffs[$queen_bee_value] = [$amazing_cutoff, $great_cutoff,
        $nice_cutoff, $solid_cutoff, $good_cutoff, $moving_up_cutoff, $good_start_cutoff];
}

$queen_bee_ambiguous = 0;

for($queen_bee_value=$queen_bee_min;$queen_bee_value<$queen_bee_max;$queen_bee_value++) {
    $lower = sizeof(array_diff_assoc($cutoffs[$queen_bee_value-1],
        $cutoffs[$queen_bee_value]));
    $higher = sizeof(array_diff_assoc($cutoffs[$queen_bee_value],
        $cutoffs[$queen_bee_value+1]));
    if(0 == $lower || 0 == $higher) {
        $queen_bee_ambiguous++;
    }
}

printf("Result 2: %.5f\n", 1 - $queen_bee_ambiguous
    / ($queen_bee_max - $queen_bee_min));


?>
