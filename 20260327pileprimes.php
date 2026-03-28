<?php

///////////////////////////////////////////////////////////////////////////
// constants

// the highest possible prime
$max_prime = 1000;

$fiddler = true;

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
    for ($i = 2; $i <= $N; $i++) {
        if (isPrime($i)) {
            $all_primes[] = $i;
        }
    }
    return $all_primes;
}

function test_groupings($rest_of_primes, $group_no, $groups, $group_sums) {
    if(sizeof($rest_of_primes) == 0) {
        // all primes used, test the sums
        $group_sum = array_sum($groups[0]);
        for($i=1;$i<$group_no;$i++) {
            if(array_sum($groups[$i]) != $group_sum) {
                return false;
            }
        }
        return $groups;
    } else {
        $next_prime = array_shift($rest_of_primes);
        for($i=0;$i<$group_no;$i++) {
            // try adding this next prime to each group
            $groups_copy = $groups;
            $group_sums_copy = $group_sums;
            $groups_copy[$i][] = $next_prime;
            $group_sums_copy[$i] -= $next_prime;
            if($group_sums_copy[$i] < 0) {
                // reject if the sum of this group has become too big
                continue;
            }
            $result = test_groupings($rest_of_primes, $group_no,
                $groups_copy, $group_sums_copy);
            if($result !== false) {
                // first solution found will bubble all the way up
                return $result;
            }
        }
        return false;
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

$all_primes = findPrimes($max_prime);
$no_of_primes = sizeof($all_primes);

if($fiddler) {
    // looking for N3
    $group_max = 3;
    $buffer = "................. ";
} else {
    // looking for N6
    $group_max = 7;
    $buffer = "";
}

// iterate over number of groups
for($group_no=1;$group_no<=$group_max;$group_no++) {
    for($i=$group_no;$i<=$no_of_primes;$i++) {
        // has to use at least 1 prime in each group
        if($fiddler) {
            printf("Testing N%d = %3d. ", $group_no, $i);
        }
        // test with the first i primes
        // reverse in order to fit the largest primes into groups first
        $first_i_primes = array_reverse(array_slice($all_primes, 0, $i));
        $sum_first_i_primes = array_sum($first_i_primes);
        // sum must be multiple of groups
        if($sum_first_i_primes % $group_no != 0) {
            if($fiddler) {
                printf("Sum of primes %4d isn't a multiple of %d, rejected.\n",
                    $sum_first_i_primes, $group_no);
            }
            continue;
        }
        
        if($fiddler) {
            printf("Testing these primes: %s\n",
                implode(",", $first_i_primes));
        }
        if(25 < $i) {
            // too many primes to test
            if(!$fiddler) {
                printf("Testing N%d = %3d.\n", $group_no, $i);
                printf("Testing these primes: %s\n",
                    implode(",", $first_i_primes));
            }
            print("${buffer}Or rather, test left as an exercise for the reader.\n\n");
            continue 2;
        }
        
        // test all possible groupings
        $groups = array_fill(0, $group_no, []);
        // the group sum will actually go down from max to 0
        $group_sums = array_fill(0, $group_no,
            $sum_first_i_primes / $group_no);
        // recursion, brute force, test all combinations
        $result = test_groupings($first_i_primes, $group_no, $groups,
            $group_sums);
        if($result !== false) {
            // solution found
            foreach($result as $j => $g) {
                printf("%sGroup %d: %s\n", $buffer, $j + 1,
                    implode(",", $g));
            }
            printf("%sSum of groups: %d\n", $buffer,
                array_sum($result[0]));
            printf("%sN%d = %d\n\n", $buffer, $group_no, $i);
            break;
        } else {
            // this i didn't work
            if($fiddler) {
                printf("%sThis N%d rejected.\n", $buffer, $group_no);
            }
        }
    }
}

?>
