<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

function add_month($months, $days) {
    global $days_used, $year_not_right_length;
    
    $days_sum = array_sum($months); // all days so far
    $months_sum = sizeof($months); // so many months so far
    
    if($months_sum == 12) {
        // all 12 months have been assigned
        if($days_sum < 365 || 366 < $days_sum) {
            // this variation didn't work
            $year_not_right_length++;
            return 0;
        }
        $days_used_here = sizeof(array_unique($days));
        $days_used[$days_used_here]++;
        
        if($days_used_here == 3) {
            print(implode(",", $months) . "\n");
        }
        
        return 1;
    }
    
    $next_first_day = $days_sum % 7;
    $days[$months_sum] = $next_first_day;
    for($next_month=28;$next_month<=31;$next_month++) {
        $months[$months_sum] = $next_month;
        add_month($months, $days);
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

$months = []; // the length of each month
$days = []; // the 1st days of each month
// 0 = Monday, 1 = Tuesday, etc.
$days_used = array_fill(1, 7, 0); // tracking how many 1st days appeared
$year_not_right_length = 0;

printf("Nice calendars with very few different 1st days:\n");
add_month($months, $days);

print("\n");
foreach($days_used as $d => $no) {
    printf("%8d calendars with %d different 1st days\n", $no, $d);
}
printf("%8d calendars with a wrong length\n", $year_not_right_length);

$all_years = array_sum($days_used) + $year_not_right_length;
$all_years_expected = pow(4, 12);

printf("%8d calendars in all\n", $all_years);
printf("%8d expected\n", $all_years_expected);

?>
