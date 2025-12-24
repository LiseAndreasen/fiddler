<?php

///////////////////////////////////////////////////////////////////////////
// constants

// test case: 240
//	47	7	79	107
//	37	101	31	71
//	73	19	89	59
//	83	113	41	3
// fiddler: 2026
$magic_constant = 2026;

///////////////////////////////////////////////////////////////////////////
// functions

// https://www.geeksforgeeks.org/php/php-program-to-print-prime-number-from-1-to-n/

function isPrime($number) {
    if ($number < 2) {
        return false;
    }

    if ($number == 2 || $number == 3) {
        return true;
    }

    if ($number % 2 == 0 || $number % 3 == 0) {
        return false;
    }

    $i = 5;
    $w = 2;

    while ($i * $i <= $number) {
        if ($number % $i == 0) {
            return false;
        }

        $i += $w;
        $w = 6 - $w;
    }

    return true;
}

function findPrimes($N) {
	global $all_primes;
	// 2 is a prime, but we can't use it
	// because the sum of the 4 distinct primes must be even
    for ($i = 3; $i <= $N; $i++) {
        if (isPrime($i)) {
        	$all_primes[$i] = $i;
        }
    }
}

// https://entertainmentmathematics.nl/Entertainment/Descriptions/Excel/MagicSquares/Magic_Squares1.html#2.1
// The solutions are obtained by guessing a(12), a(14), a(15) and a(16)
// and filling out these guesses in the abovementioned equations...
// https://entertainmentmathematics.nl/wPrimes/Entertainment/Descriptions/Excel/MagicSquares/Magic_Squares35.html#14.2

/*
a( 1) + a( 2) + a( 3) + a( 4) = s1
a( 5) + a( 6) + a( 7) + a( 8) = s1
a( 9) + a(10) + a(11) + a(12) = s1
a(13) + a(14) + a(15) + a(16) = s1

a( 1) + a( 5) + a( 9) + a(13) = s1
a( 2) + a( 6) + a(10) + a(14) = s1
a( 3) + a( 7) + a(11) + a(15) = s1
a( 4) + a( 8) + a(12) + a(16) = s1

a( 1) + a( 6) + a(11) + a(16) = s1

a( 4) + a( 7) + a(10) + a(13) = s1 

a(13) =        s1 - a(14) - a(15) - a(16)
a(11) =        s1 - a(12) - a(15) - a(16)
a(10) =             a(12) - a(14) + a(16)
a(9)  =           - a(12) + a(14) + a(15)
a(8)  =  0.5 * s1 - a(14)
a(7)  = -0.5 * s1 + a(14) + a(15) + a(16)
a(6)  =  0.5 * s1 - a(16)
a(5)  =  0.5 * s1 - a(15)
a(4)  =  0.5 * s1 - a(12) + a(14) - a(16)
a(3)  =  0.5 * s1 + a(12) - a(14) - a(15)
a(2)  =  0.5 * s1 - a(12)
a(1)  = -0.5 * s1 + a(12) + a(15) + a(16) 
*/


