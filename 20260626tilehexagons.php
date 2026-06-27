<?php

///////////////////////////////////////////////////////////////////////////
// constants

// 4 - left = right
// 4 - up = down
// etc.
$LEFT = 0;
$RIGHT = 4;
$UP = 1;
$DOWN = 3;

///////////////////////////////////////////////////////////////////////////
// functions

// https://stackoverflow.com/questions/34034730/how-to-enable-color-for-php-cli

// example
// output red text
// php -r 'echo "\033[31m some colored text \033[0m some white text \n";'
//Examples:
//formatPrint(['blue', 'bold', 'italic','strikethrough'], "Wohoo");

function formatPrint(array $format=[],string $text = '') {
	$codes=[
		'bold'=>1,
		'italic'=>3, 'underline'=>4, 'strikethrough'=>9,
		'black'=>30,   'red'=>31,   'green'=>32,   'yellow'=>33,   
		'blue'=>34,   'magenta'=>35,   'cyan'=>36,   'white'=>37,
		'blackbg'=>40, 'redbg'=>41, 'greenbg'=>42, 'yellowbg'=>43, 
		'bluebg'=>44, 'magentabg'=>45, 'cyanbg'=>46, 'lightgreybg'=>47
	];
	$formatMap = array_map(function ($v) use ($codes) {
		return $codes[$v]; 
		}, $format);
	return "\e[".implode(';',$formatMap).'m'.$text."\e[0m";
}

function print_map($map, $n, $colors_print) {
	for($i=0;$i<9*(4*$n-1)-1;$i++) {
		echo "=";
	}
	echo "\n";
	$tmp = [];
	for($i=1;$i<=2*$n;$i++) {
		for($j=1;$j<=4*$n-1;$j++) {
			$this_id = $i * 100 + $j;
			if(array_sum($map[$this_id]) != 0) {
				if(($i + $j + $n) % 2 == 1) {
					$tmp[3*$i  ][$j] = sprintf("   / \\   ");
					$tmp[3*$i+1][$j] = sprintf("  /%3d\\  ", $this_id);
					$tmp[3*$i+2][$j] = sprintf(" /     \\ ");
				} else {
					$tmp[3*$i  ][$j] = sprintf(" \     / ");
					$tmp[3*$i+1][$j] = sprintf("  \%3d/  ", $this_id);
					$tmp[3*$i+2][$j] = sprintf("   \ /   ");
				}
			} else {
				$tmp[3*$i  ][$j] = " ....... ";
				$tmp[3*$i+1][$j] = " ....... ";
				$tmp[3*$i+2][$j] = " ....... ";
			}
		}
	}
	if($colors_print) {
		$colors = ["redbg", "greenbg", "yellowbg", "bluebg",
			"magentabg", "cyanbg", "red", "green",
			"yellow", "magenta", "cyan", "blue"];
		$color_id = 0;
		for($i=1;$i<=2*$n;$i++) {
			for($j=1;$j<=4*$n-1;$j++) {
				$this_id = $i * 100 + $j;
				$friend_id = array_sum($map[$this_id]);
				if($friend_id == 0) {
					continue;
				}
				if($this_id < $friend_id) {
					$friend_j = $friend_id % 100;
					$friend_i = ($friend_id - $friend_j) / 100;
					$this_color = [$colors[$color_id]];
					$tmp[3*$i  ][$j] = formatPrint($this_color, $tmp[3*$i  ][$j]);
					$tmp[3*$i+1][$j] = formatPrint($this_color, $tmp[3*$i+1][$j]);
					$tmp[3*$i+2][$j] = formatPrint($this_color, $tmp[3*$i+2][$j]);
					$tmp[3*$friend_i  ][$friend_j] =
						formatPrint($this_color, $tmp[3*$friend_i  ][$friend_j]);
					$tmp[3*$friend_i+1][$friend_j] =
						formatPrint($this_color, $tmp[3*$friend_i+1][$friend_j]);
					$tmp[3*$friend_i+2][$friend_j] =
						formatPrint($this_color, $tmp[3*$friend_i+2][$friend_j]);
					$color_id = ($color_id + 1) % sizeof($colors);
				}
			}
		}
	}
	for($i=3;$i<=3*2*$n+2;$i++) {
		for($j=1;$j<=4*$n-1;$j++) {
			print($tmp[$i][$j]);
		}
		print("\n");
	}
	for($i=0;$i<9*(4*$n-1)-1;$i++) {
		echo "=";
	}
	echo "\n";
}

