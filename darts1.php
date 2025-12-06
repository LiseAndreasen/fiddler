<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

// x, y, radius
$circle1_1 = array(0, 0, 1);	// red
$circle2_1 = array(0.5, 0, 0.5);	// blue
$circle2_2 = array(-0.5, 0, 0.5);	// blue

$c1 = $circle1_1;
$c2 = $circle2_1;
$c3 = $circle2_2;
// https://en.wikipedia.org/wiki/Problem_of_Apollonius#Algebraic_solutions
printf("(x - %.6f)^2 + (y - %.6f)^2 = (r - %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2\n",
	$c1[0], $c1[1], $c1[2], $c2[0], $c2[1], $c2[2], $c3[0], $c3[1], $c3[2]);
// wolframalpha for the rest

$circle3_1 = array(0, 2/3, 1/3);	// purple
$circle3_2 = array(0, -2/3, 1/3);	// purple

$c1 = $circle1_1;
$c2 = $circle2_1;
$c3 = $circle3_1;
printf("(x - %.6f)^2 + (y - %.6f)^2 = (r - %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2\n",
	$c1[0], $c1[1], $c1[2], $c2[0], $c2[1], $c2[2], $c3[0], $c3[1], $c3[2]);

$circle4_1 = array(0.5, 2/3, 1/6);	// green
$circle4_2 = array(-0.5, 2/3, 1/6);	// green
$circle4_3 = array(0.5, -2/3, 1/6);	// green
$circle4_4 = array(-0.5, -2/3, 1/6);	// green

$c1 = $circle1_1;
$c2 = $circle2_1;
$c3 = $circle4_1;
printf("(x - %.6f)^2 + (y - %.6f)^2 = (r - %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2\n",
	$c1[0], $c1[1], $c1[2], $c2[0], $c2[1], $c2[2], $c3[0], $c3[1], $c3[2]);

$circle5_1 = array(8/11, 6/11, 1/11);	// orange
$circle5_2 = array(-8/11, 6/11, 1/11);	// orange
$circle5_3 = array(8/11, -6/11, 1/11);	// orange
$circle5_4 = array(-8/11, -6/11, 1/11);	// orange

$c1 = $circle1_1;
$c2 = $circle3_1;
$c3 = $circle4_1;
printf("(x - %.6f)^2 + (y - %.6f)^2 = (r - %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2\n",
	$c1[0], $c1[1], $c1[2], $c2[0], $c2[1], $c2[2], $c3[0], $c3[1], $c3[2]);
	
$circle6_1 = array(0.357142, 0.857143, 0.0714285);
$circle6_2 = array(-0.357142, 0.857143, 0.0714285);
$circle6_3 = array(0.357142, -0.857143, 0.0714285);
$circle6_4 = array(-0.357142, -0.857143, 0.0714285);

$c1 = $circle2_1;
$c2 = $circle2_2;
$c3 = $circle3_1;
printf("(x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2, (x - %.6f)^2 + (y - %.6f)^2 = (r + %.6f)^2\n",
	$c1[0], $c1[1], $c1[2], $c2[0], $c2[1], $c2[2], $c3[0], $c3[1], $c3[2]);

$circle7_1 = array(0, 4/15, 1/15);
?>
