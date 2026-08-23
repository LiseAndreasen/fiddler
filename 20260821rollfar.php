<?php

///////////////////////////////////////////////////////////////////////////
// constants

$dt = 0.01;      // how much does t grow each time?
$loops = 100000;

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1() {
    return (float)rand() / (float)getrandmax();
}

// b: distance between center of circle and light / traveling point
// radius of circle is 1
function calculate_arc_length($b) {
    global $dt;
    
    // x = t - b * sin(t)
    // y = 1 - b * cos(t)
    // 0 <= t <= 2 * pi
    
    $t_min = 0;
    $t_max = 2 * pi();
    $arc_length = 0;
    for($t=$t_min;$t<=$t_max;$t+=$dt) {
        $x1 = $t - $b * sin($t);
        $y1 = 1 - $b * cos($t);
        if($t != $t_min) {
            $x = $x1 - $x0;
            $y = $y1 - $y0;
            $distance = pow(($x*$x+$y*$y), 0.5);
            $arc_length += $distance;
        }
        $x0 = $x1;
        $y0 = $y1;
    }
    
    return $arc_length;
}

///////////////////////////////////////////////////////////////////////////
// main program

$distance_between_light_and_center = 1;
$result = calculate_arc_length($distance_between_light_and_center);

printf("Result 1: %f\n", $result);

///////////////////////////////////////////////////////////////////////////

$sum_of_arc_lengths = 0;
for($i=0;$i<=$loops;$i++) {
    if($i % 10000 == 0) {
        // progress
        print("#");
    }
    // only look at points in upper right quadrant
    $x = random_0_1();
    $y = random_0_1();
    $distance_between_light_and_center = pow(($x*$x+$y*$y), 0.5);
    $r = 1;         // radius of circle
    while($r < $distance_between_light_and_center) {
        // keep looking until the point is within the circle
        $x = random_0_1();
        $y = random_0_1();
        $distance_between_light_and_center = pow(($x*$x+$y*$y), 0.5);
    }
    $arc_length = calculate_arc_length($distance_between_light_and_center);
    $sum_of_arc_lengths += $arc_length;
}
print("\n");        // related to progress

printf("Result 2: %f\n", $sum_of_arc_lengths / $loops);

?>
