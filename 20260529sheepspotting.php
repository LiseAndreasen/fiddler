<?php

///////////////////////////////////////////////////////////////////////////
// constants

$X = 0;             // sheep location
$Y = 1;
$DIRX = 2;          // sheep looks from position to this point
$DIRY = 3;
$A = 4;             // sheep stands on line y = ax+b
$B = 5;             // looking towards DIR point

$loops = 10000000;
$fiddler = false;

///////////////////////////////////////////////////////////////////////////
// functions

function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

function a_b($sheep) {
    // https://www.sciencing.com/vector-perpendicular-8419773/
    [$xx, $yy, $dirxx, $diryy] = $sheep;
    // vector of sheep looking
    $v = [$dirxx - $xx, $diryy - $yy];
    // slope of that vector
    $aa = $v[1] / $v[0];
    // slope of perpendicular line
    $a = - 1 / $aa;
    // line goes through ($xx, $yy)
    $b = $yy - $a * $xx;
    return [$a, $b];
}

///////////////////////////////////////////////////////////////////////////
// main program

// assume pen is 1x1

if($fiddler) {
    $no_of_sheep = 2;
} else {
    $no_of_sheep = 3;
}

$no_of_sightings = 0;

for($l=0;$l<$loops;$l++) {
    $sight = [];
    
    for($i=1;$i<=$no_of_sheep;$i++) {
        $sheep[$i][$X] = random_0_1();
        $sheep[$i][$Y] = random_0_1();
        
        $dir = 2 * pi() * random_0_1();        
        $sheep[$i][$DIRX] = $sheep[$i][$X] + cos($dir);
        $sheep[$i][$DIRY] = $sheep[$i][$Y] + sin($dir);

        [$aa, $bb] = a_b($sheep[$i]);
        $sheep[$i][$A] = $aa;
        $sheep[$i][$B] = $bb;
    }
    
    for($i=1;$i<=$no_of_sheep;$i++) {
        for($j=1;$j<=$no_of_sheep;$j++) {
            if($i==$j) {
                continue;
            }
            $aa = $sheep[$i][$A];
            $bb = $sheep[$i][$B];
            $x1 = $sheep[$i][$DIRX];
            $y1 = $sheep[$i][$DIRY];
            $x2 = $sheep[$j][$X];
            $y2 = $sheep[$j][$Y];
            // https://www.careers360.com/maths/position-of-two-points-with-respect-to-a-line-topic-pge
            // can sheep i see sheep j? 0 < result, yes
            $sight[] = ($aa * $x1 - $y1 + $bb) / ($aa * $x2 - $y2 + $bb);
        }
    }
    
    if(0 < min($sight)) {
        $no_of_sightings++;
    }
}

printf("No. of sheep......: %d\n", $no_of_sheep);
printf("Loops.............: %d\n", $loops);
printf("Ratio of sightings: %7.5f\n", $no_of_sightings / $loops);

?>
