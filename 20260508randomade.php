<?php

///////////////////////////////////////////////////////////////////////////
// constants

$fiddler = false;           // or extra credit?
$fiddler_text = $fiddler ? 'True' : 'False';
$loops = 10000000;

///////////////////////////////////////////////////////////////////////////
// functions

function random12oz() {
    return (float)rand() * 12 / (float)getrandmax();
}

///////////////////////////////////////////////////////////////////////////
// main program

$pitcher_sum = 0;
for($i=0;$i<$loops;$i++) {
    if($fiddler) {
        $g[0] = random12oz();       // fill glass
        $g[1] = random12oz();       // fill glass
        sort($g);
        $pitcher = $g[0] * 2;       // move to pitcher
        $g[1] -= $g[0];
        $g[0] = 0;
        $g[0] = random12oz();       // fill glass
        sort($g);
        $pitcher += $g[0] * 2;      // move to pitcher 
        $pitcher_sum += $pitcher;
    } else {
        $g[0] = random12oz();       // fill glass
        $g[1] = random12oz();       // fill glass
        $g[2] = random12oz();       // fill glass
        sort($g);
        $pitcher = $g[0] * 3;       // move to pitcher
        $g[2] -= $g[0];
        $g[1] -= $g[0];
        $g[0] = 0;
        $g[0] = random12oz();       // fill glass
        sort($g);
        $pitcher += $g[0] * 3;       // move to pitcher
        $g[2] -= $g[0];
        $g[1] -= $g[0];
        $g[0] = 0;
        $g[0] = random12oz();       // fill glass
        sort($g);
        $pitcher += $g[0] * 3;       // move to pitcher
        $pitcher_sum += $pitcher;
    }
}

printf("Fiddler? %5s. %8d loops. Amount of randomade, average: %8.5f\n", $fiddler_text, $loops, $pitcher_sum / $loops);

?>
