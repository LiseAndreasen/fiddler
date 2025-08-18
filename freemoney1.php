<?php

///////////////////////////////////////////////////////////////////////////
// constants

// possible states
// $states = 
//	array("0", "10", "25", "1010", "1025", "101010", "101025", "10101025");

// possible strategies
$strategies["10101025"] = array("1", "2", "3a", "3b", "4a", "4b",
					"5a", "5b", "6a", "6b", "6c", "7a", "7b", "7c", "7d");
$strategies["101025"] = array("1", "2", "3a", "3b", "4a", "4b", 
														"6a", "6b", "6c");
$strategies["101010"] = array("1", "3a", "3b", "5a", "5b");
$strategies["1025"] = array("1", "2", "4a", "4b");
$strategies["1010"] = array("1", "3a", "3b");
$strategies["25"] = array("2");
$strategies["10"] = array("1");
$strategies["0"] = array("end");

// beginning state
$beginning_state = "10101025";

$loops = 1000;

///////////////////////////////////////////////////////////////////////////
// functions

function fifty_fifty() {
	return rand(0, 1);
}

// https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
// my_str_replace
function my_str_replace($needle, $replace, $haystack) {
	$pos = strpos($haystack, $needle);
	if ($pos !== false) {
		$newstring = substr_replace($haystack, $replace, $pos, strlen($needle));
	}
	if(strcmp($newstring, "") == 0) {
		$newstring = "0";
	}
	return $newstring;
}

// possible state changes
// 1.: 10 -------> 10       / +10 or    0 / +00
// 2.: 25 -------> 25       / +25 or    0 / +00
// 3a: 1010 -----> 1010     / +20 or    0 / +00
// 3b: 1010 -----> 10       / +10
// 4a: 1025 -----> 1025     / +35 or    0 / +00
// 4b: 1025 -----> 10       / +10 or   25 / +25
// 5a: 101010 ---> 101010   / +30 or    0 / +00
// 5b: 101010 ---> 1010     / +20 or   10 / +10
// 6a: 101025 ---> 101025   / +45 or    0 / +00
// 6b: 101025 ---> 1010     / +20 or   25 / +25
// 6c: 101025 ---> 10       / +10 or 1025 / +35
// 7a: 10101025 -> 10101025 / +55 or    0 / +00
// 7b: 10101025 -> 101010   / +30 or   25 / +25
// 7c: 10101025 -> 101025   / +45 or   10 / +10
// 7d: 10101025 -> 1010     / +20 or 1025 / +35

