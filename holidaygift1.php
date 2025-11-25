<?php

///////////////////////////////////////////////////////////////////////////
// constants

$loops = 1000000;

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

// part 1: 5 persons
// part 2: N persons

printf("All loops       %20d\n\n", $loops);

for($persons=5;$persons<=50;$persons+=5) {
	$good_loops = 0;
	$great_loops = 0;
	for($i=0;$i<$loops;$i++) {
		// construct array with numbers, 1 for each person
		for($j=0;$j<$persons;$j++) {
			$hat[$j] = $j;
		}
		// construct array with the numbers drawn
		$drawn = array();
		$hat_sz = sizeof($hat);
		
		// draw numbers
		for($j=0;$j<$persons;$j++) {
			$new_no = rand(0, $persons - 1 - $j);
			if($hat[$new_no] == $j) {
				continue 2;
			}
			$drawn[$j] = $hat[$new_no];
			unset($hat[$new_no]);
			sort($hat);
		}
		
		// the draw was succesful
		$good_loops++;
		
		// but did it have 1 cycle?
		// the first person drawing a number was no. 0
		// that person drew $drawn[0]
		$next_person = 0;
		$good = 1;
		for($j=0;$j<$persons;$j++) {
			$next_person = $drawn[$next_person];
			if($next_person == 0 && $j < $persons - 1) {
				$good = 0;
				break;
			}
		}
		if($good == 1) {
			$great_loops++;
		}
	}

	printf("Persons         %5d               Good loops  %20d\n",
		$persons, $good_loops);
	printf("Probability     %11.5f         Great loops %20d\n",
		$great_loops/$good_loops, $great_loops);
	$prob[] = array($persons, $great_loops/$good_loops);
}
print("\n");

foreach($prob as $p) {
	printf("$p[0],$p[1]\n");
}

?>