function add_prime($all_primes_avail, $pos, $a) {
	global $s1, $already_seen;
	
	// test
	// for a16, a15 already tested or test in progress
	// also exclude mirrors and rotations
	// a13, a14
	// a16, a12
	// a13, a9
	// a4, a8
	// a4, a3
	// a1, a2, moot
	// a1, a5, moot
	
	switch($pos) {
		case 16:
			foreach($all_primes_avail as $a16) {
				print("."); // progress
				$a[16] = $a16;
				unset($all_primes_avail[$a[16]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[16]] = $a[16];		
			}
			break;
		case 15:
			foreach($all_primes_avail as $a15) {
				$a[15] = $a15;
				$already_seen[$a[16]][$a[15]] = 1;
				unset($all_primes_avail[$a[15]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[15]] = $a[15];				
			}
			break;
		case 14:
			foreach($all_primes_avail as $a14) {
				$a[14] = $a14;
				unset($all_primes_avail[$a[14]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[14]] = $a[14];				
			}
			break;
		case 13:
			$a[13] = $s1 - $a[14] - $a[15] - $a[16];
			if(isset($all_primes_avail[$a[13]])) {
				if(isset($already_seen[$a[13]][$a[14]])) {
					break;
				}
				unset($all_primes_avail[$a[13]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[13]] = $a[13];				
			}
			break;
		case 12:
			foreach($all_primes_avail as $a12) {
				$a[12] = $a12;
				if(isset($already_seen[$a[16]][$a[12]])) {
					continue;
				}
				unset($all_primes_avail[$a[12]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[12]] = $a[12];			
			}
			break;
		case 11:
			$a[11] = $s1 - $a[12] - $a[15] - $a[16];
			if(isset($all_primes_avail[$a[11]])) {
				unset($all_primes_avail[$a[11]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[11]] = $a[11];				
			}
			break;
		case 10:
			$a[10] = $a[12] - $a[14] + $a[16];
			if(isset($all_primes_avail[$a[10]])) {
				unset($all_primes_avail[$a[10]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[10]] = $a[10];				
			}
			break;
		case 9:
			$a[9] = - $a[12] + $a[14] + $a[15];
			if(isset($all_primes_avail[$a[9]])) {
				if(isset($already_seen[$a[13]][$a[9]])) {
					break;
				}
				unset($all_primes_avail[$a[9]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[9]] = $a[9];				
			}
			break;
		case 8:
			$a[8] = $s1 / 2 - $a[14];
			if(isset($all_primes_avail[$a[8]])) {
				unset($all_primes_avail[$a[8]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[8]] = $a[8];				
			}
			break;
		case 7:
			$a[7] = - $s1 / 2 + $a[14] + $a[15] + $a[16];
			if(isset($all_primes_avail[$a[7]])) {
				unset($all_primes_avail[$a[7]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[7]] = $a[7];				
			}
			break;
		case 6:
			$a[6] = $s1 / 2 - $a[16];
			if(isset($all_primes_avail[$a[6]])) {
				unset($all_primes_avail[$a[6]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[6]] = $a[6];				
			}
			break;
		case 5:
			$a[5] = $s1 / 2 - $a[15];
			if(isset($all_primes_avail[$a[5]])) {
				unset($all_primes_avail[$a[5]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[5]] = $a[5];				
			}
			break;
		case 4:
			$a[4] = $s1 / 2 - $a[12] + $a[14] - $a[16];
			if(isset($all_primes_avail[$a[4]])) {
				if(isset($already_seen[$a[4]][$a[8]])) {
					break;
				}
				unset($all_primes_avail[$a[4]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[4]] = $a[4];				
			}
			break;
		case 3:
			$a[3] = $s1 / 2 + $a[12] - $a[14] - $a[15];
			if(isset($all_primes_avail[$a[3]])) {
				if(isset($already_seen[$a[4]][$a[3]])) {
					break;
				}
				unset($all_primes_avail[$a[3]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[3]] = $a[3];				
			}
			break;
		case 2:
			$a[2] = $s1 / 2 - $a[12];
			if(isset($all_primes_avail[$a[2]])) {
				unset($all_primes_avail[$a[2]]);
				add_prime($all_primes_avail, $pos - 1, $a);
				$all_primes_avail[$a[2]] = $a[2];				
			}
			break;
		case 1:
			$a[1] = - $s1 / 2 + $a[12] + $a[15] + $a[16];
			if(isset($all_primes_avail[$a[1]])) {
				print("\nA solution!\n");
				printf("%4d %4d %4d %4d\n", $a[1], $a[2], $a[3], $a[4]);
				printf("%4d %4d %4d %4d\n", $a[5], $a[6], $a[7], $a[8]);
				printf("%4d %4d %4d %4d\n", $a[9], $a[10], $a[11], $a[12]);
				printf("%4d %4d %4d %4d\n", $a[13], $a[14], $a[15], $a[16]);
				echo "The time is " . date("h:i:sa" . "\n");
				exit();
			}
			break;
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

// Driver code
$N = $magic_constant; // slightly too big

echo "The time is " . date("h:i:sa" . "\n");

$all_primes = [];
findPrimes($N);
printf("%d primes <= 2026 found\n", sizeof($all_primes));

$s1 = $magic_constant;
$already_seen = [];

add_prime($all_primes, 16, []);

print("\nNo solution found.\n");
echo "The time is " . date("h:i:sa" . "\n");

?>
