<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops1 = 100;
$loops2 = 10000000; // 10000000 is nice and high (10.000.000)
$part = 2;
if($loops2 <= 10) {
    $test = true;
} else {
    $test = false;
}

///////////////////////////////////////////////////////////////////////////
// functions

function rotate_teams($teams) {
    $teams[] = $teams[0];
    unset($teams[0]);
    // reindex
    $teams = array_values($teams);
    return $teams;
}

///////////////////////////////////////////////////////////////////////////
// main program

if($part == 1) {
    // west, east
    $teams = ["w", "e"];
} else {
    // stArs, stRipes, International
    $teams = ["a", "r", "i"];
}
foreach($teams as $team) {
    $points[$team] = 0;
}

if($test) {
    $points_text = implode(",", $teams);
}

// first a long time
for($i=0;$i<$loops1;$i++) {
    $j = rand(0, 1);
    $winner = $teams[$j];
    $loser = $teams[1 - $j];
    
    if($points[$winner] == 0) {
        // this is a new win
        $points[$winner] = 1;
    } else {
        // this makes a streak longer
        $points[$winner]++;
    }
    $points[$loser] = 0;
    
    $teams = rotate_teams($teams);
}

if($test) {
    printf("Teams: %s. Points: %s.\n", implode(",", $teams), $points_text);
}

// and then a few dips into the state
for($i=0;$i<$loops2;$i++) {
    $j = rand(0, 1);
    $winner = $teams[$j];
    $loser = $teams[1 - $j];
    
    if($points[$winner] == 0) {
        // this is a new win
        $points[$winner] = 1;
    } else {
        // this makes a streak longer
        $points[$winner]++;
    }
    $points[$loser] = 0;
    
    // register streak
    $streak = max($points);
    if(isset($streaks[$streak])) {
        $streaks[$streak]++;
    } else {
        $streaks[$streak] = 1;
    }
    
    if($test) {
        printf("Teams: %s. Points: %s. Winner: %s. Loser: %s. New streak: %d\n",
            implode(",", $teams), implode(",", $points), $winner, $loser, $streak);
    }

    $teams = rotate_teams($teams);
}

$average = 0;
foreach($streaks as $streak => $freq) {
    $average += $streak * $freq;
}
$average /= $loops2;

printf("Result, part %d: %.4f\n", $part, $average);

// https://math.stackexchange.com/questions/1325254/what-does-sum-k-0-infty-frack2k-converge-to

?>
