<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 1000000;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from -1 to 1
function random_m1_1()
{
    return 2 * (float)rand() / (float)getrandmax() - 1;
}

///////////////////////////////////////////////////////////////////////////
// main program

// monte carlo
for($i=0;$i<$loops;$i++) {
    // pick 2 random points
    $x1 = random_m1_1();
    $y1 = random_m1_1();
    $x2 = random_m1_1();
    $y2 = random_m1_1();
    
    // check distance to center
    $d1 = $x1 * $x1 + $y1 * $y1;
    $d2 = $x2 * $x2 + $y2 * $y2;
    if(1 < $d1 || 1 < $d2) {
        continue;
    }
    
    // find the midpoint between the points
    $xm = ($x1 + $x2) / 2;
    $ym = ($y1 + $y2) / 2;
    
    // slant of line through the 2 points
    $a1 = ($y2 - $y1) / ($x2 - $x1);
    
    // translate midpoint
    $xm2 = ($xm / $a1 + $ym) / ($a1 + 1 / $a1);
    $ym2 = $a1 * $xm2;
    
    // calculate the distance from center to midpoint
    //    $dm = pow($xm * $xm + $ym * $ym, 0.5);
    $dm = pow($xm2 * $xm2 + $ym2 * $ym2, 0.5);
    
    // calculate area of circle dm <= x <= 1
    // it would be the same as constucting the actual lines
    // it will be the smaller of the areas
    // https://en.wikipedia.org/wiki/Circular_segment
    // d = cos(t/2)
    $t = 2 * acos($dm);
    $a = ($t - sin($t)) / 2;
    
    // area of full circle
    $ac = pi();
    
    // fraction of circular segment
    $f = $a / $ac;
    
    $fractions[] = $f;
}

$expected_fraction = array_sum($fractions) / sizeof($fractions);

// compensate for calculating the smaller fraction
printf("Expected fraction: %8.5f\n", 1 - $expected_fraction);

?>
