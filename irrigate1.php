<?php

///////////////////////////////////////////////////////////////////////////
// constants

// how many loops?
$loops = 10000000;

// fiddler or extra credit?
$fiddler = 0;

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

$d_sum = 0;
for($i=0;$i<$loops;$i++) {
	// pick 2 random points on circumference

	$angle = (float)rand() * 2 * pi() / (float)getrandmax();
	$x1 = cos($angle);
	$y1 = sin($angle);

	$angle = (float)rand() * 2 * pi() / (float)getrandmax();
	$x2 = cos($angle);
	$y2 = sin($angle);

	// y = mx + b
	$m = ($y2 - $y1) / ($x2 - $x1);
	$b = $y1 - $m * $x1;

	// 0 = mx - y + b

	if($fiddler == 1) {
		$x0 = 0;
		$y0 = 0;
	} else {
		$found = 0;
		while($found == 0) {
			$x0 = (float)rand() * 2 / (float)getrandmax() - 1;
			$y0 = (float)rand() * 2 / (float)getrandmax() - 1;
			$distance = pow($x0*$x0+$y0*$y0, 0.5);
			if($distance <= 1) {
				$found = 1;
			}
		}
	}

	// note that (x1,y1) or (x2,y2) might actually be the closest point

	// https://stackoverflow.com/questions/61341712/calculate-projected-point-location-x-y-on-given-line-startx-y-endx-y
	// l2 = np.sum((p1-p2)**2)
	$l2 = ($x2-$x1)*($x2-$x1)+($y2-$y1)*($y2-$y1);
	// t = np.sum((p3 - p1) * (p2 - p1)) / l2
	$t_tmp = (($x0-$x1)*($x2-$x1) + ($y0-$y1)*($y2-$y1)) / $l2;
	// t = max(0, min(1, np.sum((p3 - p1) * (p2 - p1)) / l2))
	$t = max(0, min(1, $t_tmp));
	// projection = p1 + t * (p2 - p1)
	$x3 = $x1 + $t * ($x2 - $x1);
	$y3 = $y1 + $t * ($y2 - $y1);
	
	// distance
	$d = pow(($x3-$x0)*($x3-$x0)+($y3-$y0)*($y3-$y0),0.5);

//	printf("Point 0 (%8.5f, %8.5f) Point 1 (%8.5f, %8.5f) Point 2 (%8.5f, %8.5f) t %8.5f Distance %.5f\n", $x0, $y0, $x1, $y1, $x2, $y2, $t_tmp, $d);

	$d_sum += $d;
}

printf("Average of distance: %.5f\n", $d_sum / $loops);

?>
