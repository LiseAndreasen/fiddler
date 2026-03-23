<?php

///////////////////////////////////////////////////////////////////////////
// constants

$x_jump = 0.0005;
$y_threshold = 0.0005;
$desmos = false;

///////////////////////////////////////////////////////////////////////////
// functions

// https://math.stackexchange.com/a/3190374
// find formula for tangent to circle c (radius 1) through point p
function calculate_tangent($cx, $cy, $px, $py) {
    $dx = $px - $cx;
    $dy = $py - $cy;
    $dxr = - $dy;
    $dyr = $dx;
    $d = pow($dx * $dx + $dy * $dy, 0.5);
    $rho = 1 / $d;
    $ad = $rho * $rho;
    $bd = $rho * pow(1 - $rho * $rho, 0.5);
    $t1[] = $cx + $ad * $dx + $bd * $dxr;
    $t1[] = $cy + $ad * $dy + $bd * $dyr;
    $t2[] = $cx + $ad * $dx - $bd * $dxr;
    $t2[] = $cy + $ad * $dy - $bd * $dyr;
    // ax + by + c = 0
    $l1 = [$py - $t1[1], $t1[0] - $px, $t1[1] * $px - $t1[0] * $py];
    $l2 = [$py - $t2[1], $t2[0] - $px, $t2[1] * $px - $t2[0] * $py];
    return [$t1, $t2, $l1, $l2];
}

function calculate_string($x, $y) {
    if(0 <= $y && $y <= 1) {
        // case 1: 0 <= y <= 1
    
        // the piece of string, that's round
        // first the piece on the left
        $s1 = pi();
        // then add the 2 pieces on the right
        
        $tangents = calculate_tangent(0, 0, $x, $y);
        [$t1, $t2, $l1, $l2] = $tangents;
        $s1 += pi() / 2 - atan($t1[1] / $t1[0]);
        $s1 += pi() / 2 + atan($t2[1] / $t2[0]);
        
        // add straight pieces on the right
        $s2 = pow(($x - $t1[0]) * ($x - $t1[0]) + ($y - $t1[1]) * ($y - $t1[1]), 0.5);
        $s2 += pow(($x - $t2[0]) * ($x - $t2[0]) + ($y - $t2[1]) * ($y - $t2[1]), 0.5);
        
        // add straight pieces in the middle
        $s3 = 2 * 2;
        return $s1 + $s2 + $s3;
    }
    
    if(1 <= $y) {
        // case 2: 1 <= y
        
        // the piece of string, that's round
        // first the piece on the left
        $tangents = calculate_tangent(-2, 0, $x, $y);
        [$t1b, $t2b, $l1b, $l2b] = $tangents;
        $s1 = pi() / 2 - atan($t1b[1] / ($t1b[0] + 2));
        
        // then add the piece on the right
        
        $tangents = calculate_tangent(0, 0, $x, $y);
        [$t1a, $t2a, $l1a, $l2a] = $tangents;
        $s1 += pi() / 2 + atan($t2a[1] / $t2a[0]);

        // add straight pieces on the right
        $s2 = pow(($x - $t1a[0]) * ($x - $t1a[0]) + ($y - $t1a[1]) * ($y - $t1a[1]), 0.5);
        $s2 += pow(($x - $t2b[0]) * ($x - $t2b[0]) + ($y - $t2b[1]) * ($y - $t2b[1]), 0.5);
        
        // add straight piece in the middle
        $s3 = 2;
        return $s1 + $s2 + $s3;
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

// given 2 unit circles (0,0) and (-2,0), find a point
// that means a string looping around the circles and the point
// have length 14

for($x=-1;$x<=3.11;$x+=$x_jump) {
    if($x==0) {
        continue;
    }
    $y_min = 1.00001;
    $y_max = 4;
    $min_string = calculate_string($x, $y_min);
    $max_string = calculate_string($x, $y_max);
    
    // min and max should be on opposite sides of 14
    if(0 < ($min_string - 14) * ($max_string - 14)) {
        continue;
    }
    while($y_threshold < abs($y_max - $y_min)) {
        $y_mid = ($y_min + $y_max) / 2;
        $mid_string = calculate_string($x, $y_mid);
        // if min and mid are on opposite sides of 14
        if(0 < ($min_string - 14) * ($mid_string - 14)) {
            $y_min = $y_mid;
        } else {
            $y_max = $y_mid;
        }
    }
    $points[] = [$x, $y_min];
}

// avoid y = 1

for($x=3.12;$x<=3.5;$x+=$x_jump) {
    if($x==0) {
        continue;
    }
    $y_min = 0;
    $y_max = 0.99999;
    $min_string = calculate_string($x, $y_min);
    $max_string = calculate_string($x, $y_max);
    
    // min and max should be on opposite sides of 14
    if(0 < ($min_string - 14) * ($max_string - 14)) {
        continue;
    }
    while($y_threshold < abs($y_max - $y_min)) {
        $y_mid = ($y_min + $y_max) / 2;
        $mid_string = calculate_string($x, $y_mid);
        // if min and mid are on opposite sides of 14
        if(0 < ($min_string - 14) * ($mid_string - 14)) {
            $y_min = $y_mid;
        } else {
            $y_max = $y_mid;
        }
    }
    $points[] = [$x, $y_min];
}

// calculate area, estimate
$area = 0;
$first_one = true;
foreach($points as $p) {
    if($desmos) {
        print("$p[0]\t$p[1]\n");
    }
    
    if($first_one) {
        $first_one = false;
        $prev = $p;
        continue;
    }
    // x1 < x2
    [$x1, $y1] = $prev;
    [$x2, $y2] = $p;
    $area += ($x2 - $x1) * ($y1 + $y2) / 2;
    
    $prev = $p;
}

printf("Extra credit: %.3f\n", 4 * $area);

?>
