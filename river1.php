<?php

// constants

// max length of line
$max_line = 1000;

//////////////////////////////////////////////

// p(n): probability the nth position in the line is a space
$p[1] = 0;
$p[2] = 0;
$p[3] = 0;
// the first word is followed by a space in 4th or 5th position
// probability of each 0.5
$p[4] = 0.5;
$p[5] = 0.5;

// pp(n): probability for n space river
// given the first space is in line 1, position 12

// qq(n): probability for at least n space river
// given the first space is in line 1, position 12

// E(X): expected number of spaces in river
// E(X=n): event where a river has n spaces
// E(X) = E(X=1) * pp(1) + E(X=2) * pp(2) + ...
// E(X) =      1 * pp(1) +      2 * pp(2) + ...

$exp = 0;

// river of length 1
// the event where line 2, position 13 isn't a space
// pp(1) = 1 - p(13)
// qq(1) = 1

// river of length 2
// the event where line 3, position 14 isn't a space
// pp(2) = p(13) * (1 - p(14))
// pp(2) = qq(2) * (1 - p(14))
// qq(2) = p(13)

// qq(3) = p(13) * p(14)
// qq(3) = qq(2) * p(14)
// qq(n) = product [p(13) * ... * p(11 + n)]
// product has n - 1 factors
// qq(n) = qq(n - 1) * p(11 + n)
// pp(n) = qq(n - 1) * (1 - p(11 + n))

for($i=6;$i<=12;$i++) {
	// if there's a space 4 positions away, p(i-4)
	// and then a 3 letter word, probability 0.5
	// or there's a space 5 positions away, p(i-5)
	// and then a 4 letter word, probability 0.5
	// then this is also a space
	$p[$i] = ($p[$i - 4] + $p[$i - 5]) * 0.5;
}

$qq = 1;

for($i=13;$i<=$max_line;$i++) {
	// if there's a space 4 positions away, p(i-4)
	// and then a 3 letter word, probability 0.5
	// or there's a space 5 positions away, p(i-5)
	// and then a 4 letter word, probability 0.5
	// then this is also a space
	$p[$i] = ($p[$i - 4] + $p[$i - 5]) * 0.5;
	
	// x = i - 12
	// pp(x) = pp(i)
	$pp = $qq * (1 - $p[$i]);

	$exp += ($i - 12) * $pp;
	
	// setup for next round
	$qq *= $p[$i];
}

printf("The probability for a space at the nth position, ");
printf("n large: %.4f .\n", $p[$max_line]);

printf("Expected length of river................................:");
printf(" %.4f .\n", $exp);

?>