function change_state() {
	global $state, $money, $strategy;
	
	$this_strategy = $strategy[$state];
	
	switch($this_strategy) {
		// 1.: 10 -------> 10       / +10 or    0 / +00
		case "1":
			if(fifty_fifty() == 0) {
				$money += 10;
				// state unchanged
			} else {
				// money unchanged
				$state = my_str_replace("10", "", $state);
			}
			break;
		// 2.: 25 -------> 25       / +25 or    0 / +00
		case "2":
			if(fifty_fifty() == 0) {
				$money += 25;
				// state unchanged
			} else {
				// money unchanged
				$state = my_str_replace("25", "", $state);
			}
			break;
		// 3a: 1010 -----> 1010     / +20 or    0 / +00
		case "3a":
			if(fifty_fifty() == 0) {
				$money += 20;
				// state unchanged
			} else {
				// money unchanged
				$state = my_str_replace("1010", "", $state);
			}
			break;
		// 3b: 1010 -----> 10       / +10
		case "3b":
			$money += 10;
			$state = my_str_replace("10", "", $state);
			break;
		// 4a: 1025 -----> 1025     / +35 or    0 / +00
		case "4a":
			if(fifty_fifty() == 0) {
				$money += 35;
				// state unchanged
			} else {
				// money unchanged
				$state = my_str_replace("1025", "", $state);
			}
			break;
		// 4b: 1025 -----> 10       / +10 or   25 / +25
		case "4b":
			if(fifty_fifty() == 0) {
				$money += 10;
				$state = my_str_replace("25", "", $state);
			} else {
				$money += 25;
				$state = my_str_replace("10", "", $state);
			}
			break;
		// 5a: 101010 ---> 101010   / +30 or    0 / +00
		case "5a":
			if(fifty_fifty() == 0) {
				$money += 30;
				// state unchanged
			} else {
				// money unchanged
				$state = my_str_replace("101010", "", $state);
			}
			break;
		// 5b: 101010 ---> 1010     / +20 or   10 / +10
		case "5b":
			if(fifty_fifty() == 0) {
				$money += 20;
				$state = my_str_replace("10", "", $state);
			} else {
				$money += 10;
				$state = my_str_replace("1010", "", $state);
			}
			break;
		// 6a: 101025 ---> 101025   / +45 or    0 / +00
		case "6a":
			if(fifty_fifty() == 0) {
				$money += 45;
				// state unchanged
			} else {
				// money unchanged
				$state = my_str_replace("101025", "", $state);
			}
			break;
		// 6b: 101025 ---> 1010     / +20 or   25 / +25
		case "6b":
			if(fifty_fifty() == 0) {
				$money += 20;
				$state = my_str_replace("25", "", $state);
			} else {
				$money += 25;
				$state = my_str_replace("1010", "", $state);
			}
			break;
		// 6c: 101025 ---> 10       / +10 or 1025 / +35
		case "6c":
			if(fifty_fifty() == 0) {
				$money += 10;
				$state = my_str_replace("1025", "", $state);
				// state unchanged
			} else {
				// money unchanged
				$money += 35;
				$state = my_str_replace("10", "", $state);
			}
			break;
		// 7a: 10101025 -> 10101025 / +55 or    0 / +00
		case "7a":
			if(fifty_fifty() == 0) {
				$money += 55;
				// state unchanged
			} else {
				// money unchanged
				$state = my_str_replace("10101025", "", $state);
			}
			break;
		// 7b: 10101025 -> 101010   / +30 or   25 / +25
		case "7b":
			if(fifty_fifty() == 0) {
				$money += 30;
				$state = my_str_replace("25", "", $state);
			} else {
				$money += 25;
				$state = my_str_replace("101010", "", $state);
			}
			break;
		// 7c: 10101025 -> 101025   / +45 or   10 / +10
		case "7c":
			if(fifty_fifty() == 0) {
				$money += 45;
				$state = my_str_replace("10", "", $state);
			} else {
				$money += 10;
				$state = my_str_replace("101025", "", $state);
			}
			break;
		// 7d: 10101025 -> 1010     / +20 or 1025 / +35
		case "7d":
			if(fifty_fifty() == 0) {
				$money += 20;
				$state = my_str_replace("1025", "", $state);
			} else {
				$money += 35;
				$state = my_str_replace("1010", "", $state);
			}
			break;
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

$max_minimum = 0;
$max_strategy = array();

// yes, this looks horrible
// go through all combinations of strategies
for($key1=0;$key1<sizeof($strategies["10101025"]);$key1++) {
$strategy["10101025"] = $strategies["10101025"][$key1];
for($key2=0;$key2<sizeof($strategies["101025"]);$key2++) {
$strategy["101025"] = $strategies["101025"][$key2];
for($key3=0;$key3<sizeof($strategies["101010"]);$key3++) {
$strategy["101010"] = $strategies["101010"][$key3];
for($key4=0;$key4<sizeof($strategies["1025"]);$key4++) {
$strategy["1025"] = $strategies["1025"][$key4];
for($key5=0;$key5<sizeof($strategies["1010"]);$key5++) {
$strategy["1010"] = $strategies["1010"][$key5];
for($key6=0;$key6<sizeof($strategies["25"]);$key6++) {
$strategy["25"] = $strategies["25"][$key6];
for($key7=0;$key7<sizeof($strategies["10"]);$key7++) {
$strategy["10"] = $strategies["10"][$key7];
for($key8=0;$key8<sizeof($strategies["0"]);$key8++) {
$strategy["0"] = $strategies["0"][$key8];
	
	$buckets = array();
	for($i=0;$i<$loops;$i++) {
		$state = $beginning_state;
		$money = 0;
		// wander the space until we reach an end or at most 10 steps
		for($j=0;$j<10;$j++) {
			if(strcmp($strategy[$state], "end") == 0) {
				break;
			} else {
				change_state($strategy);
			}
		}
		if(isset($buckets[$money])) {
			$buckets[$money]++;
		} else {
			$buckets[$money] = 1;
		}
	}
	$minimum = min(array_keys($buckets));
	if($minimum > $max_minimum) {
		$max_minimum = $minimum;
		$max_strategy = $strategy;
	}
}
}
}
}
}
}
}
}

printf("Minimum: %8d.\n", $max_minimum);
print_r($max_strategy);

?>
