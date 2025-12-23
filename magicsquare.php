<?php

///////////////////////////////////////////////////////////////////////////
// constants

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



///////////////////////////////////////////////////////////////////////////
// main program

// Driver code
$N = $magic_constant; // slightly too big

echo "The time is " . date("h:i:sa" . "\n");

$all_primes = [];
findPrimes($N);
print("\n");
printf("%d primes <= 2026 found\n", sizeof($all_primes));

echo "The time is " . date("h:i:sa" . "\n");

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

// https://entertainmentmathematics.nl/Entertainment/Descriptions/Excel/MagicSquares/Magic_Squares1.html#2.1
// The solutions are obtained by guessing a(12), a(14), a(15) and a(16)
// and filling out these guesses in the abovementioned equations...

$s1 = $magic_constant;

$all_primes_avail = $all_primes;

foreach($all_primes_avail as $a12) {
print(".");
	unset($all_primes_avail[$a12]);
	foreach($all_primes_avail as $a14) {
		unset($all_primes_avail[$a14]);
		foreach($all_primes_avail as $a15) {
			unset($all_primes_avail[$a15]);
			foreach($all_primes_avail as $a16) {
				unset($all_primes_avail[$a16]);				
				$a13 = $s1 - $a14 - $a15 - $a16;
				if(isset($all_primes_avail[$a13])) {
					unset($all_primes_avail[$a13]);
					$a11 = $s1 - $a12 - $a15 - $a16;
					if(isset($all_primes_avail[$a11])) {
						unset($all_primes_avail[$a11]);
						$a10 = $a12 - $a14 + $a16;
						if(isset($all_primes_avail[$a10])) {
							unset($all_primes_avail[$a10]);
							$a9 = - $a12 + $a14 + $a15;
							if(isset($all_primes_avail[$a9])) {
								unset($all_primes_avail[$a9]);
								$a8 = $s1 / 2 - $a14;
								if(isset($all_primes_avail[$a8])) {
									unset($all_primes_avail[$a8]);
									$a7 = - $s1 / 2 + $a14 + $a15 + $a16;
									if(isset($all_primes_avail[$a7])) {
										unset($all_primes_avail[$a7]);
										$a6 = $s1 / 2 - $a16;
										if(isset($all_primes_avail[$a6])) {
											unset($all_primes_avail[$a6]);
											$a5 = $s1 / 2 - $a15;
											if(isset($all_primes_avail[$a5])) {
												unset($all_primes_avail[$a5]);
												$a4 = $s1 / 2 - $a12 + $a14 - $a16;
												if(isset($all_primes_avail[$a4])) {
													unset($all_primes_avail[$a4]);
													$a3 = $s1 / 2 + $a12 - $a14 - $a15;
													if(isset($all_primes_avail[$a3])) {
														unset($all_primes_avail[$a3]);
														$a2 = $s1 / 2 - $a12;
														if(isset($all_primes_avail[$a2])) {
															unset($all_primes_avail[$a2]);
															$a1 = - $s1 / 2 + $a12 + $a15 + $a16;
															if(isset($all_primes_avail[$a1])) {
																print("A solution!\n");
																printf("%4d %4d %4d %4d\n", $a1, $a2, $a3, $a4);
																printf("%4d %4d %4d %4d\n", $a5, $a6, $a7, $a8);
																printf("%4d %4d %4d %4d\n", $a9, $a10, $a11, $a12);
																printf("%4d %4d %4d %4d\n", $a13, $a14, $a15, $a16);
																exit();
															}
															$all_primes_avail[$a2] = $a2;				
														}
														$all_primes_avail[$a3] = $a3;				
													}
													$all_primes_avail[$a4] = $a4;				
												}
												$all_primes_avail[$a5] = $a5;
											}
											$all_primes_avail[$a6] = $a6;				
										}
										$all_primes_avail[$a7] = $a7;				
									}
									$all_primes_avail[$a8] = $a8;				
								}
								$all_primes_avail[$a9] = $a9;				
							}
							$all_primes_avail[$a10] = $a10;				
						}
						$all_primes_avail[$a11] = $a11;				
					}
					$all_primes_avail[$a13] = $a13;				
				}
				$all_primes_avail[$a16] = $a16;
			}
			$all_primes_avail[$a15] = $a15;
		}
		$all_primes_avail[$a14] = $a14;
	}
	$all_primes_avail[$a12] = $a12;
}

echo "The time is " . date("h:i:sa" . "\n");

?>
