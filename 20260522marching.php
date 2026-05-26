<?php

///////////////////////////////////////////////////////////////////////////
// constants

$r2 = 2; // radius of larger cylinder
$r1 = 1; // radius of smaller cylinder
$h = 2; // height of cylinder

///////////////////////////////////////////////////////////////////////////
// functions

function print_python($map) {
    print("[\n");
    foreach($map[20] as $j => $cell) {
        print("[");
        foreach($map as $i => $col) {
            if(isset($map[$i][$j])) {
                printf("%3d,", 1000 * $map[$i][$j]);
            } else {
                printf("%3d,", 1000 * 0);
            }
        }
        echo "],\n";
    }
    echo "]\n";
}

///////////////////////////////////////////////////////////////////////////
// main program

// a1 and a2 are the angles
// drawing angle a1 from the beginning point, to the center of the top disk,
// to the point where the path continues to the curved part of the cylinder
// a2 similarly on the bottom disk

$minimum_path = 10;

for($a1=0;$a1<=180;$a1+=1) {
    for($a2=0;$a2<=180;$a2+=1) {
        // the 2 angles have a sum of at most 180
        if(180 < $a1 + $a2) {
            break;
        }
        // length of chord on top disk
        $ch1 = $r2 * 2 * sin(deg2rad($a1 / 2));
        // length of chord on bottom disk
        $ch2 = $r2 * 2 * sin(deg2rad($a2 / 2));
        // length of path on curved part of cylinder
        $a3 = 180 - $a1 - $a2; // angle
        // corner to corner on rectangle
        $w = $r2 * deg2rad($a3);
        $ch3 = pow($w * $w + $h * $h, 0.5);
        $ch = $ch1 + $ch2 + $ch3;
        //printf("Angle 1: %3d. Angle 2: %3d. Path: %8.5f\n", $a1, $a2, $ch);
        if($ch < $minimum_path) {
            $minimum_path = $ch;
        }
        $paths[$a1][$a2] = $ch;
    }
}

printf("Minimum path 1: %8.5f\n", $minimum_path);

//print_python($paths);

///////////////////////////////////////////////////////////////////////////

$minimum_path = 10;
$paths = [];

for($a1=0;$a1<=90;$a1+=0.1) {
    for($a2=0;$a2<=90;$a2+=0.1) {
        // the 2 angles have a sum of at most 180
        if(180 < $a1 + $a2) {
            break;
        }
        // length of path on top disk
        $x = 2 - cos(deg2rad($a1));
        $y = sin(deg2rad($a1));
        $ch1 = pow($x * $x + $y * $y, 0.5);
        // length of path on bottom disk
        $x = 2 - cos(deg2rad($a2));
        $y = sin(deg2rad($a2));
        $ch2 = pow($x * $x + $y * $y, 0.5);
        // length of path on curved part of cylinder
        $a3 = 180 - $a1 - $a2; // angle
        // corner to corner on rectangle
        $w = $r1 * deg2rad($a3);
        $ch3 = pow($w * $w + $h * $h, 0.5);
        $ch = $ch1 + $ch2 + $ch3;
        if($ch < $minimum_path) {
            $minimum_path = $ch;
        }
        $paths[$a1][$a2] = $ch;
    }
}

printf("Minimum path 2: %8.5f\n", $minimum_path);

//print_python($paths);

?>
