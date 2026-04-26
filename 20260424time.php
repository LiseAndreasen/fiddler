<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

// the square error function f(A, B) = (A − 90)^2 + (B − 90)^2
function f($A, $B) {
    return pow($A - 90, 2) + pow($B - 90, 2);
}

// convert from s to HH:MM:SS and unrounded versions
function convert_time($s) {
    // hours, not rounded
    $h = $s / 3600;
    
    // where is the hand? measured in minute markers
    $hh = 5 * $h;
    $mh = fmod($s / 60, 60);
    $sh = fmod($s, 60);
    
    // hours, minutes, seconds, rounded
    $hr = floor($h);
    $mr = floor($mh) % 60;
    $sr = floor($sh);
    
    return [$hh, $mh, $sh, $hr, $mr, $sr];
}

// calculate angles between hands
function analyze_time($s0, $part) {
    [$hh, $mh, $sh, $hr, $mr, $sr] = convert_time($s0);
    
    if($sr == 0 && $hh == $mr + 13 && $part == 1) {
        printf("Fiddler: %2d:%2d:%2d -- hour hand at: %7.4f\n", $hr, $mr, $sh, $hh);
    }
    
    // where is the hand, in degrees
    $hd = $hh * 6;
    $md = $mh * 6;
    $sd = $sh * 6;
    
    // angle between hands
    $a = [];
    $a[] = min(abs($hd - $md), 360 - abs($hd - $md));
    $a[] = min(abs($hd - $sd), 360 - abs($hd - $sd));
    $a[] = min(abs($md - $sd), 360 - abs($md - $sd));
    sort($a);
    
    return $a;
}

// look for 90 degree angles
function look_for_90d_angles($s0s, $i, $part, $threshold) {
    
    // the next options to look through
    $s0s_next = [];
    
    foreach($s0s as $s0_mid) {
        // if interval is 0, only do once
        for($s0=$s0_mid-$i;$s0<=$s0_mid+$i;$s0+=max($i,0.0000001)/10) {
            $a = analyze_time($s0, $part);
            // look for a small error function
            $f = f($a[0], $a[1]);
            if($part == 2 && $f < $threshold) {
                printf("Good time, in seconds: %11.5f -- ", $s0);
                [$hh, $mh, $sh, $hr, $mr, $sr] = convert_time($s0);
                printf("Good time............: %2d:%2d:%2d -- ", $hr, $mr, $sr);
                printf("Error function: %10.7f\n", $f);
                $s0s_next[] = $s0;
            }
        }
    }
    print("\n");
    return $s0s_next;
}

///////////////////////////////////////////////////////////////////////////
// main program

// s0: time in seconds, from 00:00:00 to 11:59:59

$part = 1;

$s_max = 12 * 60 * 60;
$s0a = range(0, $s_max - 1);
$interval = 0;  // dummy
$threshold = 0;
look_for_90d_angles($s0a, $interval, $part, $threshold);

$part = 2;

$threshold = 8; // guess the threshold
printf("Looking for 90deg angles, solutions %8.5f apart, error function < %8.5f\n\n", 1, $threshold);
$s0b = look_for_90d_angles($s0a, $interval, $part, $threshold);
sleep(5);

$interval = 0.5; // seconds, back and forward
$threshold = 0.5; // guess the threshold
printf("Looking for 90deg angles, solutions %8.5f apart, error function < %8.5f\n\n", $interval / 10, $threshold);
$s0c = look_for_90d_angles($s0b, $interval, $part, $threshold);
sleep(5);

$interval = 0.025; // seconds, back and forward
$threshold = 0.015; // guess the threshold
printf("Looking for 90deg angles, solutions %8.5f apart, error function < %8.5f\n\n", $interval / 10, $threshold);
$s0d = look_for_90d_angles($s0c, $interval, $part, $threshold);
sleep(5);

$interval = 0.002; // seconds, back and forward - there migth be repeat solutions now
$threshold = 0.00796; // guess the threshold
printf("Looking for 90deg angles, solutions %8.5f apart, error function < %8.5f\n\n", $interval / 10, $threshold);
look_for_90d_angles($s0d, $interval, $part, $threshold);
sleep(5);

// doing the rest more by hand
$interval = 0.0005; // seconds, back and forward
$threshold = 0.007957; // guess the threshold
printf("Looking for 90deg angles, solutions %8.5f apart, error function < %8.5f\n\n", $interval / 10, $threshold);
$s0f = range(13744.078, 13744.079, 0.001);
look_for_90d_angles($s0f, $interval, $part, $threshold);
$s0g = range(29455.921, 29455.922, 0.001);
look_for_90d_angles($s0g, $interval, $part, $threshold);

?>
