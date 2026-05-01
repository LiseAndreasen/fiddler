<?php

///////////////////////////////////////////////////////////////////////////
// constants

// starting point is (0,0)
// begin by looking at jumps to points with |x|, |y| <= max distance
$max_distance = 8;

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

// look at all jumps from (,0) to (x,y)
for($x=-$max_distance;$x<=$max_distance;$x++) {
    for($y=-$max_distance;$y<=$max_distance;$y++) {
        // distance to (0,0)^2
        $d = $x * $x + $y * $y;
        // distance 0 doesn't work
        if($d == 0) {
            continue;
        }
        $jumps[$d][] = [$x, $y];
    }
}

// sort so that the printout will be sorted
ksort($jumps);

// look at all double jumps
// go through all the possible values of distances for jumps
foreach($jumps as $d => $jump_list) {
    $double_jumps_tmp = [];
    
    // construct list of end points after 2 jumps
    foreach($jump_list as $jump1) {
        [$x1, $y1] = $jump1;
        foreach($jump_list as $jump2) {
            [$x2, $y2] = $jump2;
            $new_x = $x1 + $x2;
            $new_y = $y1 + $y2;
            if($new_x <= 0 || $new_y <= 0) {
                // don't go to (0,y) or (x,0) after 2 jumps ((5.1))
                // only look at options where x and y are both positive
                continue;
            }
            if(abs($new_x) == abs($new_y)) {
                // don't go to (x,x) or (x,-x) after 2 jumps ((5.2))
                continue;
            }
            if($new_y < $new_x) {
                // discard mirror solutions
                continue;
            }
            $double_jumps_tmp[$new_x][$new_y][$x1.$y1.$x2.$y2]
                = [$x1, $y1, $x2, $y2];
        }
    }
    
    // construct full paths by combining 2 x 2 jumps
    foreach($double_jumps_tmp as $new_x => $y_list) {
        foreach($y_list as $new_y => $combos) {
            if(1 < sizeof($combos)) {
                // this point can be reached in at least 2 different ways
                sort($combos);
                [$x1a, $y1a, $x2a, $y2a] = $combos[0];
                [$x1b, $y1b, $x2b, $y2b] = $combos[1];
                if(abs($x1a) == abs($y2a) && abs($y1a) == abs($x2a)) {
                    // this will be a square, just tilted ((4))
                    continue;
                }
                if($x1a == 0 || $y1a == 0 || $x1b == 0 || $y1b == 0) {
                    // first jump can't be to (0,y) or (x,0) ((5.1))
                    continue;
                }
                if(abs($x1a) == abs($y1a) || abs($x1b) == abs($y1b)) {
                    // first jump can't be to (x,x) or (x,-x) ((5.2))
                    continue;
                }
                printf("(%2d,%2d) can be reached in 2+ ways. (L^2 = %2d.)\n",
                    $new_x, $new_y, $d);
                foreach($combos as $combo) {
                    [$x1, $y1, $x2, $y2] = $combo;
                    printf("\t\t(0,0) -> (%2d,%2d) -> (%2d,%2d)\n",
                        $x1, $y1, $x2, $y2);
                }
            }
        }
    }
}

?>
