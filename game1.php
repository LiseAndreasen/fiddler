<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

function create_empty_map() {
	$map = array();
	$map[-5] = "\n";
	$good_state = formatPrint(['cyanbg'], "(1,0)");
	$bad_state = formatPrint(['magentabg'], "(0,1)");
	$map[-4] = "                    $good_state    $bad_state\n";
	$wg_state = formatPrint(['greenbg'], sprintf('  %2s ', 0));
	$wb_state = formatPrint(['greenbg'], sprintf('  %2s ', 0));
	$lg_state = formatPrint(['redbg'], sprintf('  %2s ', 0));
	$lb_state = formatPrint(['redbg'], sprintf('  %2s ', 0));
	$map[-3] = "  Wins............: $wg_state    $wb_state\n";
	$map[-2] = "  Losses..........: $lg_state    $lb_state\n";
	$map[-1] = "\n";
	for($i=0;$i<=7;$i++) {
		$map[2*$i] = "  ";
		for($j=0;$j<=7-$i;$j++) {
			$map[2*$i] .= "($j,$i) ";
			if($j<7-$i) {
				$map[2*$i] .= "-- ";
			}
		}
		$map[2*$i] .= "\n";

		$map[2*$i+1] = "  ";
		for($k=0;$k<$j-1;$k++) {
			$map[2*$i+1] .= "  |      ";
		}
		$map[2*$i+1] .= "\n";
	}
	return $map;
}

// https://stackoverflow.com/questions/34034730/how-to-enable-color-for-php-cli

// example
// output red text
// php -r 'echo "\033[31m some colored text \033[0m some white text \n";'

//Examples:
//formatPrint(['blue', 'bold', 'italic','strikethrough'], "Wohoo");
//formatPrintLn(['yellow', 'italic'], " I'm invicible");
//formatPrintLn(['yellow', 'bold'], "I'm invicible");

function formatPrint(array $format=[],string $text = '') {
  $codes=[
    'bold'=>1,
    'italic'=>3, 'underline'=>4, 'strikethrough'=>9,
    'black'=>30,   'red'=>31,   'green'=>32,   'yellow'=>33,   'blue'=>34,   'magenta'=>35,   'cyan'=>36,   'white'=>37,
    'blackbg'=>40, 'redbg'=>41, 'greenbg'=>42, 'yellowbg'=>43, 'bluebg'=>44, 'magentabg'=>45, 'cyanbg'=>46, 'lightgreybg'=>47
  ];
  $formatMap = array_map(function ($v) use ($codes) {
    return $codes[$v]; 
  }, $format);
  return "\e[".implode(';',$formatMap).'m'.$text."\e[0m";
}

// https://stackoverflow.com/questions/15737408/php-find-all-occurrences-of-a-substring-in-a-string
function strpos_no($haystack, $needle, $no) {
	$pos_no = 0;
    $offset = 0;
    while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
    	if($pos_no == $no) {
    		return $pos;
    	}
    	$pos_no++;
        $offset   = $pos + 1;
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

$org_map = create_empty_map();

// beginning screen
system('clear');
print("                                                                        *\n");
print("  Simulation of all possible series.\n  Game 1 fiddler.\n");
print("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");
usleep(1000000); // microseconds
for($i=0;$i<=5;$i++) {
	print("*");
	usleep(1000000); // microseconds
}

$win_good = 0;
$win_bad = 0;
$lose_good = 0;
$lose_bad = 0;

// map before series
system('clear');
foreach($org_map as $line) {
	print($line);
}
usleep(3000000); // microseconds

for($k=0;$k<128;$k++) {
	$good_bad = 0;
	$win_lose = 0;
	$bin_no = decbin($k);
	$bin_pad = sprintf('%7d', $bin_no);
	$bin_arr  = array_map('intval', str_split($bin_pad));

	$map = $org_map;
	$i=0;
	$j=0;
	
	$color = array('bluebg');
	
	$state_color = formatPrint($color, "(0,0)");
	$map[0] = str_replace("(0,0)", $state_color, $map[0]);

	foreach($bin_arr as $bit) {
		if($bit == "0") {
			// go right
			$line_pos = strpos_no($map[2*$i], " -- ", $j);
			$map[2*$i] = substr($map[2*$i], 0, $line_pos) . formatPrint($color, " -- ") . substr($map[2*$i], $line_pos + 4);
			$j++;
		} else {
			// go down
			$line_pos = strpos_no($map[2*$i+1], "  |  ", $j);
			$map[2*$i+1] = substr($map[2*$i+1], 0, $line_pos) . formatPrint($color, "  |  ") . substr($map[2*$i+1], $line_pos + 5);
			$i++;
		}
		// if we went through (1,0), the good strategy
		if($i==0 && $j==1) {
			$color = array('cyanbg');
			$good_bad = 1;
		}
		// if we went through (0,1), the bad strategy
		if($i==1 && $j==0) {
			$color = array('magentabg');
			$good_bad = 2;
		}
		// if we won
		if($j == 4) {
			$color = array('greenbg');
			$win_lose = 1;
		}
		// if we lost
		if($i == 4) {
			$color = array('redbg');
			$win_lose = 2;
		}
		$state = "($j,$i)";
		$state_pos = strpos($map[2*$i], $state);
		$map[2*$i] = substr($map[2*$i], 0, $state_pos) . formatPrint($color, $state) . substr($map[2*$i], $state_pos + 5);
	}
	if($good_bad == 1) {
		if($win_lose == 1) {
			$win_good++;
		} else {
			$lose_good++;
		}
	} else {
		if($win_lose == 1) {
			$win_bad++;
		} else {
			$lose_bad++;
		}
	}
	$wg_state = formatPrint(['greenbg'], sprintf('  %2s ', $win_good));
	$wb_state = formatPrint(['greenbg'], sprintf('  %2s ', $win_bad));
	$lg_state = formatPrint(['redbg'], sprintf('  %2s ', $lose_good));
	$lb_state = formatPrint(['redbg'], sprintf('  %2s ', $lose_bad));
	$map[-3] = "  Wins............: $wg_state    $wb_state\n";
	$map[-2] = "  Losses..........: $lg_state    $lb_state\n";
	system('clear');
	foreach($map as $line) {
		print($line);
	}
	usleep(400000); // microseconds
}
print("\n\n");

?>
