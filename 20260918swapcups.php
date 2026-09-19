<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 1000000;

///////////////////////////////////////////////////////////////////////////
// functions

function swap_random_cups($order) {
	$order_arr = str_split($order);
	$s = rand(1, 3);
	switch($s) {
		case 1:
			$swap1 = 0;
			$swap2 = 1;
			break;
		case 2:
			$swap1 = 0;
			$swap2 = 2;
			break;
		case 3:
			$swap1 = 1;
			$swap2 = 2;
			break;
	}
	$tmp = $order_arr[$swap1];
	$order_arr[$swap1] = $order_arr[$swap2];
	$order_arr[$swap2] = $tmp;
	$order = implode("", $order_arr);
	return $order;
}

///////////////////////////////////////////////////////////////////////////
// main program

// init
$org_order = "abc";
$all_orders_used_count = [];
$back_to_original_order_count = [];

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
print("\n");		// progress

$back_to_original_order_average = 0;
foreach($back_to_original_order_count as $b => $c) {
	$back_to_original_order_average += $b * $c / $loops;
}

$all_orders_used_average = 0;
foreach($all_orders_used_count as $a => $c) {
	$all_orders_used_average += $a * $c / $loops;
}

printf("Result 1: %8.5f\n", $back_to_original_order_average);
printf("Result 2: %8.5f\n", $all_orders_used_average);

?>
