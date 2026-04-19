<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

function add_number($d1, $d2, $d3, $d4) {
    global $all_three_digit_numbers;
    
    // all 6 combinations with this d1
    $number = (int) ($d1 . $d2 . $d3);
    $all_three_digit_numbers[$number] = $number;
    
    $number = (int) ($d1 . $d2 . $d4);
    $all_three_digit_numbers[$number] = $number;
    
    $number = (int) ($d1 . $d3 . $d2);
    $all_three_digit_numbers[$number] = $number;
    
    $number = (int) ($d1 . $d3 . $d4);
    $all_three_digit_numbers[$number] = $number;
    
    $number = (int) ($d1 . $d4 . $d2);
    $all_three_digit_numbers[$number] = $number;
    
    $number = (int) ($d1 . $d4 . $d3);
    $all_three_digit_numbers[$number] = $number;
}

///////////////////////////////////////////////////////////////////////////
// main program

// handmade dice and test
// 6 means the face can be read as 6 or 9
$dice[1] = [1,6,3,4,5,8];
$dice[2] = [1,2,7,4,5,0];
$dice[3] = [1,2,3,7,5,6];
$dice[4] = [6,2,3,4,8,0];

// first create all groups of digits

$group[0][] = [];
for ($i=1;$i<=4;$i++) {
    foreach ($dice[$i] as $face) {
        foreach ($group[$i-1] as $smaller_group) {
            $larger_group = $smaller_group;
            $larger_group[] = $face;
            $group[$i][] = $larger_group;
        }
    }
    unset($group[$i-1]);
}

// create all 3 digit numbers
foreach($group[4] as $dice_group) {
    
    for($i=1;$i<=4;$i++) {
        [$d1, $d2, $d3, $d4] = $dice_group;
        
        add_number($d1, $d2, $d3, $d4);

        // rotate dice
        $d0 = array_shift($dice_group);
        $dice_group[] = $d0;
    }
}

// not very elegant
// include all swaps from 6 to 9
foreach ($all_three_digit_numbers as $number) {
    [$d0, $d1, $d2, $d3] = str_split((string) ($number + 1000));
    if($d1 == 6) {
        $new_number = (int) (9 . $d2 . $d3);
        $all_three_digit_numbers[$new_number] = $new_number;
    }
    if($d2 == 6) {
        $new_number = (int) ($d1 . 9 . $d3);
        $all_three_digit_numbers[$new_number] = $new_number;
    }
    if($d3 == 6) {
        $new_number = (int) ($d1 . $d2 . 9);
        $all_three_digit_numbers[$new_number] = $new_number;
    }
    if($d1 == 6 && $d2 == 6) {
        $new_number = (int) (9 . 9 . $d3);
        $all_three_digit_numbers[$new_number] = $new_number;
    }
    if($d1 == 6 && $d3 == 6) {
        $new_number = (int) (9 . $d2 . 9);
        $all_three_digit_numbers[$new_number] = $new_number;
    }
    if($d2 == 6 && $d3 == 6) {
        $new_number = (int) ($d1 . 9 . 9);
        $all_three_digit_numbers[$new_number] = $new_number;
    }
}

ksort($all_three_digit_numbers);

print_r($all_three_digit_numbers);

printf("Created %d numbers\n", sizeof($all_three_digit_numbers));

for($i=1;$i<1000;$i++) {
    if(!isset($all_three_digit_numbers[$i])) {
        printf("Missing: %3d\n", $i);
    }
}

?>
