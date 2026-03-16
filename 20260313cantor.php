<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 1000000;
$FIDDLER = true;
$EXTRA_CREDIT = false;
$no_to_cantor_val = [];

///////////////////////////////////////////////////////////////////////////
// functions

// auxiliary function
// returns random number with flat distribution from 0 to 1
function random_0_1()
{
    return (float)rand() / (float)getrandmax();
}

function monte_carlo_not_cantor($fiddler) {
    global $loops;
    
    if($fiddler) {
        $points = 2;
    } else {
        $points = 3;
    }
    
    $dist_sum = 0;
    $triangle_count = 0;
    for($i=0;$i<$loops;$i++) {
        $p = [];
        // so many points
        for($k=0;$k<$points;$k++) {
            $p[] = random_0_1();
        }
        
        if($fiddler) {
            $dist = abs($p[0] - $p[1]);
            $dist_sum += $dist;
        } else {
            sort($p);
            if($p[2] < $p[0] + $p[1]) {
                $triangle_count++;
            }
        }
    }
    
    if($fiddler) {
        return $dist_sum / $loops;
    } else {
        return $triangle_count / $loops;
    }
}

function monte_carlo($fiddler) {
    global $loops, $sensitivity;
    
    if($fiddler) {
        $points = 2;
    } else {
        $points = 3;
    }
    
    $dist_sum = 0;
    $triangle_count = 0;
    for($i=0;$i<$loops;$i++) {
        $p = [];
        // so many points
        for($k=0;$k<$points;$k++) {
            $range = [0, 1];
            // make length smaller a few times
            for($j=0;$j<$sensitivity;$j++) {
                [$a, $b] = $range;
                $upper_lower = rand(0, 1);
                if($upper_lower == 0) {
                    // split in 3, keep lower part
                    $new_b = ($b - $a) / 3 + $a;
                    $range = [$a, $new_b];
                } else {
                    // split in 3, keep upper part
                    $new_a = $b - ($b - $a) / 3;
                    $range = [$new_a, $b];
                }
            }
            $upper_lower = rand(0, 1);
            if($upper_lower == 0) {
                $p[] = $range[0];
            } else {
                $p[] = $range[1];
            }
        }
        
        if($fiddler) {
            $dist = abs($p[0] - $p[1]);
            $dist_sum += $dist;
        } else {
            sort($p);
            if($p[2] < $p[0] + $p[1]) {
                $triangle_count++;
            }
        }
    }
    
    if($fiddler) {
        return $dist_sum / $loops;
    } else {
        return $triangle_count / $loops;
    }
}

// given a number and a level
// return a member of the cantor set
function no_to_cantor($no, $level) {
    // memoization
    global $no_to_cantor_val;
    
    if(isset($no_to_cantor_val[$no][$level])) {
        return $no_to_cantor_val[$no][$level];
    } else {
        $bin = decbin($no);
        $tri = str_replace(1, 2, $bin);
        $dec = base_convert($tri, 3, 10) / pow(3, $level);
        $no_to_cantor_val[$no][$level] = $dec;
        return $dec;
    }
}

function all_combinations($level, $fiddler) {
    // at a level
    // there are 2^level endpoints
    // 1: 2
    // 2: 4
    // see each endpoint as the binary number
    // like for level 2, the numbers 0-3
    // but in binary, 00-11
    // but in ternary, 00-22
    // that is, each 1 converted to a 2
    // and interpreted as base 3
    // level 2: 00, 01, 10, 11
    // becomes: 00, 02, 20, 22
    // which is: 0, 2/9, 6/9, 8/9
    if($fiddler) {
        $dist_sum = 0;
        for($i=0;$i<pow(2, $level);$i++) {
            if(7 < $level && $i % 10 == 0) { print("."); } // progress
            $i_dec = no_to_cantor($i, $level);
            for($j=0;$j<pow(2, $level);$j++) {
                $j_dec = no_to_cantor($j, $level);
                $dist_sum += abs($j_dec - $i_dec);
            }
        }
        if(7 < $level) { print("\n"); } // progress
        return $dist_sum / pow(2, $level * 2);
    } else {
        $triangle_count = 0;
        for($i=0;$i<pow(2, $level);$i++) {
            if(7 < $level && $i % 10 == 0) { print("."); } // progress
            $i_dec = no_to_cantor($i, $level);
            for($j=0;$j<pow(2, $level);$j++) {
                $j_dec = no_to_cantor($j, $level);
                for($k=0;$k<pow(2, $level);$k++) {
                    $k_dec = no_to_cantor($k, $level);
                    $p = [$i_dec, $j_dec, $k_dec];
                    sort($p);
                    if($p[2] < $p[0] + $p[1]) {
                        $triangle_count++;
                    }
                }
            }
        }
        if(7 < $level) { print("\n"); } // progress
        return $triangle_count / pow(2, $level * 3);
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

printf("Result for      fiddler,  ! cantor, %7d loops: %.5f\n",
    $loops, monte_carlo_not_cantor($FIDDLER));
printf("Result for extra credit,  ! cantor, %7d loops: %.5f\n",
    $loops, monte_carlo_not_cantor($EXTRA_CREDIT));

$sensitivity = 30;
printf("Result for      fiddler, %2d digits, %7d loops: %.5f\n",
    $sensitivity, $loops, monte_carlo($FIDDLER));
printf("Result for extra credit, %2d digits, %7d loops: %.5f\n",
    $sensitivity, $loops, monte_carlo($EXTRA_CREDIT));

for($no_digits=1;$no_digits<=12;$no_digits++) {
    printf("Result for      fiddler, %2d digit(s)..............: %.5f\n",
        $no_digits, all_combinations($no_digits, $FIDDLER));
}

for($no_digits=1;$no_digits<=10;$no_digits++) {
    printf("Result for extra credit, %2d digit(s)..............: %.5f\n",
        $no_digits, all_combinations($no_digits, $EXTRA_CREDIT));
}

?>
