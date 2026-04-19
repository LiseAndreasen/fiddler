<?php

///////////////////////////////////////////////////////////////////////////
// constants

$desmos_ready = false;

///////////////////////////////////////////////////////////////////////////
// functions

// note: N_max should be at least 7
// so that a search going downwards through the values will find something
function buzz_frequency($N_max) {
    global $all_the_buzzes;
    
    $N = $N_max;
    $N_key = array_search($N, $all_the_buzzes);
    while($N_key === false) {
        $N--;
        $N_key = array_search($N, $all_the_buzzes);
    }
    return [$N_key + 1, ($N_key + 1) / $N_max];
}

///////////////////////////////////////////////////////////////////////////
// main program

print("Building list of buzzes.\n");

$all_the_buzzes = [];

for ($i = 0; $i < 10; $i++) {
for ($j = 0; $j < 10; $j++) {
for ($k = 0; $k < 10; $k++) {
for ($l = 0; $l < 10; $l++) {
for ($m = 0; $m < 10; $m++) {
for ($n = 0; $n < 10; $n++) {
for ($o = 0; $o < 10; $o++) {
    // current number: ponmlkji
    $no = $i + 10 * $j + 100 * $k + 1000 * $l + 10000 * $m
        + 100000 * $n + 1000000 * $o;
    if($no == 0) {
        // we begin at 1
        continue;
    }
    
    // if 1 of the digits is 7
    if($i == 7 || $j == 7 || $k == 7 || $l == 7 || $m == 7
    || $n == 7 || $o == 7) {
        $all_the_buzzes[$no] = $no;
    }
    
    // if number is a multiple of 7
    if($no % 7 == 0) {
        $all_the_buzzes[$no] = $no;
    }
}
}
}
}
}
}
}
// final no + 1 is automatically included, because
// none of the digits are 7
// it is not a multiple of 7

sort($all_the_buzzes);

// count all buzzes up to 20
[$count, $freq] = buzz_frequency(20);
printf("Test........: %2d\n", $count);
printf("Test........:  %f\n", $freq);

// count all buzzes up to 100
[$count, $freq] = buzz_frequency(100);
printf("Fiddler.....: %2d\n", $count);

print("\n");

// examination of N in more and more detail

// jump of factor 1.25
for($i=1;$i<=7;$i+=0.1) {
    $N = (int) pow(10, $i);
    $all_the_N[$N] = $N;
}

// jump of factor 1.023
for($i=5.83;$i<=5.95;$i+=0.01) {
    $N = (int) pow(10, $i);
    $all_the_N[$N] = $N;
}

for($N=630000;$N<=724500;$N+=1000) {
    $all_the_N[$N] = $N;
}

for($N=708000;$N<=709000;$N+=100) {
    $all_the_N[$N] = $N;
}

for($N=708580;$N<=708600;$N+=1) {
    $all_the_N[$N] = $N;
}

sort($all_the_N);

// count buzzes by adding 1 to the key of the highest number lower than N
foreach ($all_the_N as $N_max) {
    [$count, $freq] = buzz_frequency($N_max);
    if($desmos_ready) {
        printf("%d\t%10.7f\n", $N_max, $freq);
    } else {
        if(0.5 <= $freq) {
            $hooray = " ***";
        } else {
            $hooray = "";
        }
        printf("With N = %8d the frequency is %10.7f%s\n", $N_max, $freq, $hooray);
    }
}

?>
