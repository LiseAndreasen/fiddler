<?php

///////////////////////////////////////////////////////////////////////////
// constants

// highest number on bingo card
$max_no = 64;
$small_card_size = 5;
$large_card_size = 8;
$bingo_size = 5;
$bingo_marked = 0;
$loops = 100000;

///////////////////////////////////////////////////////////////////////////
// functions

function print_histogram($data) {
    $no_of_buckets = sizeof($data);
    $max_bucket = max($data) + 1;
    // print histogram
    // height
    $h = 20;
    for($i=1;$i<$h+1;$i++) {
        $histogram[$i] = "";
    }
    $histogram[0] = "+";
    // assuming no of buckets less than width of screen
    for($i=1;$i<=1+$no_of_buckets/10;$i++) {
        $histogram[0] .= "---------+";
    }
    $histogram[$h+1] = $histogram[0];
    
    $sum_of_all = 0;
    $no_of_all = 0;
    for($i=0;$i<$no_of_buckets;$i++) {
        $column_top = (int) (($h + 1) * $data[$i] / $max_bucket);
        for($j=1;$j<=$column_top;$j++) {
            $histogram[$j] .= "X";
        }
        for($j=$column_top+1;$j<$h+1;$j++) {
            $histogram[$j] .= " ";
        }
        $sum_of_all += $i * $data[$i];
        $no_of_all += $data[$i];
    }
    for($i=$h+1;$i>=0;$i--) {
        print($histogram[$i] . "\n");
    }
    printf("Average: %.5f\n\n", $sum_of_all / $no_of_all);
}

function create_bingocard($width) {
    global $max_no, $small_card_size, $large_card_size;
    
    $card_matrix = [];        // card as matrix
    $card_list = [];          // numbers as list
    
    if($width == $large_card_size) {
        // if the called numbers and the small card are randon
        // the large card doesn't have to be
        for($r = 0; $r < $width; $r++) {
            for($c = 0; $c < $width; $c++) {
                // draw number
                $drawn_number = $r * $width + $c + 1;
                $card_matrix[$r][$c] = $drawn_number;
                $card_list[$drawn_number] = [$r, $c];
            }
        }
    } else {
        for($r = 0; $r < $width; $r++) {
            for($c = 0; $c < $width; $c++) {
                // draw number
                $drawn_number = rand(1, $max_no);
                // if number drawn before, keep drawing
                while(isset($card_list[$drawn_number])) {
                    $drawn_number = rand(1, $max_no);
                }
                $card_matrix[$r][$c] = $drawn_number;
                $card_list[$drawn_number] = [$r, $c];
            }
        }
    }
    return [$card_matrix, $card_list];
}

function check_bingo($card) {
    global $bingo_size, $bingo_marked;
    $card_size = sizeof($card);
    
    $bingo = false;
    for($r = 0;$r < $card_size;$r++) {
        for($c = 0;$c < $card_size;$c++) {
            ///////////////////////////////////////////////////
            // row bingo?
            if($c <= $card_size - $bingo_size) {
                $possible_bingo = true;
                for($ci = $c;$ci < $c + $bingo_size;$ci++) {
                    if($card[$r][$ci] != $bingo_marked) {
                        $possible_bingo = false;
                        break;
                    }
                }
                if($possible_bingo) {
                    return true;
                }
            }
            ///////////////////////////////////////////////////
            // column bingo?
            if($r <= $card_size - $bingo_size) {
                $possible_bingo = true;
                for($ri = $r;$ri < $r + $bingo_size;$ri++) {
                    if($card[$ri][$c] != $bingo_marked) {
                        $possible_bingo = false;
                        break;
                    }
                }
                if($possible_bingo) {
                    return true;
                }
            }
            ///////////////////////////////////////////////////
            // diagonal bingo moving forward?
            if($r <= $card_size - $bingo_size
            && $c <= $card_size - $bingo_size) {
                $possible_bingo = true;
                for($i = 0;$i < $bingo_size;$i++) {
                    $ri = $r + $i;
                    $ci = $c + $i;
                    if($card[$ri][$ci] != $bingo_marked) {
                        $possible_bingo = false;
                        break;
                    }
                }
                if($possible_bingo) {
                    return true;
                }
            }
            ///////////////////////////////////////////////////
            // diagonal bingo moving back?
            if($r <= $card_size - $bingo_size
            && $bingo_size - 1 <= $c) {
                $possible_bingo = true;
                for($i = 0;$i < $bingo_size;$i++) {
                    $ri = $r + $i;
                    $ci = $c - $i;
                    if($card[$ri][$ci] != $bingo_marked) {
                        $possible_bingo = false;
                        break;
                    }
                }
                if($possible_bingo) {
                    return true;
                }
            }
            ///////////////////////////////////////////////////
        }
    }
    return false;
}

///////////////////////////////////////////////////////////////////////////
// main program

$small_bingos = 0;
$large_bingos = 0;

// data for histogram
for($i=0;$i<=$large_card_size*$large_card_size;$i++) {
    $small_bingo_at_alone[$i] = 0;
    $large_bingo_at_alone[$i] = 0;
    $small_bingo_at_combined[$i] = 0;
    $large_bingo_at_combined[$i] = 0;
}

for($j=0;$j<$loops;$j++) {
    // progress
    if($j % 5000 == 0) {
        printf("%.3f ", $j / $loops);
    }
    
    // data for histogram
    $small_bingo = 0;
    $large_bingo = 0;
    
    [$small_card_matrix, $small_card_list] =
        create_bingocard($small_card_size);
    [$large_card_matrix, $large_card_list] =
        create_bingocard($large_card_size);
    
    // call numbers
    $called = [];
    for($i = 0; $i < $max_no; $i++) {
        $called_number = rand(1, $max_no);
        // keep going until number not called before
        // optimization: array of all 64 numbers shuffled
        while(isset($called[$called_number])) {
            $called_number = rand(1, $max_no);
        }
        // if number on small card
        if(isset($small_card_list[$called_number])) {
            [$r, $c] = $small_card_list[$called_number];
            $small_card_matrix[$r][$c] = $bingo_marked;
            $bingo = check_bingo($small_card_matrix);
            $small_bingo++;
            if($bingo) {
                $small_bingo_at_alone[$small_bingo]++;
                $small_bingo_at_combined[$small_bingo+$large_bingo]++;
                $small_bingos++;
                $bingo_called = true;
                break;
            }
        } else {
            // if not on small card, number will be on large card
            [$r, $c] = $large_card_list[$called_number];
            $large_card_matrix[$r][$c] = $bingo_marked;
            $bingo = check_bingo($large_card_matrix);
            $large_bingo++;
            if($bingo) {
                $large_bingo_at_alone[$large_bingo]++;
                $large_bingo_at_combined[$small_bingo+$large_bingo]++;
                $large_bingos++;
                $bingo_called = true;
                break;
            }
        }
        $called[$called_number] = $called_number;
    }
}

// progress
print("\n\n");

printf("Bingos on small vs large cards: %.5f %.5f\n",
    $small_bingos / $loops, $large_bingos / $loops);

print("Distribution, how many numbers necessary for bingo.\n\n");
print("Only looking at small card, igonoring numbers on large card.\n\n");
print_histogram($small_bingo_at_alone);
print("Same, for large card.\n\n");
print_histogram($large_bingo_at_alone);
print("Looking at small card bingos, including numbers on large.\n\n");
print_histogram($small_bingo_at_combined);
print("Same, for large card.\n\n");
print_histogram($large_bingo_at_combined);

?>
