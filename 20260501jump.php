<?php

///////////////////////////////////////////////////////////////////////////
// constants

// fiddler
// starting point is (0,0)
// begin by looking at jumps to points with |x|, |y| <= max distance
$max_distance = 8;

// extra credit
$legal_jumps = [
    [ 1, 8], [ 1,-8], [-1, 8], [-1,-8],
    [ 4, 7], [ 4,-7], [-4, 7], [-4,-7],
    [ 7, 4], [ 7,-4], [-7, 4], [-7,-4],
    [ 8, 1], [ 8,-1], [-8, 1], [-8,-1]
];

//$progress_max = 170;

///////////////////////////////////////////////////////////////////////////
// functions

// extra credit
// https://www.geeksforgeeks.org/dsa/the-knights-tour-problem/

// Count the number of onward moves from position (x, y)
function count_options($board, $x, $y) {
    global $legal_jumps;
    
    $count = 0;
    $n = sizeof($board);
    foreach($legal_jumps as $jump) {
        [$dx, $dy] = $jump;
        $nx = $x + $dx;
        $ny = $y + $dy;
        if(0 <= $nx && $nx < $n && 0 <= $ny && $ny < $n && $board[$nx][$ny] == -1) {
            $count++;
        }
    }
    return $count;
}

// Generate valid knight moves from (x, y), sorted by fewest onward moves
function get_sorted_moves($board, $x, $y) {
    global $legal_jumps;
    
    $move_list = [];
    $n = sizeof($board);
    foreach($legal_jumps as $i => $jump) {
        [$dx, $dy] = $jump;
        $nx = $x + $dx;
        $ny = $y + $dy;
        if(0 <= $nx && $nx < $n && 0 <= $ny && $ny < $n && $board[$nx][$ny] == -1) {
            $options = count_options($board, $nx, $ny);
            $move_list[] = [$options, $i];
        }
    }
    sort($move_list);
    return $move_list;
}

// Recursive function to solve the Knight's Tour
function knight_tour_util($x, $y, $step, $n, &$board) {
    global $legal_jumps;
//    global $progress_bar, $progress_max;
    
    if($step == $n * $n) {
        return true;
    }
    $moves = get_sorted_moves($board, $x, $y);
    foreach($moves as $i => $move) {
        $legal_jump_idx = $move[1];
        [$dx, $dy] = $legal_jumps[$legal_jump_idx];
        $nx = $x + $dx;
        $ny = $y + $dy;
        $board[$nx][$ny] = $step;
        if(knight_tour_util($nx, $ny, $step + 1, $n, $board)) {
            return true;
        }
        
        // backtrack
        $board[$nx][$ny] = -1;
    }
    return false;
}

// Function to start Knight's Tour
function knight_tour($n) {
    $board_column = array_fill(0, $n, -1);
    $board = array_fill(0, $n, $board_column);
    // assume tour possible beginning from (0,0)
    $board[0][0] = 0;
    if(knight_tour_util(0, 0, 1, $n, $board)) {
        return $board;
    }
    return [[-1]];
}

function print_map($map) {
    foreach($map[0] as $j => $cell) {
        foreach($map as $i => $col) {
            printf(" %3d", $map[$i][$j]);
        }
        echo "\n";
    }
    for($i=0;$i<sizeof($map);$i++) {
        echo "====";
    }
    echo "\n";
}

function print_python($map) {
    print("[\n");
    foreach($map[0] as $j => $cell) {
        print("[");
        foreach($map as $i => $col) {
            printf("%3d,", $map[$i][$j]);
        }
        echo "]\n";
    }
    echo "]\n";
}

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

///////////////////////////////////////////////////////////////////////////
// extra credit

$n = 15;
printf("\nLooking for loop around all cells on %dx%d board.\n", $n, $n);
$result = knight_tour($n);
print_map($result);

$n = 2;
$keep_going = true;
while($keep_going) {
    // is nxn board connected?
    $board2 = [];
    $all_cells = [[0,0]];
    $board2[0][0] = 0;
    while(0 < sizeof($all_cells)) {
        [$x, $y] = array_shift($all_cells);
        foreach($legal_jumps as $jump) {
            [$dx, $dy] = $jump;
            $newx = $x + $dx;
            $newy = $y + $dy;
            if(0 <= $newx && $newx < $n && 0 <= $newy && $newy < $n) {
                if(!isset($board2[$newx][$newy])) {
                    $all_cells[] = [$newx, $newy];
                    $board2[$newx][$newy] = $board2[$x][$y] + 1;
                    $i++;
                }
            }
        }
    }
    foreach($board2 as $x => $col) {
        ksort($board2[$x]);
    }
    ksort($board2);
    
    $board2_flat = array_merge(...$board2);
    $board2_size = sizeof($board2_flat);
    
    printf("Available cells on %d x %d board: %d\n", $n, $n, $board2_size);
    if($board2_size < $n * $n) {
        print("Not enough.\n");
        $n++;
    } else {
        print_map($board2);
        $keep_going = false;
    }
}

?>
