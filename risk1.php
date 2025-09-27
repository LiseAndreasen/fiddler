<?php

///////////////////////////////////////////////////////////////////////////
// constants

// instead of calling the cards infantry, cavalry and artillery
// simply call them a, b and c

// the cards for the fiddler
$cards = array();
for($i=0;$i<14;$i++) {
	$cards[] = "a";
	$cards[] = "b";
	$cards[] = "c";
}

// number of loops in monte carlo
$loops = 1000000;

///////////////////////////////////////////////////////////////////////////
// functions

function trade_possible($cards) {
	// input: 5 cards drawn in that order
	// output: a trade can be made with the first x cards
	$counting["a"] = 0;
	$counting["b"] = 0;
	$counting["c"] = 0;
	$counting["*"] = 0;
	
	for($i=1;$i<5;$i++) {
	// it's actually not necessary to look at the 5th card
		$letter = $cards[$i-1];
		$counting[$letter]++;
	
		// 3 cards possible if 3 of the same kind, 3 of different kind
		// or wild card(s)
		$max_kind = max($counting);
		if($max_kind == 3) {
			return $i;
		}
		if($counting["a"] > 0 && $counting["b"] > 0 && $counting["c"] > 0) {
			return $i;
		}
		if($counting["*"] > 0 && 3 <= $i) {
			return $i;
		}
	}

	return 5;
}

function print_trades($trades, $wild_cards) {
	global $loops, $fiddler, $csv;
	$sum = 0;
	$csv .= "$wild_cards,";
	printf("Wild cards? %d\n", $wild_cards);
	printf("%10d trades in all:\n", $loops);
	for($i=3;$i<=5;$i++) {
		$csv .= "$trades[$i],";
		printf("%10d trades with %d cards (%8.5f%%).\n", $trades[$i], $i, 100*$trades[$i]/$loops);
		$sum += $trades[$i] * $i;
	}
	$csv .= "\n";
	printf("Expected no. of cards: %.5f\n", $sum/$loops);
	print("==============================================\n");
}

///////////////////////////////////////////////////////////////////////////
// main program

// variable for later csv print
$csv = "0,3,4,5\n";

for($j=0;$j<10;$j++) {
	$trades[3] = 0;
	$trades[4] = 0;
	$trades[5] = 0;

	// loop
	for($i=0;$i<$loops;$i++) {
		// shuffle the cards
		shuffle($cards);
		// draw 5
		$drawn_cards = array_slice($cards, 0, 5);
		$trade = trade_possible($drawn_cards);
		$trades[$trade]++;
	}

	print_trades($trades, $j);
	$cards[] = "*";
}

print($csv);

?>
