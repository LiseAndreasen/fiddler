<?php

///////////////////////////////////////////////////////////////////////////
// constants

$org_order = "abc";

///////////////////////////////////////////////////////////////////////////
// functions

function swap_random_cups($order) {
	$order_arr = str_split($order);
	$s = rand(1, 3);
	switch($s) {
		case 1:
			$swap1 = 0; $swap2 = 1;
			break;
		case 2:
			$swap1 = 0; $swap2 = 2;
			break;
		case 3:
			$swap1 = 1; $swap2 = 2;
			break;
	}
	$tmp = $order_arr[$swap1];
	$order_arr[$swap1] = $order_arr[$swap2];
	$order_arr[$swap2] = $tmp;
	$order = implode("", $order_arr);
	return $order;
}

function monte_carlo($loops) {
	global $org_order;
	
	$all_orders_used_count = [];
	$back_to_original_order_count = [];
	
	print("\n\t");		// progress

	for($l=0;$l<$loops;$l++) {
		// progress
		if($l % 100000 == 0) {
			printf("%.3f ", $l / $loops);
		}
		$swaps = 0;
		$order = $org_order;
		$orders_used = [];
		$orders_used[$order] = true;
		// fiddler: swaps until back to original order
		$back_to_original_order = false;
		// extra credit: swaps until all orders used
		$all_orders_used = false;
		while(!$back_to_original_order || !$all_orders_used) {
			$order = swap_random_cups($order);
			$swaps++;
			
			if(strcmp($order, $org_order) == 0) {
				if(!$back_to_original_order) {
					$back_to_original_order = true;
					if(isset($back_to_original_order_count[$swaps])) {
						$back_to_original_order_count[$swaps]++;
					} else {
						$back_to_original_order_count[$swaps] = 1;
					}
				}
			}

			$orders_used[$order] = true;
			if(sizeof($orders_used) == 6) {
				if(!$all_orders_used) {
					$all_orders_used = true;
					if(isset($all_orders_used_count[$swaps])) {
						$all_orders_used_count[$swaps]++;
					} else {
						$all_orders_used_count[$swaps] = 1;
					}
				}
			}
		}
	}
	print("\n\n");		// progress

	$back_to_original_order_average = 0;
	foreach($back_to_original_order_count as $b => $c) {
		$back_to_original_order_average += $b * $c / $loops;
	}

	$all_orders_used_average = 0;
	foreach($all_orders_used_count as $a => $c) {
		$all_orders_used_average += $a * $c / $loops;
	}
	
	return [$back_to_original_order_average, $all_orders_used_average];
}

// sample variance
function variance($samples) {
	$average = array_sum($samples) / sizeof($samples);
	$variance = 0;
	foreach($samples as $s) {
		$variance += pow($average - $s, 2);
	}
	return $variance / (sizeof($samples) - 1);
}

///////////////////////////////////////////////////////////////////////////
// main program

$deviation_target = 0.0000000001;
$variance_target = pow($deviation_target, 0.5);

// init
$loops = 100;
$var1 = 1;
$var2 = 1;

while($variance_target < $var1 || $variance_target < $var2) {
	$loops *= 10;
	$res1_arr = [];
	$res2_arr = [];
	
	for($i=0;$i<3;$i++) {
		[$res1, $res2] = monte_carlo($loops);
		printf("Result 1................: %12.7f\n", $res1);
		printf("Result 2................: %12.7f\n", $res2);
		$res1_arr[] = $res1;
		$res2_arr[] = $res2;
	}
	
	$var1 = variance($res1_arr);
	$var2 = variance($res2_arr);
	
	printf("\nNumber of loops.........: %12d\n", $loops);
	printf("Variances so far........: %12.7f\n", $var1);
	printf("........................: %12.7f\n", $var2);
}

printf("Final result 1..........:   %10.7f\n........................: - %10.7f\n", min($res1_arr), max($res1_arr));
printf("Final result 2..........:   %10.7f\n........................: - %10.7f\n", min($res2_arr), max($res2_arr));
?>