// height of hexagon 2n, in this case n=2, 2n lines of triangles
//  ^v^v^
// ^v^v^v^
// v^v^v^v
//  v^v^v
function create_hexagon($n) {
	global $LEFT, $RIGHT, $UP, $DOWN;
	
	// each triangle has an id
	// each triangle has 1-3 neighbors
	$triangles = [];
	
	// each line has a number of triangles: 2n+1, 2n+3, ... 4n-1
	// initiate 2d array of triangles
	for($i=1;$i<=2*$n;$i++) {
		for($j=1;$j<=4*$n-1;$j++) {
			$this_id = $i * 100 + $j;
			$triangles[$this_id] = [0,0,0,0,0];
		}
	}
	
	// a triangle that exists also has neighbors
	// in the 1st line, existing triangles, 2n+1 of them, lie in the middle
	// of the 4n-1 choices
	for($i=1;$i<=2*$n;$i++) {
		$ii = min($i, 2*$n-$i+1);
		for($j=$n-$ii+1;$j<=4*$n-1-$n+$ii;$j++) {
			$this_id = $i * 100 + $j;
			
			// odd triangles have neighbors below
			// even have above
			if(($i + $j + $n) % 2 == 1 && $i < 2 * $n) {
				$new_id = ($i + 1) * 100 + $j;
				$triangles[$this_id][$DOWN] = $new_id;
			}
			if(($i + $j + $n) % 2 == 0 && 1 < $i) {
				$new_id = ($i - 1) * 100 + $j;
				$triangles[$this_id][$UP] = $new_id;
			}
			
			if($n - $ii + 1 < $j) {
				// neighbor to the left
				$new_id = $i * 100 + $j - 1;
				$triangles[$this_id][$LEFT] = $new_id;
			}

			if($j < 4 * $n - 1 - $n + $ii) {
				// neighbor to the right
				$new_id = $i * 100 + $j + 1;
				$triangles[$this_id][$RIGHT] = $new_id;
			}
		}
	}
	
	return $triangles;
}

function disconnect_neighbors($triangles, $id1, $id2, $dir) {
	global $LEFT, $RIGHT, $UP, $DOWN;

	// the id1 triangle has the id2 triangle on the dir
	// e.g. 102 has 103 on the RIGHT
	foreach($triangles[$id1] as $this_dir => $this_id) {
		if($this_dir == $dir) {
			continue;
		}
		if($this_id != 0) {
			$triangles[$this_id][4 - $this_dir] = 0;
		}
	}

	foreach($triangles[$id2] as $this_dir => $this_id) {
		if($this_dir == 4 - $dir) {
			continue;
		}
		if($this_id != 0) {
			$triangles[$this_id][4 - $this_dir] = 0;
		}
	}
	
	return $triangles;
}

function nonzero($a) {
	return $a != 0;
}

function tri_string($triangles) {
	$tri_string = "";
	foreach($triangles as $triangle_id => $friends) {
		// first check whether this triangle has more than 1 friend
		$friends_real = array_filter($friends, "nonzero");
		if(sizeof($friends_real) != 1) {
			continue;
		}
		
		$friend_id = array_sum($friends);
		// then check whether the friend has more than 1 friend
		$friends_real = array_filter($triangles[$friend_id], "nonzero");
		if(sizeof($friends_real) != 1) {
			continue;
		}
		
		if($triangle_id < $friend_id) {
			$tri_string .= "($triangle_id,$friend_id) ";
		}
	}
	return $tri_string;
}

function create_all_permutations($triangles, $ids) {
	global $LEFT, $RIGHT, $UP, $DOWN;
	global $all_permutations;
	global $been_here_before;
	global $all_triangle_ids;
	
	$string1 = tri_string($triangles);
	$string2 = implode(",", $ids);
	// memoization
	if(isset($been_here_before[$string1][$string2])) {
		return;
	}

	if(sizeof($ids) == 0) {
		// permutation found
		$all_permutations[$string1] = $triangles;
		return;
	}
	
	foreach($ids as $id) {
		// progress
		if($all_triangle_ids == sizeof($ids)) {
			printf("%3s %3s %3s %s\n", $id, "", "", date("h:i:sa"));
		}

		$friends_sum = array_sum($triangles[$id]);
		if($friends_sum == 0) {
			// there's a triangle with 0 friends
			// it is invisible
			// it will never be removed from the list of ids
			continue;
		}
		
		$dirs = [$LEFT, $UP, $DOWN, $RIGHT];
		
		foreach($dirs as $dir) {
			if($triangles[$id][$dir] != 0) {
				$this_triangles = $triangles;
				// create a rhombus with these 2 triangles and recurse
				$friend_id = $this_triangles[$id][$dir];
				
				if($friend_id < $id) {
					// skip half of the choices, they are duplicates
					continue;
				}
				
				$this_triangles = disconnect_neighbors($this_triangles,
					$id, $friend_id, $dir);
				
				$this_triangles[$id] = [0, 0, 0, 0, 0];
				$this_triangles[$id][$dir] = $friend_id;
				$this_triangles[$friend_id] = [0, 0, 0, 0, 0];
				$this_triangles[$friend_id][4-$dir] = $id;
				
				$this_ids = $ids;
				unset($this_ids[$id]);
				unset($this_ids[$friend_id]);
				create_all_permutations($this_triangles, $this_ids);
			}
		}
	}
	
	$been_here_before[$string1][$string2] = true;
}

///////////////////////////////////////////////////////////////////////////
// main program

$n = 2;		// more than 2: will take forever to run

$triangles = create_hexagon($n);

system('clear');
print_map($triangles, $n, false);
print("\n");

foreach($triangles as $id => $friends) {
	$friends_sum = array_sum($friends);
	if($friends_sum != 0) {
		$triangle_ids[$id] = $id;
	}
}

$all_triangle_ids = sizeof($triangle_ids);

$all_permutations = [];
$been_here_before = [];		// memoization

create_all_permutations($triangles, $triangle_ids);

usleep(3000000);
//system('clear');

$i = 1;
$all_permutations_sz = sizeof($all_permutations);

foreach($all_permutations as $perm_id => $perm) {
	print_map($perm, $n, true);
	printf("Tiling %3d of %3d\n\n", $i, $all_permutations_sz);
	print("$perm_id\n\n");
	$i++;
	// sleep a little, microseconds
	usleep(3000000);
	// clear screen
	// system('clear');
}

?>
